<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldTestResult extends Model
{
    //

     protected $fillable = [
        'cst_request_id',
        'driver_id',
        'checklist_id',
        'start_time',
        'end_time',
        'working_hours',
        'notes',
        'status',
        'docs'
    ];

    // Optional: define relationships
    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
         protected $casts = [
        'docs' => 'array',
    ];
}
