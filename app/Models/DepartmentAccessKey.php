<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentAccessKey extends Model
{
    protected $fillable = [
        'department',
        'semester',
        'school_year',
        'access_key',
        'access_key_plain',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active'  => 'boolean',
    ];
}
