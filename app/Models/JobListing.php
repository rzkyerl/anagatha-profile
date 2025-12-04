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
        'description',
        'responsibilities',
        'requirements',
        'key_skills',
        'benefits',
        'salary_min',
        'salary_max',
        'salary_display',
        'work_preference',
        'contract_type',
        'contract_type_other',
        'experience_level',
        'experience_level_other',
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
        'responsibilities' => 'array',
        'requirements' => 'array',
        'key_skills' => 'array',
        'benefits' => 'array',
    ];

    /**
     * Get the recruiter that owns the job listing.
     */
    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    /**
     * Get company name from recruiter's company relationship.
     * This accessor provides backward compatibility for views that use $jobListing->company
     */
    public function getCompanyAttribute()
    {
        return $this->recruiter?->company?->name ?? $this->recruiter?->company_name ?? 'Unknown Company';
    }

    /**
     * Get company logo from recruiter's company relationship.
     * This accessor provides backward compatibility for views that use $jobListing->company_logo
     */
    public function getCompanyLogoAttribute()
    {
        return $this->recruiter?->company?->logo ?? $this->recruiter?->company_logo;
    }

    /**
     * Get location from recruiter's company relationship.
     * This accessor provides backward compatibility for views that use $jobListing->location
     */
    public function getLocationAttribute()
    {
        return $this->recruiter?->company?->location ?? 'Location not specified';
    }

    /**
     * Get industry from recruiter's company relationship.
     * This accessor provides backward compatibility for views that use $jobListing->industry
     */
    public function getIndustryAttribute()
    {
        return $this->recruiter?->company?->industry ?? $this->recruiter?->industry;
    }

    /**
     * Get industry_other from recruiter's company relationship.
     * This accessor provides backward compatibility for views that use $jobListing->industry_other
     */
    public function getIndustryOtherAttribute()
    {
        return $this->recruiter?->company?->industry_other ?? $this->recruiter?->industry_other;
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
