<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PostProcessorChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'parent_title',
        'check_point',
        'status',
        'remarks',
    ];
}
