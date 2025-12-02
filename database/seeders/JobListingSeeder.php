<?php

namespace Database\Seeders;

use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Job Listing Seeder
 * 
 * Seeds the database with sample job listings for testing.
 * Creates job listings for different recruiters with various statuses.
 */
class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        // Get recruiters (users with role 'recruiter')
        $recruiters = User::where('role', 'recruiter')->get();

        if ($recruiters->isEmpty()) {
            $this->command->warn('No recruiters found. Please run UserSeeder first.');
            return;
        }

        $jobTitles = [
            'Senior Software Engineer',
            'Frontend Developer',
            'Backend Developer',
            'Full Stack Developer',
            'Product Manager',
            'UX/UI Designer',
            'Data Analyst',
            'DevOps Engineer',
            'QA Engineer',
            'Mobile Developer',
            'Marketing Manager',
            'Sales Executive',
            'HR Specialist',
            'Finance Manager',
            'Business Analyst',
        ];

        $companies = [
            'Tech Solutions Inc.',
            'Digital Innovations',
            'Cloud Services Co.',
            'Startup Hub',
            'Enterprise Solutions',
            'Creative Agency',
            'E-commerce Platform',
            'FinTech Startup',
            'Healthcare Tech',
            'Education Platform',
        ];

        $locations = [
            'Jakarta',
            'Bandung',
            'Surabaya',
            'Yogyakarta',
            'Bali',
            'Medan',
            'Semarang',
            'Makassar',
        ];

        $industries = [
            'Technology',
            'Finance',
            'Healthcare',
            'Education',
            'E-commerce',
            'Manufacturing',
            'Consulting',
            'Media',
        ];

        $workPreferences = ['wfo', 'wfh', 'hybrid'];
        $contractTypes = ['Full Time', 'Contract', 'Part Time'];
        $experienceLevels = ['Entry', '1-3 Years', '3-5 Years', '5+ Years', 'Senior', 'Mid Level'];
        $degrees = ['Senior High School', 'Diploma', 'Bachelor', 'Master', 'MBA', 'Ph.D'];
        $statuses = ['draft', 'active', 'inactive', 'closed'];

        // Create 30 job listings
        for ($i = 0; $i < 30; $i++) {
            $recruiter = $recruiters->random();
            $title = $jobTitles[array_rand($jobTitles)];
            $company = $companies[array_rand($companies)];
            $location = $locations[array_rand($locations)];
            $industry = $industries[array_rand($industries)];
            $workPreference = $workPreferences[array_rand($workPreferences)];
            $contractType = $contractTypes[array_rand($contractTypes)];
            $experienceLevel = $experienceLevels[array_rand($experienceLevels)];
            $degree = $degrees[array_rand($degrees)];
            $status = $statuses[array_rand($statuses)];

            // Generate salary range
            $salaryMin = rand(5000000, 15000000); // 5M - 15M
            $salaryMax = $salaryMin + rand(5000000, 20000000); // Add 5M - 20M
            $salaryDisplay = "IDR " . number_format($salaryMin, 0, ',', '.') . " - IDR " . number_format($salaryMax, 0, ',', '.');

            // Random verified status (70% verified)
            $verified = rand(1, 10) <= 7;

            // Posted at date (random date in last 3 months)
            $postedAt = now()->subDays(rand(0, 90));

            JobListing::create([
                'title' => $title,
                'company' => $company,
                'company_logo' => null,
                'description' => $this->generateDescription($title, $company),
                'salary_min' => $salaryMin,
                'salary_max' => $salaryMax,
                'salary_display' => $salaryDisplay,
                'work_preference' => $workPreference,
                'contract_type' => $contractType,
                'experience_level' => $experienceLevel,
                'location' => $location,
                'industry' => $industry,
                'minimum_degree' => $degree,
                'recruiter_id' => $recruiter->id,
                'verified' => $verified,
                'status' => $status,
                'posted_at' => $status === 'active' ? $postedAt : null,
                'created_at' => $postedAt,
                'updated_at' => $postedAt,
            ]);
        }

        $this->command->info('Created 30 job listings.');
    }

    /**
     * Generate a job description.
     * 
     * @param string $title
     * @param string $company
     * @return string
     */
    private function generateDescription(string $title, string $company): string
    {
        return "We are looking for a talented {$title} to join our team at {$company}. 

Key Responsibilities:
- Develop and maintain high-quality software solutions
- Collaborate with cross-functional teams
- Participate in code reviews and technical discussions
- Contribute to architectural decisions
- Stay updated with latest technologies and best practices

Requirements:
- Strong problem-solving skills
- Excellent communication abilities
- Team player with positive attitude
- Passionate about technology

Join us and be part of an innovative team that values creativity and excellence!";
    }
}

