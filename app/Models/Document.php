<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'division_id',
        'document_type_id',
        'title',
        'description',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    // New relationships for multi-file and comments
    public function files()
    {
        return $this->hasMany(DocumentFile::class);
    }

    public function activeFiles()
    {
        return $this->hasMany(DocumentFile::class)->where('status', 'active');
    }

    public function latestFile()
    {
        return $this->hasOne(DocumentFile::class)->where('status', 'active')->latest('version');
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    public function topLevelComments()
    {
        return $this->hasMany(DocumentComment::class)->whereNull('parent_id')->with('user', 'replies.user');
    }

    // Helper methods
    public function getLatestVersionAttribute()
    {
        return $this->files()->max('version') ?? 0;
    }

    public function getNextVersionAttribute()
    {
        return $this->latest_version + 1;
    }

    public function hasFiles()
    {
        return $this->files()->count() > 0;
    }

    public function getMainFileAttribute()
    {
        return $this->files()->where('status', 'active')->latest('version')->first();
    }
}
