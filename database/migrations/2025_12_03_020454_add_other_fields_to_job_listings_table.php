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
            if (!Schema::hasColumn('job_listings', 'contract_type_other')) {
                $table->string('contract_type_other', 255)->nullable()->after('contract_type');
            }
            if (!Schema::hasColumn('job_listings', 'experience_level_other')) {
                $table->string('experience_level_other', 255)->nullable()->after('experience_level');
            }
            if (!Schema::hasColumn('job_listings', 'industry_other')) {
                $table->string('industry_other', 255)->nullable()->after('industry');
            }
            if (!Schema::hasColumn('job_listings', 'minimum_degree_other')) {
                $table->string('minimum_degree_other', 255)->nullable()->after('minimum_degree');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            if (Schema::hasColumn('job_listings', 'contract_type_other')) {
                $table->dropColumn('contract_type_other');
            }
            if (Schema::hasColumn('job_listings', 'experience_level_other')) {
                $table->dropColumn('experience_level_other');
            }
            if (Schema::hasColumn('job_listings', 'industry_other')) {
                $table->dropColumn('industry_other');
            }
            if (Schema::hasColumn('job_listings', 'minimum_degree_other')) {
                $table->dropColumn('minimum_degree_other');
            }
        });
    }
};
