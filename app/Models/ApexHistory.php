<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApexHistory extends Model
{
    protected $table = 'apex_history';

    protected $fillable = [
        'process_id',
        'process_step_status',
        'step_name',
        'step_user',
        'step_start',
        'step_end',
        'step_duration_min',
        'process_id_num',
    ];

    protected $casts = [
        'step_start' => 'datetime',
        'step_end'   => 'datetime',
    ];

    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class, 'process_id', 'unique_request_id');
    }
}
