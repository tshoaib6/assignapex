<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedChecklist extends Model
{
    //

     protected $fillable = [
        'cst_request_id',
        'checklist_id',
        'driver_id',
        'is_checked',
        'start_od_pic',
        'end_od_pic',
        'starting_km',
        'ending_km',
        'total_km',
        'total_cost',
        'is_endactivity_odmeter',
        'status',
        'docs'
    ];

    // Relationships (optional)
    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }
    protected $casts = [
        'docs' => 'array',
    ];
}
