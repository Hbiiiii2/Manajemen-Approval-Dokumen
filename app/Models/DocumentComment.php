<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'parent_id',
        'comment',
        'type'
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(DocumentComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(DocumentComment::class, 'parent_id');
    }

    // Helper methods
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function canBeEditedBy($user)
    {
        return $this->user_id === $user->id || $user->role->name === 'admin';
    }
}
