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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('github')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('x')->nullable();
            $table->string('instagram')->nullable();
            $table->enum('role', ['admin', 'user', 'recruiter'])->default('user');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            // Add indexes for better performance
            $table->index('email');
            $table->index('role');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
