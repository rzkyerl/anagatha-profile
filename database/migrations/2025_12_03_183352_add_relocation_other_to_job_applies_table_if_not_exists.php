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
        // Check if column doesn't exist before adding it
        if (!Schema::hasColumn('job_applies', 'relocation_other')) {
            Schema::table('job_applies', function (Blueprint $table) {
                $table->string('relocation_other', 255)->nullable()->after('relocation');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if column exists before dropping it
        if (Schema::hasColumn('job_applies', 'relocation_other')) {
            Schema::table('job_applies', function (Blueprint $table) {
                $table->dropColumn('relocation_other');
            });
        }
    }
};
