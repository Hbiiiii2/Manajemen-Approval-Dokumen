<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DivisionRole extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'division_id',
        'role_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
