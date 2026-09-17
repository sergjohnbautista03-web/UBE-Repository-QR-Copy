<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Research extends Model
{
  
    use HasFactory, SoftDeletes;

    protected $table = 'researches';

    protected $fillable = [
    'title', 'abstract', 'author_name', 'user_id', 'type',
    'department', 'course', 'program', 'year_published', 'keywords',
    'file_path', 'file_name', 'status', 'rejection_reason',
    'view_count', 'download_count', 'approved_by', 'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'year_published' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function pinnedByUsers()
    {
        return $this->belongsToMany(User::class, 'research_pins')->withTimestamps();
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('abstract', 'like', "%{$term}%")
              ->orWhere('author_name', 'like', "%{$term}%")
              ->orWhere('keywords', 'like', "%{$term}%");
        });
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'thesis' => 'Thesis',
            'dissertation' => 'Dissertation',
            'journal' => 'Journal Article',
            'conference' => 'Conference Paper',
            'research' => 'Research Study',
            'case-study' => 'Case Study',
            default => ucfirst($this->type),
        };
    }
}