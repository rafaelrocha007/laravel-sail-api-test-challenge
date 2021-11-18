<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Gender;
use Tests\TestCase;

class CustomersApiTest extends TestCase
{
    public function test_unauthorized_customers_request()
    {
        $response = $this->getJson('/api/customers');
        $response->assertStatus(401);
    }

    public function test_unauthorized_customer_request()
    {
        $response = $this->getJson('/api/customers/1');
        $response->assertStatus(401);
    }

    public function test_authorized_customers_request()
    {
        $accessToken = $this->postJson(
            '/api/register',
            [
                "name"                  => "user",
                "email"                 => "user@mail.com",
                "password"              => "12345678",
                "password_confirmation" => "12345678"
            ]
        )->json('token');
        $gender = Gender::create(['description' => 'Female']);
        $company = Company::create(['name' => 'My company']);
        $city = City::create(['name' => 'My city', 'state' => 'AZ']);
        $customer = Customer::create(
            [
                'first_name' => 'user',
                'last_name'  => 'test',
                'email'      => 'test@email.com',
                'gender_id'  => $gender->id,
                'company_id' => $company->id,
                'city_id'    => $city->id
            ]
        );
        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $accessToken
        )->getJson('/api/customers/' . $customer->id);
        $response->assertStatus(200);
    }

    public function test_authorized_not_found_customer_request()
    {
        $accessToken = $this->postJson(
            '/api/register',
            [
                "name"                  => "user",
                "email"                 => "user@mail.com",
                "password"              => "12345678",
                "password_confirmation" => "12345678"
            ]
        )->json('token');
        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $accessToken
        )->getJson('/api/customers/123');
        $response->assertStatus(404);
    }
}
