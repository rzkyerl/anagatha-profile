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
        if (!Schema::hasColumn('users', 'minimum_degree')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('minimum_degree', ['Senior High School', 'Diploma', 'Bachelor', 'Master', 'MBA', 'Ph.D', 'Other'])->nullable()->after('industry_other');
            });
        }
        
        if (!Schema::hasColumn('users', 'minimum_degree_other')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('minimum_degree_other', 255)->nullable()->after('minimum_degree');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['minimum_degree', 'minimum_degree_other']);
        });
    }
};
