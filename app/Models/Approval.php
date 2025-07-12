<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'level_id',
        'status',
        'notes',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvalLevel()
    {
        return $this->belongsTo(ApprovalLevel::class, 'level_id');
    }

    public function level()
    {
        return $this->belongsTo(ApprovalLevel::class, 'level_id');
    }
}
