<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'scenario_type',
        'scenario',
        'description',
        'network',
        'duration',
        'pause',
        'number_of_devices'
    ];


    public function similarTypes()
{
    return self::where('scenario_type', $this->scenario_type)
               ->get();
}

}
