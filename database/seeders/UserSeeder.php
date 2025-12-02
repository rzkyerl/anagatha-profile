<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * User Seeder
 * 
 * Seeds the database with sample users for testing.
 * Creates admin, recruiter, and regular user accounts.
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        // ============================================
        // ADMIN USERS
        // ============================================
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Created admin user: admin@example.com');

        // ============================================
        // RECRUITER USERS
        // ============================================
        $recruiters = [
            [
                'email' => 'recruiter@example.com',
                'first_name' => 'Recruiter',
                'last_name' => 'Demo',
                'phone' => '6281234567890',
                'company_name' => 'Demo Company',
                'job_title' => 'HR Manager',
            ],
            [
                'email' => 'recruiter2@example.com',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'phone' => '6281234567891',
                'company_name' => 'Tech Solutions Inc.',
                'job_title' => 'Talent Acquisition Specialist',
            ],
            [
                'email' => 'recruiter3@example.com',
                'first_name' => 'Michael',
                'last_name' => 'Chen',
                'phone' => '6281234567892',
                'company_name' => 'Digital Innovations',
                'job_title' => 'Recruitment Manager',
            ],
            [
                'email' => 'recruiter4@example.com',
                'first_name' => 'Lisa',
                'last_name' => 'Williams',
                'phone' => '6281234567893',
                'company_name' => 'Cloud Services Co.',
                'job_title' => 'HR Business Partner',
            ],
        ];

        foreach ($recruiters as $recruiter) {
            User::updateOrCreate(
                ['email' => $recruiter['email']],
                [
                    'first_name' => $recruiter['first_name'],
                    'last_name' => $recruiter['last_name'],
                    'password' => Hash::make('password'),
                    'role' => 'recruiter',
                    'phone' => $recruiter['phone'],
                    'company_name' => $recruiter['company_name'],
                    'job_title' => $recruiter['job_title'],
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Created ' . count($recruiters) . ' recruiter users.');

        // ============================================
        // REGULAR USERS (EMPLOYEES/APPLICANTS)
        // ============================================
        $regularUsers = [
            ['first_name' => 'Ahmad', 'last_name' => 'Santoso', 'email' => 'ahmad.santoso@example.com'],
            ['first_name' => 'Budi', 'last_name' => 'Wijaya', 'email' => 'budi.wijaya@example.com'],
            ['first_name' => 'Citra', 'last_name' => 'Kurniawan', 'email' => 'citra.kurniawan@example.com'],
            ['first_name' => 'Dewi', 'last_name' => 'Prasetyo', 'email' => 'dewi.prasetyo@example.com'],
            ['first_name' => 'Eko', 'last_name' => 'Sari', 'email' => 'eko.sari@example.com'],
            ['first_name' => 'Fajar', 'last_name' => 'Hidayat', 'email' => 'fajar.hidayat@example.com'],
            ['first_name' => 'Gita', 'last_name' => 'Nugroho', 'email' => 'gita.nugroho@example.com'],
            ['first_name' => 'Hadi', 'last_name' => 'Rahayu', 'email' => 'hadi.rahayu@example.com'],
            ['first_name' => 'Indra', 'last_name' => 'Saputra', 'email' => 'indra.saputra@example.com'],
            ['first_name' => 'Joko', 'last_name' => 'Lestari', 'email' => 'joko.lestari@example.com'],
            ['first_name' => 'Kartika', 'last_name' => 'Setiawan', 'email' => 'kartika.setiawan@example.com'],
            ['first_name' => 'Lina', 'last_name' => 'Dewi', 'email' => 'lina.dewi@example.com'],
            ['first_name' => 'Mario', 'last_name' => 'Santoso', 'email' => 'mario.santoso@example.com'],
            ['first_name' => 'Nina', 'last_name' => 'Wijaya', 'email' => 'nina.wijaya@example.com'],
            ['first_name' => 'Omar', 'last_name' => 'Kurniawan', 'email' => 'omar.kurniawan@example.com'],
            ['first_name' => 'Putri', 'last_name' => 'Prasetyo', 'email' => 'putri.prasetyo@example.com'],
            ['first_name' => 'Rizki', 'last_name' => 'Sari', 'email' => 'rizki.sari@example.com'],
            ['first_name' => 'Sari', 'last_name' => 'Hidayat', 'email' => 'sari.hidayat@example.com'],
            ['first_name' => 'Tono', 'last_name' => 'Nugroho', 'email' => 'tono.nugroho@example.com'],
            ['first_name' => 'Umi', 'last_name' => 'Rahayu', 'email' => 'umi.rahayu@example.com'],
        ];

        foreach ($regularUsers as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Created ' . count($regularUsers) . ' regular users.');
    }
}


