<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CstPostProcessor extends Model
{
    //

    protected $fillable = [
        'cst_request_id',
        'checklist_ids',
        'docs',
        'status',
    ];

    protected $casts = [
        'checklist_ids' => 'array', 
        'docs' => 'array',
    ];

    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }
}
