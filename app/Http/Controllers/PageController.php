<?php

namespace App\Http\Controllers;

use App\Models\JobApply;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    public function landing()
    {
        return view('pages.landing-pages');
    }

    public function home()
    {
        // Check if user is authenticated and has the correct role
        if (!Auth::check() || Auth::user()->role !== 'user') {
            // If user is recruiter or admin, redirect to admin dashboard
            if (Auth::check() && in_array(Auth::user()->role, ['recruiter', 'admin'])) {
                return redirect()->route('admin.dashboard')
                    ->with('status', 'You do not have permission to access this page.')
                    ->with('toast_type', 'warning');
            }
            // If not authenticated, redirect to login
            return redirect()->route('login')
                ->with('status', 'Please login to access this page.')
                ->with('toast_type', 'info');
        }

        // Get latest 6 active job listings for homepage
        $jobListings = JobListing::with('recruiter')
            ->where('status', 'active')
            ->orderBy('posted_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        // Format jobs for view
        $jobs = [];
        foreach ($jobListings as $listing) {
            // Build tags array
            $tags = [];
            if ($listing->work_preference) {
                $tags[] = strtoupper($listing->work_preference);
            }
            if ($listing->contract_type) {
                $tags[] = $listing->contract_type;
            }
            if ($listing->experience_level) {
                $tags[] = $listing->experience_level;
            }
            
            // Format salary
            $salary = $listing->salary_display ?? 'Not Disclose';
            if ($listing->salary_min && $listing->salary_max) {
                $salary = 'IDR ' . number_format($listing->salary_min, 0, ',', ',') . ' - IDR ' . number_format($listing->salary_max, 0, ',', ',');
            } elseif ($listing->salary_min) {
                $salary = 'IDR ' . number_format($listing->salary_min, 0, ',', ',') . '+';
            }
            
            // Format posted date
            $posted = 'Just now';
            if ($listing->posted_at) {
                $posted = $listing->posted_at->diffForHumans();
            } elseif ($listing->created_at) {
                $posted = $listing->created_at->diffForHumans();
            }
            
            // Recruiter info
            $recruiterName = 'Admin';
            $recruiterAvatar = 'AD';
            if ($listing->recruiter) {
                $recruiterName = $listing->recruiter->first_name . ' ' . $listing->recruiter->last_name;
                if (strlen($recruiterName) > 20) {
                    // Shorten if too long
                    $recruiterName = substr($recruiterName, 0, 20) . '...';
                }
                $recruiterAvatar = strtoupper(substr($listing->recruiter->first_name, 0, 1) . substr($listing->recruiter->last_name, 0, 1));
            }
            
            $jobs[] = [
                'id' => $listing->id,
                'logo' => $listing->company_logo ? route('company.logo', $listing->company_logo) : '/assets/hero-sec.png',
                'title' => $listing->title,
                'company' => $listing->company,
                'verified' => $listing->verified ?? false,
                'salary' => $salary,
                'tags' => $tags,
                'location' => $listing->location,
                'posted' => $posted,
                'recruiter' => [
                    'name' => $recruiterName,
                    'avatar' => $recruiterAvatar
                ],
            ];
        }
        
        return view('pages.home', [
            'jobs' => $jobs
        ]);
    }

    public function about()
    {
        return view('pages.about');
    }

    public function services()
    {
        return view('pages.service');
    }

    public function whyUs()
    {
        return view('pages.why_us');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function jobListing(Request $request)
    {
        // Get active job listings with recruiter info
        $query = JobListing::with('recruiter')
            ->where('status', 'active')
            ->orderBy('posted_at', 'desc')
            ->orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->filled('work_preference')) {
            $query->where('work_preference', $request->work_preference);
        }

        if ($request->filled('experience_level')) {
            $query->where('experience_level', $request->experience_level);
        }

        if ($request->filled('industry')) {
            $query->where('industry', $request->industry);
        }

        if ($request->filled('salary_range')) {
            $salaryRange = $request->salary_range;
            if ($salaryRange !== '20+') {
                [$min, $max] = explode('-', $salaryRange);
                $query->where(function($q) use ($min, $max) {
                    $q->whereBetween('salary_min', [$min * 1000000, $max * 1000000])
                      ->orWhereBetween('salary_max', [$min * 1000000, $max * 1000000]);
                });
            } else {
                $query->where('salary_max', '>=', 20000000);
            }
        }

        $jobListings = $query->get();

        return view('jobs.job_listing', [
            'jobListings' => $jobListings,
            'filters' => $request->only(['search', 'location', 'work_preference', 'experience_level', 'industry', 'salary_range'])
        ]);
    }

    public function jobDetail($id)
    {
        $jobListing = JobListing::with('recruiter')
            ->where('status', 'active')
            ->findOrFail($id);
        
        // Get other jobs from the same company (excluding current job)
        $otherJobs = JobListing::with('recruiter')
            ->where('status', 'active')
            ->where('company', $jobListing->company)
            ->where('id', '!=', $id)
            ->orderBy('posted_at', 'desc')
            ->limit(5)
            ->get();
        
        // Format salary
        $salary = $jobListing->salary_display ?? 'Not Disclose';
        if ($jobListing->salary_min && $jobListing->salary_max) {
            $salary = 'IDR ' . number_format($jobListing->salary_min, 0, ',', ',') . ' - IDR ' . number_format($jobListing->salary_max, 0, ',', ',');
        } elseif ($jobListing->salary_min) {
            $salary = 'IDR ' . number_format($jobListing->salary_min, 0, ',', ',') . '+';
        }
        
        // Format posted date
        $posted = 'Just now';
        if ($jobListing->posted_at) {
            $posted = $jobListing->posted_at->diffForHumans();
        } elseif ($jobListing->created_at) {
            $posted = $jobListing->created_at->diffForHumans();
        }
        
        // Build tags array
        $tags = [];
        if ($jobListing->work_preference) {
            $tags[] = ['text' => strtoupper($jobListing->work_preference), 'icon' => 'fa-building'];
        }
        if ($jobListing->contract_type) {
            $tags[] = ['text' => $jobListing->contract_type, 'icon' => 'fa-file-contract'];
        }
        if ($jobListing->experience_level) {
            $tags[] = ['text' => $jobListing->experience_level, 'icon' => 'fa-rocket'];
        }
        if ($jobListing->minimum_degree) {
            $tags[] = ['text' => $jobListing->minimum_degree, 'icon' => 'fa-graduation-cap'];
        }
        
        // Recruiter info
        $recruiterName = 'Admin';
        $recruiterRole = 'Recruitment';
        $recruiterAvatar = 'AD';
        if ($jobListing->recruiter) {
            $recruiterName = $jobListing->recruiter->first_name . ' ' . $jobListing->recruiter->last_name;
            $recruiterAvatar = strtoupper(substr($jobListing->recruiter->first_name, 0, 1) . substr($jobListing->recruiter->last_name, 0, 1));
        }
        
        // Parse description - split into responsibilities and requirements if structured
        $description = $jobListing->description ?? '';
        $responsibilities = [];
        $requirements = [];
        $keySkills = [];
        $benefits = [];
        
        if ($description) {
            // Try to detect structured format
            $lines = explode("\n", $description);
            $currentSection = null;
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                // Detect section headers
                if (preg_match('/^(Responsibilities|Requirements|Key Skills|Benefits|Skills):/i', $line)) {
                    if (stripos($line, 'responsibilities') !== false) {
                        $currentSection = 'responsibilities';
                    } elseif (stripos($line, 'requirements') !== false) {
                        $currentSection = 'requirements';
                    } elseif (stripos($line, 'skills') !== false) {
                        $currentSection = 'skills';
                    } elseif (stripos($line, 'benefits') !== false) {
                        $currentSection = 'benefits';
                    }
                    continue;
                }
                
                // Remove bullet points and numbering
                $cleanLine = preg_replace('/^[-*•]\s*/', '', $line);
                $cleanLine = preg_replace('/^\d+[\.)]\s*/', '', $cleanLine);
                $cleanLine = trim($cleanLine);
                
                if (empty($cleanLine)) continue;
                
                // Assign to appropriate section
                if ($currentSection === 'responsibilities') {
                    $responsibilities[] = $cleanLine;
                } elseif ($currentSection === 'requirements') {
                    $requirements[] = $cleanLine;
                } elseif ($currentSection === 'skills') {
                    // Handle comma-separated skills
                    $skills = array_map('trim', explode(',', $cleanLine));
                    $keySkills = array_merge($keySkills, array_filter($skills));
                } elseif ($currentSection === 'benefits') {
                    // Handle comma-separated benefits
                    $benefitList = array_map('trim', explode(',', $cleanLine));
                    $benefits = array_merge($benefits, array_filter($benefitList));
                } else {
                    // No section header, treat as general description/responsibilities
                    if (empty($responsibilities)) {
                        $responsibilities[] = $cleanLine;
                    }
                }
            }
        }
        
        // Fallback: use description as responsibilities if no structured data found
        if (empty($responsibilities) && !empty($description)) {
            // Split by newlines if multiline, otherwise use whole description
            $descLines = array_filter(array_map('trim', explode("\n", $description)));
            if (count($descLines) > 1) {
                $responsibilities = array_values($descLines);
            } else {
                $responsibilities = [$description];
            }
        }
        
        // Fallback defaults
        if (empty($requirements)) {
            $requirements = ['Please refer to job description for full requirements.'];
        }
        if (empty($keySkills)) {
            $keySkills = ['See job description'];
        }
        if (empty($benefits)) {
            $benefits = ['Competitive salary', 'Professional development'];
        }
        
        // Prepare job data for view
        $job = [
            'id' => $jobListing->id,
            'logo' => $jobListing->company_logo ? route('company.logo', $jobListing->company_logo) : '/assets/hero-sec.png',
            'title' => $jobListing->title,
            'company' => $jobListing->company,
            'verified' => $jobListing->verified ?? false,
            'salary' => $salary,
            'tags' => $tags,
            'location' => $jobListing->location,
            'posted' => $posted,
            'recruiter' => [
                'name' => $recruiterName,
                'role' => $recruiterRole,
                'avatar' => $recruiterAvatar
            ],
            'responsibilities' => $responsibilities,
            'requirements' => $requirements,
            'key_skills' => $keySkills,
            'benefits' => $benefits,
            'company_info' => [
                'industry' => $jobListing->industry ?? 'Not specified',
                'employees' => 'Not specified',
                'description' => $description ?: 'No company description available.',
                'address' => $jobListing->location ?? 'Address not specified',
            ],
        ];
        
        return view('jobs.job_detail', [
            'job' => $job,
            'otherJobs' => $otherJobs,
            'jobListing' => $jobListing
        ]);
    }

    public function jobApplication($id = null)
    {
        // If no job ID provided, redirect to job listing page
        if (!$id) {
            return redirect()->route('jobs')
                ->with('status', 'Please select a job to apply for.')
                ->with('toast_type', 'warning');
        }
        
        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')
                ->with('status', 'Please login to apply for this job.')
                ->with('toast_type', 'info');
        }
        
        $jobListing = JobListing::with('recruiter')
            ->where('status', 'active')
            ->findOrFail($id);
        
        // Check if user has already applied for this job
        $existingApplication = JobApply::where('user_id', $user->id)
            ->where('job_listing_id', $id)
            ->first();
        
        // Format job data for view (similar to jobDetail but simplified)
        $salary = $jobListing->salary_display ?? 'Not Disclose';
        if ($jobListing->salary_min && $jobListing->salary_max) {
            $salary = 'IDR ' . number_format($jobListing->salary_min, 0, ',', ',') . ' - IDR ' . number_format($jobListing->salary_max, 0, ',', ',');
        } elseif ($jobListing->salary_min) {
            $salary = 'IDR ' . number_format($jobListing->salary_min, 0, ',', ',') . '+';
        }
        
        $job = [
            'id' => $jobListing->id,
            'title' => $jobListing->title,
            'company' => $jobListing->company,
            'salary' => $salary,
            'location' => $jobListing->location,
        ];
        
        return view('jobs.form-jobs', [
            'job' => $job,
            'hasApplied' => $existingApplication !== null,
            'existingApplication' => $existingApplication
        ]);
    }

    public function profile()
    {
        // For frontend testing - no auth required
        return view('pages.profile');
    }

    public function history()
    {
        // Get authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('status', 'Please login to view your job application history.')
                ->with('toast_type', 'info');
        }
        
        // Get all job applications for this user
        $jobApplies = JobApply::with(['jobListing'])
            ->where('user_id', $user->id)
            ->orderBy('applied_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Remove duplicate applications - keep the newest one for each job listing
        $uniqueApplications = [];
        $duplicatesToDelete = [];
        
        foreach ($jobApplies as $apply) {
            $jobListingId = $apply->job_listing_id;
            
            // If we haven't seen this job listing before, keep this application
            if (!isset($uniqueApplications[$jobListingId])) {
                $uniqueApplications[$jobListingId] = $apply;
            } else {
                // Compare dates to determine which is newer
                $existingApply = $uniqueApplications[$jobListingId];
                
                // Get dates for comparison (use applied_at if available, otherwise created_at)
                $existingDate = $existingApply->applied_at ?? $existingApply->created_at;
                $currentDate = $apply->applied_at ?? $apply->created_at;
                
                // Convert to Carbon instances for proper comparison
                $existingCarbon = $existingDate ? \Carbon\Carbon::parse($existingDate) : null;
                $currentCarbon = $currentDate ? \Carbon\Carbon::parse($currentDate) : null;
                
                // Determine which is newer
                $isCurrentNewer = false;
                if ($currentCarbon && $existingCarbon) {
                    $isCurrentNewer = $currentCarbon->greaterThanOrEqualTo($existingCarbon);
                } elseif ($currentCarbon) {
                    // Current has date, existing doesn't - keep current
                    $isCurrentNewer = true;
                }
                
                // If current application is newer or equal, replace the existing one
                if ($isCurrentNewer) {
                    // Current is newer or equal, delete the old one and keep current
                    $duplicatesToDelete[] = $existingApply;
                    $uniqueApplications[$jobListingId] = $apply;
                } else {
                    // Current application is older, mark it for deletion
                    $duplicatesToDelete[] = $apply;
                }
            }
        }
        
        // Delete duplicate applications (soft delete)
        foreach ($duplicatesToDelete as $duplicate) {
            $duplicate->delete();
            Log::info('Duplicate job application removed', [
                'application_id' => $duplicate->id,
                'user_id' => $user->id,
                'job_listing_id' => $duplicate->job_listing_id,
                'deleted_at' => now(),
            ]);
        }
        
        // Log if duplicates were found
        if (count($duplicatesToDelete) > 0) {
            Log::info('Duplicate job applications cleaned up', [
                'user_id' => $user->id,
                'duplicates_removed' => count($duplicatesToDelete),
                'remaining_applications' => count($uniqueApplications),
            ]);
        }
        
        // Convert unique applications array to collection for easier iteration
        $uniqueApplications = collect($uniqueApplications)->sortByDesc(function($apply) {
            return $apply->applied_at ?? $apply->created_at;
        });
        
        // Format applications data for view
        $applications = [];
        foreach ($uniqueApplications as $apply) {
            // Get company logo from job listing or use default
            $companyLogo = '/assets/hero-sec.png';
            $companyLogoPlaceholder = 'AE';
            
            if ($apply->jobListing) {
                if ($apply->jobListing->company_logo) {
                    $companyLogo = route('company.logo', $apply->jobListing->company_logo);
                }
                // Generate placeholder from company name
                $companyName = $apply->jobListing->company;
                if (strlen($companyName) >= 2) {
                    $companyLogoPlaceholder = strtoupper(substr($companyName, 0, 2));
                } else {
                    $companyLogoPlaceholder = strtoupper(substr($companyName, 0, 1) . 'X');
                }
            }
            
            // Map status to frontend status format
            $statusMap = [
                'pending' => ['status' => 'pending', 'text' => 'Pending', 'icon' => 'fa-clock'],
                'shortlisted' => ['status' => 'review', 'text' => 'Under Review', 'icon' => 'fa-eye'],
                'interview' => ['status' => 'review', 'text' => 'Interview', 'icon' => 'fa-calendar-check'],
                'hired' => ['status' => 'accepted', 'text' => 'Accepted', 'icon' => 'fa-check-circle'],
                'rejected' => ['status' => 'rejected', 'text' => 'Rejected', 'icon' => 'fa-times-circle'],
            ];
            
            $statusConfig = $statusMap[$apply->status] ?? ['status' => 'pending', 'text' => ucfirst($apply->status), 'icon' => 'fa-clock'];
            
            // Format applied date
            $appliedDate = 'Just now';
            if ($apply->applied_at) {
                $appliedDate = \Carbon\Carbon::parse($apply->applied_at)->setTimezone('Asia/Jakarta')->format('F d, Y');
            } elseif ($apply->created_at) {
                $appliedDate = $apply->created_at->setTimezone('Asia/Jakarta')->format('F d, Y');
            }
            
            // Status message based on status
            $statusMessages = [
                'pending' => 'Your application is currently being reviewed by the hiring team. We will notify you once there\'s an update.',
                'shortlisted' => 'Your application has been reviewed and is currently in the selection process. We will contact you soon.',
                'interview' => 'Congratulations! You have been selected for an interview. Please check your email for details.',
                'hired' => 'Congratulations! Your application has been accepted. The company will contact you for the next steps.',
                'rejected' => 'Thank you for your interest. Unfortunately, your application was not selected for this position.',
            ];
            $statusMessage = $statusMessages[$apply->status] ?? 'Your application status is being updated.';
            
            // Job detail link
            $jobLink = '#';
            if ($apply->jobListing) {
                $jobLink = route('job.detail', ['id' => $apply->jobListing->id]);
            }
            
            $applications[] = [
                'id' => $apply->id,
                'jobTitle' => $apply->jobListing ? $apply->jobListing->title : 'N/A',
                'company' => $apply->jobListing ? $apply->jobListing->company : 'N/A',
                'companyLogo' => $companyLogo,
                'companyLogoPlaceholder' => $companyLogoPlaceholder,
                'status' => $statusConfig['status'],
                'statusText' => $statusConfig['text'],
                'statusIcon' => $statusConfig['icon'],
                'statusMessage' => $statusMessage,
                'appliedDate' => $appliedDate,
                'location' => $apply->jobListing ? $apply->jobListing->location : 'N/A',
                'jobLink' => $jobLink,
                'jobListingId' => $apply->jobListing ? $apply->jobListing->id : null,
                'notes' => $apply->notes, // Recruiter notes
            ];
        }
        
        return view('jobs.history', [
            'applications' => $applications
        ]);
    }
}
