<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostProcessorFinalChecklistConfirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'cst_request_id',
        'checklist_confirmation',
        'checklist_id',
        'actual_km',
        'actual_hours',
        'docs',
        'status',

    ];

    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class);
    }
    protected $casts = [
        'docs' => 'array',
        'checklist_id' => 'array',
    ];
}
