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
        Schema::table('job_listings', function (Blueprint $table) {
            // Add JSON columns for structured job details
            $table->json('responsibilities')->nullable()->after('description');
            $table->json('requirements')->nullable()->after('responsibilities');
            $table->json('key_skills')->nullable()->after('requirements');
            $table->json('benefits')->nullable()->after('key_skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn(['responsibilities', 'requirements', 'key_skills', 'benefits']);
        });
    }
};
