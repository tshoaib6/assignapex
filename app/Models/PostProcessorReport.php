<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostProcessorReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'cst_request_id',
        'report_link',
        'notes',
        'docs',
        'status',
    ];

    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class, 'cst_request_id');
    }
        protected $casts = [
        'docs' => 'array',
    ];
}
