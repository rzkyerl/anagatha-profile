<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobListing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'company',
        'company_logo',
        'description',
        'salary_min',
        'salary_max',
        'salary_display',
        'work_preference',
        'contract_type',
        'contract_type_other',
        'experience_level',
        'experience_level_other',
        'location',
        'industry',
        'industry_other',
        'minimum_degree',
        'minimum_degree_other',
        'recruiter_id',
        'verified',
        'status',
        'posted_at',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'verified' => 'boolean',
        'posted_at' => 'datetime',
    ];

    /**
     * Get the recruiter that owns the job listing.
     */
    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    /**
     * Scope to get only active job listings
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get only verified job listings
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Get all job applications for this job listing.
     */
    public function jobApplies(): HasMany
    {
        return $this->hasMany(JobApply::class, 'job_listing_id');
    }
}
