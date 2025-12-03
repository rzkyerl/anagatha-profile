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
        // Check if columns already exist before adding
        if (!Schema::hasColumn('users', 'industry')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('industry', 100)->nullable();
            });
        }
        
        if (!Schema::hasColumn('users', 'industry_other')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('industry_other', 255)->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['industry', 'industry_other']);
        });
    }
};

