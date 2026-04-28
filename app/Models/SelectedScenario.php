<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedScenario extends Model
{
    //

     protected $fillable = [
        'cst_request_id',
        'scenario',
        'description',
        'network',
        'duration',
        'pause',
        'devices',
        'status',
    ];

     public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }
}
