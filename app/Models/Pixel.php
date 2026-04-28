<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pixel extends Model
{
    use HasFactory;

    protected $fillable = ['grid_id', 'region', 'city', 'lat', 'lon'];

    protected $casts = [
        'grid_id' => 'string',
        'region'  => 'string',
        'city'    => 'string',
        'lat'     => 'decimal:7',
        'lon'     => 'decimal:7',
    ];
}
