<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLeaderEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'cst_request_id',
        'status',
        'decision',
        'docs',
        'notes',
    ];


    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }
    
    protected $casts = [
        'docs' => 'array',
    ];
}

