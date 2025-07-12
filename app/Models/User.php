<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'division_id',
        'profile_photo',
        'phone',
        'location',
        'about_me',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function divisionRoles()
    {
        return $this->hasMany(DivisionRole::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    // Get user's role in specific division
    public function getRoleInDivision($divisionId)
    {
        return $this->divisionRoles()
            ->where('division_id', $divisionId)
            ->first();
    }

    // Get user's primary division
    public function getPrimaryDivision()
    {
        return $this->divisionRoles()
            ->where('is_primary', true)
            ->with('division')
            ->first();
    }

    // Check if user has access to division
    public function hasAccessToDivision($divisionId)
    {
        return $this->divisionRoles()
            ->where('division_id', $divisionId)
            ->exists();
    }
}
