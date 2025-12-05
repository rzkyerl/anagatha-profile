<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'github_id',
        'role',
        'phone',
        'company_name',
        'company_logo',
        'job_title',
        'job_title_other',
        'industry',
        'industry_other',
        'minimum_degree',
        'minimum_degree_other',
        'avatar',
        'github',
        'linkedin',
        'x',
        'instagram',
        'email_verified_at',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function jobApplies(): HasMany
    {
        return $this->hasMany(JobApply::class, 'user_id');
    }

    public function jobListings(): HasMany
    {
        return $this->hasMany(JobListing::class, 'recruiter_id');
    }

    /**
     * Get the company associated with this user (for recruiters).
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Send the email verification notification.
     * Override default to use custom notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
