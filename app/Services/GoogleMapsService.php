<?php

namespace App\Services;

use App\Exceptions\UnknownPlaceException;
use App\Models\GeoPoint;
use Illuminate\Support\Facades\Http;

class GoogleMapsService implements GeocodingService
{
    private $endpoint;

    /**
     * GoogleMapsService constructor.
     * @param string $endpoint
     */
    public function __construct()
    {
        $this->endpoint = 'https://maps.googleapis.com/maps/api/geocode/json';
    }

    public function getGeoPointFromAddress(string $address): GeoPoint
    {
        $url = $this->endpoint;
        $url .= '?address=' . $address;
        $url .= '&key=' . env('GOOGLE_MAPS_API_KEY');
        $response = Http::get($url)->json()['results'];
        if (!isset($response[0])) {
            throw new UnknownPlaceException('Unknown place: ' . $address);
        }
        $location = $response[0]['geometry']['location'];
        return new GeoPoint($location['lat'], $location['lng']);
    }
}
