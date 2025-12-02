<?php

namespace Database\Seeders;

use App\Models\JobApply;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Job Apply Seeder
 * 
 * Seeds the database with sample job applications for testing.
 * Creates applications from regular users to various job listings.
 */
class JobApplySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void
     */
    public function run(): void
    {
        // Get regular users (users with role 'user')
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $this->command->warn('No regular users found. Please run UserSeeder first.');
            return;
        }

        // Get active job listings
        $jobListings = JobListing::where('status', 'active')->get();

        if ($jobListings->isEmpty()) {
            $this->command->warn('No active job listings found. Please run JobListingSeeder first.');
            return;
        }

        $firstNames = [
            'Ahmad', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fajar', 'Gita', 'Hadi',
            'Indra', 'Joko', 'Kartika', 'Lina', 'Mario', 'Nina', 'Omar',
            'Putri', 'Rizki', 'Sari', 'Tono', 'Umi', 'Vina', 'Wawan', 'Yuni',
        ];

        $lastNames = [
            'Santoso', 'Wijaya', 'Kurniawan', 'Prasetyo', 'Sari', 'Hidayat',
            'Nugroho', 'Rahayu', 'Saputra', 'Lestari', 'Setiawan', 'Dewi',
        ];

        $addresses = [
            'Jl. Sudirman No. 123, Jakarta Pusat',
            'Jl. Gatot Subroto No. 45, Jakarta Selatan',
            'Jl. Thamrin No. 78, Jakarta Pusat',
            'Jl. Kebon Jeruk No. 12, Jakarta Barat',
            'Jl. Cikini Raya No. 34, Jakarta Pusat',
            'Jl. Senopati No. 56, Jakarta Selatan',
            'Jl. Kemang Raya No. 89, Jakarta Selatan',
            'Jl. HR Rasuna Said No. 23, Jakarta Selatan',
        ];

        $statuses = ['pending', 'shortlisted', 'interview', 'hired', 'rejected'];
        $statusWeights = [50, 20, 15, 5, 10]; // Weighted random distribution

        // Create 50 job applications
        $applicationCount = 0;
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $jobListing = $jobListings->random();

            // Check if user already applied to this job
            $existingApplication = JobApply::where('user_id', $user->id)
                ->where('job_listing_id', $jobListing->id)
                ->exists();

            if ($existingApplication) {
                continue; // Skip if already applied
            }

            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName . '@example.com');
            $phone = '628' . rand(1000000000, 9999999999);
            $address = $addresses[array_rand($addresses)];

            // Generate salary
            $currentSalary = rand(3000000, 12000000);
            $expectedSalary = $currentSalary + rand(2000000, 5000000);
            $currentSalaryDisplay = "IDR " . number_format($currentSalary, 0, ',', '.');
            $expectedSalaryDisplay = "IDR " . number_format($expectedSalary, 0, ',', '.');

            // Availability (random date in next 30 days)
            $availability = now()->addDays(rand(1, 30))->format('Y-m-d');
            $relocation = rand(0, 1) ? 'Yes' : 'No';

            // Social media links
            $linkedin = 'https://linkedin.com/in/' . strtolower($firstName . '-' . $lastName);
            $github = 'https://github.com/' . strtolower($firstName . $lastName);
            $socialMedia = 'https://instagram.com/' . strtolower($firstName . $lastName);

            // Generate status with weighted distribution
            $status = $this->weightedRandom($statuses, $statusWeights);

            // Applied at date (random date in last 60 days)
            $appliedAt = now()->subDays(rand(0, 60));

            JobApply::create([
                'user_id' => $user->id,
                'job_listing_id' => $jobListing->id,
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'current_salary' => $currentSalaryDisplay,
                'expected_salary' => $expectedSalaryDisplay,
                'availability' => $availability,
                'relocation' => $relocation,
                'linkedin' => $linkedin,
                'github' => $github,
                'social_media' => $socialMedia,
                'cv' => null,
                'portfolio_file' => null,
                'cover_letter' => $this->generateCoverLetter($fullName, $jobListing->title, $jobListing->company),
                'reason_applying' => $this->generateReasonApplying($jobListing->company),
                'relevant_experience' => $this->generateRelevantExperience($jobListing->title),
                'status' => $status,
                'notes' => $status !== 'pending' ? $this->generateNotes($status) : null,
                'applied_at' => $appliedAt,
                'created_at' => $appliedAt,
                'updated_at' => $appliedAt,
            ]);

            $applicationCount++;
        }

        $this->command->info("Created {$applicationCount} job applications.");
    }

    /**
     * Generate weighted random selection.
     * 
     * @param array $items
     * @param array $weights
     * @return mixed
     */
    private function weightedRandom(array $items, array $weights)
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($items as $index => $item) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $item;
            }
        }

        return $items[0];
    }

    /**
     * Generate a cover letter.
     * 
     * @param string $name
     * @param string $jobTitle
     * @param string $company
     * @return string
     */
    private function generateCoverLetter(string $name, string $jobTitle, string $company): string
    {
        return "Dear Hiring Manager,

I am writing to express my interest in the {$jobTitle} position at {$company}. With my background and passion for this field, I am confident that I would be a valuable addition to your team.

I am excited about the opportunity to contribute to {$company} and look forward to discussing how my skills and experience align with your needs.

Best regards,
{$name}";
    }

    /**
     * Generate reason for applying.
     * 
     * @param string $company
     * @return string
     */
    private function generateReasonApplying(string $company): string
    {
        $reasons = [
            "I am interested in joining {$company} because of its innovative approach and strong company culture.",
            "{$company} has an excellent reputation in the industry, and I would love to be part of the team.",
            "I believe my skills and experience align perfectly with {$company}'s mission and values.",
            "The opportunity to work at {$company} represents a significant step forward in my career.",
            "I am drawn to {$company}'s commitment to excellence and innovation in the field.",
        ];

        return $reasons[array_rand($reasons)];
    }

    /**
     * Generate relevant experience.
     * 
     * @param string $jobTitle
     * @return string
     */
    private function generateRelevantExperience(string $jobTitle): string
    {
        return "I have extensive experience in this field, with a proven track record of success. My background includes working on various projects and collaborating with cross-functional teams. I am confident that my skills and expertise make me a strong candidate for this position.";
    }

    /**
     * Generate admin notes based on status.
     * 
     * @param string $status
     * @return string
     */
    private function generateNotes(string $status): string
    {
        $notes = [
            'shortlisted' => 'Candidate has been shortlisted for further review.',
            'interview' => 'Scheduled for interview. Strong candidate with relevant experience.',
            'hired' => 'Candidate has been selected and hired for this position.',
            'rejected' => 'Application reviewed but does not meet current requirements.',
        ];

        return $notes[$status] ?? 'Application reviewed.';
    }
}

