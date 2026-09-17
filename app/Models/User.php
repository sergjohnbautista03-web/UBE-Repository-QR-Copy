<?php

namespace App\Models;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class User extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, CanResetPasswordTrait;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'department', 'student_id', 'profile_photo',
        'is_active', 'is_approved', 'is_super_admin', 'last_seen_at',
        'year_level', 'course_duration', 'graduation_year', 'graduated_at',
        'researcher_status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'is_approved'       => 'boolean',
        'is_super_admin'    => 'boolean',
        'last_seen_at'      => 'datetime',
        'graduated_at'      => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function researches()
    {
        return $this->hasMany(Research::class);
    }

    public function pinnedResearches()
    {
        return $this->belongsToMany(Research::class, 'research_pins')->withTimestamps();
    }

    // ── Role Checks ──────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isResearcher(): bool
    {
        return $this->role === 'researcher';
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    /**
     * PhilCST students — role='user' with a student_id.
     * They can view full research documents but cannot submit.
     */
    public function isPhilcstStudent(): bool
    {
        return $this->role === 'user' && ! empty($this->student_id);
    }

    /**
     * Who can view the full PDF/document of a research paper:
     * - Admins
     * - Approved researchers
     * - PhilCST students (role=user with student_id)
     *
     * Guests / unauthenticated users cannot — enforce this in your
     * ResearchController@viewFile / @download methods via auth middleware.
     */
    public function canViewFullDocument(): bool
    {
        if (! $this->is_active) return false;

        return match ($this->role) {
            'admin'      => true,
            'researcher' => $this->is_approved,
            'user'       => $this->isPhilcstStudent(),  // must have a student_id
            default      => false,
        };
    }

    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(2));
    }

    // ── Graduation / Expiry ──────────────────────────────────────────────────

    public function isGraduated(): bool
    {
        if (! is_null($this->graduated_at)) {
            return true;
        }
        return $this->role === 'researcher'
            && $this->graduation_year
            && $this->graduation_year < (int) date('Y');
    }

    public function isGraduatingSoon(): bool
    {
        return $this->role === 'researcher'
            && $this->graduation_year
            && $this->graduation_year == (int) date('Y');
    }

    public function yearsUntilGraduation(): ?int
    {
        if (! $this->graduation_year) return null;
        return $this->graduation_year - (int) date('Y');
    }

    public function computeGraduationYear(): ?int
    {
        if (! $this->year_level || ! $this->course_duration) {
            return null;
        }
        return (int) date('Y') + ($this->course_duration - $this->year_level);
    }

    // ── Researcher Status ────────────────────────────────────────────────────

    public function isDroppedOut(): bool
    {
        return $this->researcher_status === 'dropped_out';
    }

    public function isIrregular(): bool
    {
        return $this->researcher_status === 'irregular';
    }

    public function canSubmitResearch(): bool
    {
        if ($this->role !== 'researcher') return false;
        if (! $this->is_active)           return false;
        if (! $this->is_approved)         return false;
        if ($this->isGraduated())         return false;
        if ($this->isDroppedOut())        return false;
        return true;
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getYearLevelLabelAttribute(): string
    {
        return match ((int) $this->year_level) {
            1       => '1st Year',
            2       => '2nd Year',
            3       => '3rd Year',
            4       => '4th Year',
            default => 'N/A',
        };
    }

    public function getResearcherStatusLabelAttribute(): string
    {
        if (! $this->graduation_year)  return 'No Data';
        if ($this->isGraduated())      return 'Graduated';
        if ($this->isGraduatingSoon()) return 'Graduating This Year';
        $years = $this->yearsUntilGraduation();
        if ($years === 1)              return 'Graduating Next Year';
        return 'Active';
    }
}