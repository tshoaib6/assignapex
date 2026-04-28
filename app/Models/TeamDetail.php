<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class TeamDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'department', 'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
