<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobListing;
use App\Models\JobApply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reports overview.
     */
    public function index()
    {
        // Overall statistics
        $totalUsers = User::count();
        $totalRecruiters = User::where('role', 'recruiter')->count();
        $totalEmployees = User::where('role', 'user')->count();
        $totalJobListings = JobListing::count();
        $activeJobListings = JobListing::where('status', 'active')->count();
        $totalJobApplications = JobApply::count();
        
        // Today's statistics
        $newUsersToday = User::whereDate('created_at', today())->count();
        $newJobListingsToday = JobListing::whereDate('created_at', today())->count();
        $newApplicationsToday = JobApply::whereDate('created_at', today())->count();
        
        // This month's statistics
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $newJobListingsThisMonth = JobListing::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $newApplicationsThisMonth = JobApply::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Application status distribution
        $applicationStatusStats = JobApply::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Job status distribution
        $jobStatusStats = JobListing::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        return view('admin.reports.index', [
            'title' => 'Reports Overview',
            'totalUsers' => $totalUsers,
            'totalRecruiters' => $totalRecruiters,
            'totalEmployees' => $totalEmployees,
            'totalJobListings' => $totalJobListings,
            'activeJobListings' => $activeJobListings,
            'totalJobApplications' => $totalJobApplications,
            'newUsersToday' => $newUsersToday,
            'newJobListingsToday' => $newJobListingsToday,
            'newApplicationsToday' => $newApplicationsToday,
            'newUsersThisMonth' => $newUsersThisMonth,
            'newJobListingsThisMonth' => $newJobListingsThisMonth,
            'newApplicationsThisMonth' => $newApplicationsThisMonth,
            'applicationStatusStats' => $applicationStatusStats,
            'jobStatusStats' => $jobStatusStats,
        ]);
    }

    /**
     * Display user reports.
     */
    public function users(Request $request)
    {
        $period = $request->get('period', '6'); // Default 6 months
        
        // User growth data
        $userGrowthData = $this->getUserGrowthData((int)$period);
        
        // User by role
        $usersByRole = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');
        
        // Verified vs unverified
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = User::whereNull('email_verified_at')->count();
        
        // Recent registrations
        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();
        
        // Users by month (last N months)
        $usersByMonth = $this->getUsersByMonth((int)$period);
        
        return view('admin.reports.users', [
            'title' => 'User Reports',
            'userGrowthCategories' => $userGrowthData['categories'],
            'userGrowthSeries' => $userGrowthData['series'],
            'usersByRole' => $usersByRole,
            'verifiedUsers' => $verifiedUsers,
            'unverifiedUsers' => $unverifiedUsers,
            'recentUsers' => $recentUsers,
            'usersByMonth' => $usersByMonth,
            'period' => $period,
        ]);
    }

    /**
     * Display job reports.
     */
    public function jobs(Request $request)
    {
        $period = $request->get('period', '6'); // Default 6 months
        
        // Job listings growth
        $jobListingsGrowthData = $this->getJobListingsGrowthData((int)$period);
        
        // Job applications growth
        $jobApplicationsGrowthData = $this->getJobApplicationsGrowthData((int)$period);
        
        // Job status distribution
        $jobStatusStats = JobListing::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Application status distribution
        $applicationStatusStats = JobApply::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Top jobs by applications
        $topJobs = JobListing::withCount('jobApplies')
            ->orderBy('job_applies_count', 'desc')
            ->limit(10)
            ->get();
        
        // Jobs by location
        $jobsByLocation = JobListing::select('location', DB::raw('count(*) as count'))
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // Jobs by industry
        $jobsByIndustry = JobListing::whereNotNull('industry')
            ->select('industry', DB::raw('count(*) as count'))
            ->groupBy('industry')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.reports.jobs', [
            'title' => 'Job Reports',
            'jobListingsGrowthCategories' => $jobListingsGrowthData['categories'],
            'jobListingsGrowthSeries' => $jobListingsGrowthData['series'],
            'jobApplicationsGrowthCategories' => $jobApplicationsGrowthData['categories'],
            'jobApplicationsGrowthSeries' => $jobApplicationsGrowthData['series'],
            'jobStatusStats' => $jobStatusStats,
            'applicationStatusStats' => $applicationStatusStats,
            'topJobs' => $topJobs,
            'jobsByLocation' => $jobsByLocation,
            'jobsByIndustry' => $jobsByIndustry,
            'period' => $period,
        ]);
    }
    
    /**
     * Get user growth data for the specified number of months
     */
    private function getUserGrowthData($months = 6)
    {
        $categories = [];
        $series = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $categories[] = $date->format('M Y');
            $count = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $series[] = $count;
        }
        
        return [
            'categories' => $categories,
            'series' => $series
        ];
    }
    
    /**
     * Get job listings growth data for the specified number of months
     */
    private function getJobListingsGrowthData($months = 6)
    {
        $categories = [];
        $series = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $categories[] = $date->format('M Y');
            $count = JobListing::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $series[] = $count;
        }
        
        return [
            'categories' => $categories,
            'series' => $series
        ];
    }
    
    /**
     * Get job applications growth data for the specified number of months
     */
    private function getJobApplicationsGrowthData($months = 6)
    {
        $categories = [];
        $series = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $categories[] = $date->format('M Y');
            $count = JobApply::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $series[] = $count;
        }
        
        return [
            'categories' => $categories,
            'series' => $series
        ];
    }
    
    /**
     * Get users by month breakdown
     */
    private function getUsersByMonth($months = 6)
    {
        $data = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'admin' => User::where('role', 'admin')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count(),
                'recruiter' => User::where('role', 'recruiter')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count(),
                'user' => User::where('role', 'user')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                    ->count(),
            ];
        }
        
        return $data;
    }

    /**
     * Export reports overview to CSV.
     */
    public function exportOverview()
    {
        try {
            $filename = 'reports_overview_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function() {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                fputcsv($file, ['Report Type', 'Metric', 'Value', 'Date']);
                
                // Overall statistics
                $totalUsers = User::count();
                $totalRecruiters = User::where('role', 'recruiter')->count();
                $totalEmployees = User::where('role', 'user')->count();
                $totalJobListings = JobListing::count();
                $activeJobListings = JobListing::where('status', 'active')->count();
                $totalJobApplications = JobApply::count();
                
                // Today's statistics
                $newUsersToday = User::whereDate('created_at', today())->count();
                $newJobListingsToday = JobListing::whereDate('created_at', today())->count();
                $newApplicationsToday = JobApply::whereDate('created_at', today())->count();
                
                // This month's statistics
                $newUsersThisMonth = User::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                $newJobListingsThisMonth = JobListing::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                $newApplicationsThisMonth = JobApply::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
                
                // Application status distribution
                $applicationStatusStats = JobApply::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get();
                
                // Job status distribution
                $jobStatusStats = JobListing::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get();
                
                $now = now()->format('Y-m-d H:i:s');
                
                // Overall stats
                fputcsv($file, ['Overall', 'Total Users', $totalUsers, $now]);
                fputcsv($file, ['Overall', 'Total Recruiters', $totalRecruiters, $now]);
                fputcsv($file, ['Overall', 'Total Employees', $totalEmployees, $now]);
                fputcsv($file, ['Overall', 'Total Job Listings', $totalJobListings, $now]);
                fputcsv($file, ['Overall', 'Active Job Listings', $activeJobListings, $now]);
                fputcsv($file, ['Overall', 'Total Job Applications', $totalJobApplications, $now]);
                
                // Today's stats
                fputcsv($file, ['Today', 'New Users', $newUsersToday, today()->format('Y-m-d')]);
                fputcsv($file, ['Today', 'New Job Listings', $newJobListingsToday, today()->format('Y-m-d')]);
                fputcsv($file, ['Today', 'New Applications', $newApplicationsToday, today()->format('Y-m-d')]);
                
                // This month's stats
                fputcsv($file, ['This Month', 'New Users', $newUsersThisMonth, now()->format('Y-m')]);
                fputcsv($file, ['This Month', 'New Job Listings', $newJobListingsThisMonth, now()->format('Y-m')]);
                fputcsv($file, ['This Month', 'New Applications', $newApplicationsThisMonth, now()->format('Y-m')]);
                
                // Application status stats
                foreach ($applicationStatusStats as $stat) {
                    fputcsv($file, ['Application Status', ucfirst($stat->status), $stat->count, $now]);
                }
                
                // Job status stats
                foreach ($jobStatusStats as $stat) {
                    fputcsv($file, ['Job Status', ucfirst($stat->status), $stat->count, $now]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.index')
                ->with('error', 'Failed to export report: ' . $e->getMessage());
        }
    }

    /**
     * Export user reports to CSV.
     */
    public function exportUsers(Request $request)
    {
        try {
            $period = $request->get('period', '6');
            $filename = 'user_reports_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function() use ($period) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Section 1: User Statistics
                fputcsv($file, ['USER STATISTICS']);
                fputcsv($file, ['Metric', 'Value']);
                
                $usersByRole = User::select('role', DB::raw('count(*) as count'))
                    ->groupBy('role')
                    ->get();
                
                $verifiedUsers = User::whereNotNull('email_verified_at')->count();
                $unverifiedUsers = User::whereNull('email_verified_at')->count();
                
                foreach ($usersByRole as $role) {
                    fputcsv($file, ['Total ' . ucfirst($role->role) . 's', $role->count]);
                }
                fputcsv($file, ['Verified Users', $verifiedUsers]);
                fputcsv($file, ['Unverified Users', $unverifiedUsers]);
                fputcsv($file, ['Total Users', User::count()]);
                
                fputcsv($file, []); // Empty row
                
                // Section 2: Users by Month
                fputcsv($file, ['USERS BY MONTH (Last ' . $period . ' months)']);
                fputcsv($file, ['Month', 'Admin', 'Recruiter', 'User', 'Total']);
                
                $usersByMonth = $this->getUsersByMonth((int)$period);
                foreach ($usersByMonth as $monthData) {
                    $total = $monthData['admin'] + $monthData['recruiter'] + $monthData['user'];
                    fputcsv($file, [
                        $monthData['month'],
                        $monthData['admin'],
                        $monthData['recruiter'],
                        $monthData['user'],
                        $total
                    ]);
                }
                
                fputcsv($file, []); // Empty row
                
                // Section 3: Recent Users
                fputcsv($file, ['RECENT USERS (Last 10)']);
                fputcsv($file, ['ID', 'First Name', 'Last Name', 'Email', 'Role', 'Email Verified', 'Created At']);
                
                $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();
                foreach ($recentUsers as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->first_name,
                        $user->last_name,
                        $user->email,
                        ucfirst($user->role),
                        $user->email_verified_at ? 'Yes' : 'No',
                        $user->created_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.users')
                ->with('error', 'Failed to export report: ' . $e->getMessage());
        }
    }

    /**
     * Export job reports to CSV.
     */
    public function exportJobs(Request $request)
    {
        try {
            $period = $request->get('period', '6');
            $filename = 'job_reports_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function() use ($period) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Section 1: Job Statistics
                fputcsv($file, ['JOB STATISTICS']);
                fputcsv($file, ['Metric', 'Value']);
                
                $jobStatusStats = JobListing::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get();
                
                $applicationStatusStats = JobApply::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->get();
                
                fputcsv($file, ['Total Job Listings', JobListing::count()]);
                foreach ($jobStatusStats as $stat) {
                    fputcsv($file, ['Job Status: ' . ucfirst($stat->status), $stat->count]);
                }
                
                fputcsv($file, ['Total Job Applications', JobApply::count()]);
                foreach ($applicationStatusStats as $stat) {
                    fputcsv($file, ['Application Status: ' . ucfirst($stat->status), $stat->count]);
                }
                
                fputcsv($file, []); // Empty row
                
                // Section 2: Job Listings Growth (Last N months)
                fputcsv($file, ['JOB LISTINGS GROWTH (Last ' . $period . ' months)']);
                fputcsv($file, ['Month', 'Count']);
                
                $jobListingsGrowthData = $this->getJobListingsGrowthData((int)$period);
                for ($i = 0; $i < count($jobListingsGrowthData['categories']); $i++) {
                    fputcsv($file, [
                        $jobListingsGrowthData['categories'][$i],
                        $jobListingsGrowthData['series'][$i]
                    ]);
                }
                
                fputcsv($file, []); // Empty row
                
                // Section 3: Job Applications Growth (Last N months)
                fputcsv($file, ['JOB APPLICATIONS GROWTH (Last ' . $period . ' months)']);
                fputcsv($file, ['Month', 'Count']);
                
                $jobApplicationsGrowthData = $this->getJobApplicationsGrowthData((int)$period);
                for ($i = 0; $i < count($jobApplicationsGrowthData['categories']); $i++) {
                    fputcsv($file, [
                        $jobApplicationsGrowthData['categories'][$i],
                        $jobApplicationsGrowthData['series'][$i]
                    ]);
                }
                
                fputcsv($file, []); // Empty row
                
                // Section 4: Top Jobs by Applications
                fputcsv($file, ['TOP JOBS BY APPLICATIONS']);
                fputcsv($file, ['ID', 'Title', 'Company', 'Location', 'Status', 'Applications', 'Created At']);
                
                $topJobs = JobListing::withCount('jobApplies')
                    ->orderBy('job_applies_count', 'desc')
                    ->limit(10)
                    ->get();
                
                foreach ($topJobs as $job) {
                    fputcsv($file, [
                        $job->id,
                        $job->title,
                        $job->company_name ?? 'N/A',
                        $job->location ?? 'N/A',
                        ucfirst($job->status),
                        $job->job_applies_count,
                        $job->created_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fputcsv($file, []); // Empty row
                
                // Section 5: Jobs by Location
                fputcsv($file, ['JOBS BY LOCATION']);
                fputcsv($file, ['Location', 'Count']);
                
                $jobsByLocation = JobListing::select('location', DB::raw('count(*) as count'))
                    ->groupBy('location')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get();
                
                foreach ($jobsByLocation as $location) {
                    fputcsv($file, [
                        $location->location ?? 'N/A',
                        $location->count
                    ]);
                }
                
                fputcsv($file, []); // Empty row
                
                // Section 6: Jobs by Industry
                fputcsv($file, ['JOBS BY INDUSTRY']);
                fputcsv($file, ['Industry', 'Count']);
                
                $jobsByIndustry = JobListing::whereNotNull('industry')
                    ->select('industry', DB::raw('count(*) as count'))
                    ->groupBy('industry')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get();
                
                foreach ($jobsByIndustry as $industry) {
                    fputcsv($file, [
                        $industry->industry,
                        $industry->count
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.jobs')
                ->with('error', 'Failed to export report: ' . $e->getMessage());
        }
    }
}

