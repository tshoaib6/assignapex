<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_image', // ✅ allows saving uploaded image
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ✅ Return profile image path OR null if not uploaded
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        return $this->profile_image
            ? asset('storage/' . $this->profile_image)
            : null; // ✅ return null so we can handle FA icon in Blade
    }

    public function testerAssignments()
{
    return $this->hasMany(TesterAssignment::class, 'tester_id');
}

public function teamDetail() {
    return $this->hasOne(TeamDetail::class);
}


}
