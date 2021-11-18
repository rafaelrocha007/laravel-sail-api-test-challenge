<?php

namespace App\Services;

use App\Exceptions\UnknownPlaceException;
use App\Models\City;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Gender;
use App\Models\Title;

class ImportCustomerService
{
    private $geocodingService;

    /**
     * ImportCustomerService constructor.
     */
    public function __construct(GeocodingService $geocodingService)
    {
        $this->geocodingService = $geocodingService;
    }

    public function createCustomerFromArray(array $data): void
    {
        $this->validateCSVInputData($data);
        $parsedData = $this->parseFileData($data);
        $customer = Customer::firstOrCreate(
            [
                'email' => $data['email']
            ],
            $parsedData
        );
        if ($customer->latitude || $customer->longitude) {
            return;
        }
        try {
            $geopoint = $this->geocodingService->getGeoPointFromAddress($data['city']);
            $customer->update(
                [
                    'latitude'  => $geopoint->getLat(),
                    'longitude' => $geopoint->getLong()
                ]
            );
        } catch (UnknownPlaceException $e) {
            report($e);
        }
    }

    private function validateCSVInputData(array $data): void
    {
        if (!$data || !count($data)) {
            throw new \Exception('Data must not be empty');
        }

        $this->validateMandatoryFields($data);
    }

    private function validateMandatoryFields(array $data)
    {
        $mandatoryFields = [
            'first_name',
            'email',
            'gender',
            'company',
            'city'
        ];

        foreach ($mandatoryFields as $field) {
            if (!array_key_exists($field, $data) || !$data[$field]) {
                throw new \Exception("Field $field must not be empty");
            }
        }
    }

    private function parseFileData(array $data): array
    {
        $cityAsArray = explode(',', $data['city']);
        $cityName = $cityAsArray[0];
        $state = $cityAsArray[1];
        $gender = $this->getOrCreateGender($data['gender']);
        $city = $this->getOrCreateCity($cityName, $state);
        $company = $this->getOrCreateCompany($data['company']);
        $title = $this->getOrCreateTitle($data['title']);
        return [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'gender_id'  => $gender->id,
            'title_id'   => $title->id ?? null,
            'company_id' => $company->id,
            'city_id'    => $city->id,
        ];
    }

    public function getOrCreateCity(string $name, string $state): City
    {
        $name = ucfirst(strtolower(trim($name)));
        $state = strtoupper(trim($state));

        if (!$name || !$state) {
            throw new \Exception('Name and state are mandatory fields');
        }

        return City::firstOrCreate(
            [
                'name'  => $name,
                'state' => $state
            ]
        );
    }

    public function getOrCreateGender(string $description): Gender
    {
        if (!$description) {
            throw new \Exception('Description must be filled');
        }

        return Gender::firstOrCreate(
            [
                'description' => ucfirst(strtolower(trim($description)))
            ]
        );
    }

    public function getOrCreateCompany(string $name)
    {
        $name = ucfirst(strtolower(trim($name)));

        if (!$name) {
            throw new \Exception('Company name must be filled');
        }

        return Company::firstOrCreate(
            [
                'name' => $name
            ]
        );
    }

    public function getOrCreateTitle(string $title): ?Title
    {
        $title = trim($title);

        if (!$title) {
            return null;
        }

        return Title::firstOrCreate(
            [
                'description' => $title
            ]
        );
    }
}
