<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'gender_id',
        'title_id',
        'company_id',
        'city_id',
        'latitude',
        'longitude'
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
