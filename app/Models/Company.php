<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'industry',
        'industry_other',
        'location',
    ];

    /**
     * Get the user (recruiter) that owns this company.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all job listings for this company.
     */
    public function jobListings(): HasMany
    {
        return $this->hasMany(JobListing::class, 'recruiter_id', 'user_id');
    }
}
