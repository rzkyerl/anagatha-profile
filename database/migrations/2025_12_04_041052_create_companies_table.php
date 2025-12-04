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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('logo', 255)->nullable();
            $table->enum('industry', ['Technology', 'Healthcare', 'Finance', 'Education', 'Manufacturing', 'Retail', 'Real Estate', 'Hospitality', 'Transportation & Logistics', 'Energy', 'Telecommunications', 'Media & Entertainment', 'Consulting', 'Legal', 'Construction', 'Agriculture', 'Food & Beverage', 'Automotive', 'Aerospace', 'Pharmaceuticals', 'Other'])->nullable();
            $table->string('industry_other', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('user_id');
            $table->index('industry');
            $table->index('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
