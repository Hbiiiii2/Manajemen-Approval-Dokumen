<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'file_path',
        'original_name',
        'file_extension',
        'file_size',
        'version',
        'status',
        'description'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'version' => 'integer',
    ];

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    // Helper methods
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getDownloadUrlAttribute()
    {
        return route('documents.download', ['file' => $this->id]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByVersion($query, $version)
    {
        return $query->where('version', $version);
    }
}
