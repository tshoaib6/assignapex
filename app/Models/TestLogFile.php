<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestLogFile extends Model
{
    //

     protected $fillable = [
        'cst_request_id',
        'file_link',
        'file_quantity',
        'docs',
        'status',
    ];

    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }
          protected $casts = [
        'docs' => 'array',
    ];
}
