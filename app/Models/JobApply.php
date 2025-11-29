<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApply extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'job_applies';

    protected $fillable = [
        'user_id',
        'job_listing_id',
        'full_name',
        'email',
        'phone',
        'address',
        'current_salary',
        'expected_salary',
        'availability',
        'relocation',
        'linkedin',
        'github',
        'social_media',
        'cv',
        'portfolio_file',
        'cover_letter',
        'reason_applying',
        'relevant_experience',
        'status',
        'notes',
        'applied_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jobListing(): BelongsTo
    {
        return $this->belongsTo(JobListing::class, 'job_listing_id');
    }
}
