<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Database Seeder
 * 
 * Main seeder class that calls all other seeders.
 * Run with: php artisan db:seed
 * 
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * This method is called when running: php artisan db:seed
     * It will execute all seeders listed in the $this->call() array.
     * 
     * Seeders are executed in the order they are listed.
     * 
     * @return void
     */
    public function run(): void
    {
        // ============================================
        // USER SEEDERS
        // ============================================
        // Must run first as other seeders depend on users
        // $this->command->info('Seeding users...');
        // $this->call([
        //     UserSeeder::class,
        // ]);

        // ============================================
        // COMPANY SEEDERS
        // ============================================
        // Requires: Users (recruiters)
        // $this->command->info('Seeding companies...');
        // $this->call([
        //     CompanySeeder::class,
        // ]);

        // ============================================
        // JOB LISTING SEEDERS
        // ============================================
        // Requires: Users (recruiters) and Companies
        // $this->command->info('Seeding job listings...');
        // $this->call([
        //     JobListingSeeder::class,
        // ]);

        // ============================================
        // JOB APPLICATION SEEDERS
        // ============================================
        // Requires: Users (regular users) and Job Listings
        // $this->command->info('Seeding job applications...');
        // $this->call([
        //     JobApplySeeder::class,
        // ]);

        // ============================================
        // ADD MORE SEEDERS HERE
        // ============================================
        // Example:
        // $this->call([
        //     CompanySeeder::class,
        //     NotificationSeeder::class,
        // ]);
        
        $this->command->info('Database seeding completed!');
    }
}
