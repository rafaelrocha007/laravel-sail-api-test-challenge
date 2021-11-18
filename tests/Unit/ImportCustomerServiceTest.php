<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\GeoPoint;
use App\Services\GoogleMapsService;
use App\Services\ImportCustomerService;
use Tests\TestCase;

class ImportCustomerServiceTest extends TestCase
{
    public function test_should_throw_when_mandatory_field_missing()
    {
        $googleMapsServiceStub = $this->createMock(GoogleMapsService::class);
        $googleMapsServiceStub->method('getGeoPointFromAddress')
            ->willReturn(new GeoPoint(123, 123));
        $customerData = [
            'first_name' => 'Rafael',
            'last_name'  => 'Rocha',
        ];
        $this->expectException(\Exception::class);
        $sut = new ImportCustomerService($googleMapsServiceStub);
        $sut->createCustomerFromArray($customerData);
    }

    public function test_import_new_customer()
    {
        $googleMapsServiceStub = $this->createMock(GoogleMapsService::class);
        $googleMapsServiceStub->method('getGeoPointFromAddress')
            ->willReturn(new GeoPoint(123, 123));
        $customerData = [
            'first_name' => 'Rafael',
            'last_name'  => 'Rocha',
            'email'      => 'mail@mail.com',
            'city'       => 'Pouso Alegre, MG',
            'company'    => 'Meveto',
            'gender'     => 'Male',
            'title'      => 'PHP Engineer'
        ];
        $sut = new ImportCustomerService($googleMapsServiceStub);
        $sut->createCustomerFromArray($customerData);
        $customer = Customer::whereEmail('mail@mail.com')->first();
        $this->assertEquals($customer->first_name, 'Rafael');
        $this->assertEquals($customer->last_name, 'Rocha');
        $this->assertEquals($customer->email, 'mail@mail.com');
        $this->assertEquals($customer->company->name, 'Meveto');
        $this->assertEquals($customer->city->name, 'Pouso alegre');
        $this->assertEquals($customer->city->state, 'MG');
        $this->assertEquals($customer->gender->description, 'Male');
        $this->assertEquals($customer->title->description, 'PHP Engineer');
        $this->assertEquals($customer->latitude, 123);
        $this->assertEquals($customer->longitude, 123);
    }
}
