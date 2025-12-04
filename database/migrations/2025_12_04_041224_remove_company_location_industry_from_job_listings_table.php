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
            $table->dropColumn(['company', 'location', 'industry', 'industry_other']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->string('company', 255)->after('title');
            $table->string('location', 255)->after('experience_level_other');
            $table->enum('industry', ['Technology', 'Finance', 'Healthcare', 'Education', 'E-commerce', 'Manufacturing', 'Consulting', 'Media', 'Other'])->nullable()->after('location');
            $table->string('industry_other', 255)->nullable()->after('industry');
        });
    }
};
