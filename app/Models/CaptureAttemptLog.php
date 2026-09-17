<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaptureAttemptLog extends Model
{
    protected $fillable = [
        'research_id',
        'user_id',
        'event_type',
        'viewer_name',
        'viewer_email',
        'viewer_department',
        'viewer_scope',
        'ip_address',
        'user_agent',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
