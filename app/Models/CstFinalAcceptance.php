<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CstFinalAcceptance extends Model
{
    use HasFactory;

    protected $fillable = [
        'cst_request_id',
        'decision',
        'notes',
        'docs',
        'status',
    ];

    /**
     * Relationship to the CST request
     */
    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }
    
    protected $casts = [
        'docs' => 'array',
    ];
}
