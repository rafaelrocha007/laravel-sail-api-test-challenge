<?php

namespace App\Services;

use App\Models\GeoPoint;

interface GeocodingService
{
    public function getGeoPointFromAddress(string $address): GeoPoint;
}
