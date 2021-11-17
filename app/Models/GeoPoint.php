<?php

namespace App\Models;

class GeoPoint
{
    private $latitude;
    private $longitude;

    public function __construct($lat, $long)
    {
        $this->latitude = $lat;
        $this->longitude = $long;
    }

    public function getPoint(): array
    {
        return [
            'lat'  => $this->latitude,
            'long' => $this->longitude
        ];
    }

    public function getLat(): float
    {
        return $this->latitude;
    }

    public function getLong(): float
    {
        return $this->longitude;
    }
}
