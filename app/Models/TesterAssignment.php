<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TesterAssignment extends Model
{
    //

     protected $fillable = [
        'cst_request_id',
        'tester_id',
        'contact', 
        'email',
        'note',
        'docs',
        'status',
        'user_id',
    ];

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cstRequest()
    {
        return $this->belongsTo(CSTRequest::class, 'cst_request_id');
    }
     protected $casts = [
        'docs' => 'array',
    ];
}
