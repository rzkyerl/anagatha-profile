<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('company', 255);
            $table->string('company_logo', 255)->nullable();
            $table->text('description')->nullable();
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->string('salary_display', 255)->default('Not Disclose'); // "IDR 25,000,000 - IDR 35,000,000" or "Not Disclose"
            $table->enum('work_preference', ['wfo', 'wfh', 'hybrid'])->default('wfo');
            $table->enum('contract_type', ['Full Time', 'Contract', 'Part Time', 'Other'])->default('Full Time');
            $table->string('contract_type_other', 255)->nullable();
            $table->enum('experience_level', ['Entry', '1-3 Years', '3-5 Years', '5+ Years', 'Senior', 'Mid Level', 'Other'])->nullable();
            $table->string('experience_level_other', 255)->nullable();
            $table->string('location', 255);
            $table->enum('industry', ['Technology', 'Finance', 'Healthcare', 'Education', 'E-commerce', 'Manufacturing', 'Consulting', 'Media', 'Other'])->nullable();
            $table->string('industry_other', 255)->nullable();
            $table->enum('minimum_degree', ['Senior High School', 'Diploma', 'Bachelor', 'Master', 'MBA', 'Ph.D', 'Other'])->nullable();
            $table->string('minimum_degree_other', 255)->nullable();
            $table->foreignId('recruiter_id')->constrained('users')->onDelete('cascade');
            $table->boolean('verified')->default(false);
            $table->enum('status', ['draft', 'active', 'inactive', 'closed'])->default('draft');
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index('status');
            $table->index('recruiter_id');
            $table->index('work_preference');
            $table->index('contract_type');
            $table->index('industry');
            $table->index('experience_level');
            $table->index('posted_at');
            $table->index('verified');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
