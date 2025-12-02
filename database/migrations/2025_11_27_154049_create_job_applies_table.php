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
        Schema::create('job_applies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('job_listing_id')->constrained('job_listings')->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 50);
            $table->string('address', 500);
            $table->string('current_salary', 100)->nullable();
            $table->string('expected_salary', 100);
            $table->string('availability');
            $table->enum('relocation', ['Yes', 'No', 'Other'])->default('No');
            $table->string('relocation_other', 255)->nullable();
            $table->string('linkedin', 500)->nullable();
            $table->string('github', 500)->nullable();
            $table->string('social_media', 500)->nullable();
            $table->string('cv')->nullable(); // CV file path
            $table->string('portfolio_file')->nullable(); // Portfolio file path
            $table->text('cover_letter')->nullable();
            $table->text('reason_applying');
            $table->text('relevant_experience')->nullable();
            $table->enum('status', ['pending', 'shortlisted', 'interview', 'hired', 'rejected'])->default('pending');
            $table->text('notes')->nullable(); // Admin notes
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applies');
    }
};
