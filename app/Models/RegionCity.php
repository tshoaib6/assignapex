<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'area',
        'city_highway',
        'test_type',
        'lat',
        'lon',
    ];
}
