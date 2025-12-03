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
        // Check if column already exists before adding
        if (!Schema::hasColumn('users', 'job_title_other')) {
            Schema::table('users', function (Blueprint $table) {
                // Try to add after job_title if it exists, otherwise just add the column
                if (Schema::hasColumn('users', 'job_title')) {
                    $table->string('job_title_other', 255)->nullable()->after('job_title');
                } else {
                    $table->string('job_title_other', 255)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'job_title_other')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('job_title_other');
            });
        }
    }
};

