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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('phone');
            $table->enum('job_title', ['HR Manager', 'HR Business Partner', 'Talent Acquisition Specialist', 'Recruitment Manager', 'HR Director', 'HR Coordinator', 'Recruiter', 'Senior Recruiter', 'HR Generalist', 'People Operations Manager', 'Other'])->nullable()->after('company_name');
            $table->string('job_title_other', 255)->nullable()->after('job_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'job_title', 'job_title_other']);
        });
    }
};
