<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role',
        'total_points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'email_verified_at' => 'datetime',
    ];

    public function reportedReports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }

    public function verifiedReports()
    {
        return $this->hasMany(Report::class, 'admin_id');
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return strtolower($this->role) === 'admin';
    }
}
