<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobListing;
use App\Models\JobApply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        // Check if user has the correct role (middleware should handle this, but double-check for safety)
        if (!$user || !in_array($user->role, ['recruiter', 'admin'])) {
            // If user is regular user, redirect to home
            if ($user && $user->role === 'user') {
                return redirect()->route('home')
                    ->with('status', 'You do not have permission to access the admin panel.')
                    ->with('toast_type', 'warning');
            }
            // If not authenticated, redirect to admin login
            return redirect()->route('admin.login')
                ->with('error', 'Please login to access the admin panel.');
        }

        $isRecruiter = $user->role === 'recruiter';

        if ($isRecruiter) {
            // Recruiter-specific statistics (only data related to their own job listings)
            $recruiterId = $user->id;

            $totalJobListings = JobListing::where('recruiter_id', $recruiterId)->count();
            $activeJobListings = JobListing::where('recruiter_id', $recruiterId)
                ->where('status', 'active')
                ->count();

            // All applications for this recruiter's listings
            $jobApplicationsQuery = JobApply::whereHas('jobListing', function ($query) use ($recruiterId) {
                $query->where('recruiter_id', $recruiterId);
            });

            $totalJobApplications = $jobApplicationsQuery->count();

            // Unique candidates (users) who applied to this recruiter's jobs
            $applicantUserIds = (clone $jobApplicationsQuery)
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->unique();

            $totalUsers = $applicantUserIds->count();

            // For "today" we count new applications today
            $newUsersToday = (clone $jobApplicationsQuery)
                ->whereDate('created_at', today())
                ->count();

            // Recent candidates (by application date)
            $recentUsers = User::whereIn('id', $applicantUserIds)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // For recruiter dashboard, verified / unverified stats are based on applicants only
            $verifiedUsers = User::whereIn('id', $applicantUserIds)
                ->whereNotNull('email_verified_at')
                ->count();
            $unverifiedUsers = $totalUsers - $verifiedUsers;

            // Role distribution among applicants
            $adminCount = User::whereIn('id', $applicantUserIds)->where('role', 'admin')->count();
            $userCount = User::whereIn('id', $applicantUserIds)->where('role', 'user')->count();
            $recruiterCount = User::whereIn('id', $applicantUserIds)->where('role', 'recruiter')->count();

            // Growth data limited to this recruiter's applicants and listings
            $userGrowthData = $this->getUserGrowthData(6, $applicantUserIds);
            $userGrowthCategories = $userGrowthData['categories'];
            $userGrowthSeries = $userGrowthData['series'];

            $jobListingsGrowthData = $this->getJobListingsGrowthData(6, $recruiterId);
            $jobListingsGrowthCategories = $jobListingsGrowthData['categories'];
            $jobListingsGrowthSeries = $jobListingsGrowthData['series'];
        } else {
            // Admin dashboard (global statistics)
        $totalUsers = User::count();
        $totalJobListings = JobListing::count();
        $activeJobListings = JobListing::where('status', 'active')->count();
            $totalJobApplications = JobApply::count();
        $totalRecruiters = User::where('role', 'recruiter')->count();
        
        // Get recent users (last 5)
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Additional statistics
        $newUsersToday = User::whereDate('created_at', today())->count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = $totalUsers - $verifiedUsers;
        
        // User statistics by role
        $adminCount = User::where('role', 'admin')->count();
        $userCount = User::where('role', 'user')->count();
        $recruiterCount = User::where('role', 'recruiter')->count();
        
        // User growth data for last 6 months
        $userGrowthData = $this->getUserGrowthData(6);
        $userGrowthCategories = $userGrowthData['categories'];
        $userGrowthSeries = $userGrowthData['series'];
        
        // Job listings growth data for last 6 months
        $jobListingsGrowthData = $this->getJobListingsGrowthData(6);
        $jobListingsGrowthCategories = $jobListingsGrowthData['categories'];
        $jobListingsGrowthSeries = $jobListingsGrowthData['series'];
        }
        
        // For recruiter we show only recruiter-related totalRecruiters,
        // for admin this was already calculated above
        $totalRecruiters = $totalRecruiters ?? User::where('role', 'recruiter')->count();
        
        return view('admin.dashboard', [
            'title' => $user->role === 'admin' ? 'Admin Dashboard' : 'Recruiter Dashboard',
            'isRecruiterDashboard' => $isRecruiter,
            'totalUsers' => $totalUsers,
            'totalJobListings' => $totalJobListings,
            'activeJobListings' => $activeJobListings,
            'totalJobApplications' => $totalJobApplications,
            'totalRecruiters' => $totalRecruiters,
            'recentUsers' => $recentUsers,
            'newUsersToday' => $newUsersToday,
            'verifiedUsers' => $verifiedUsers,
            'unverifiedUsers' => $unverifiedUsers,
            'adminCount' => $adminCount,
            'userCount' => $userCount,
            'recruiterCount' => $recruiterCount,
            'userGrowthCategories' => $userGrowthCategories,
            'userGrowthSeries' => $userGrowthSeries,
            'jobListingsGrowthCategories' => $jobListingsGrowthCategories,
            'jobListingsGrowthSeries' => $jobListingsGrowthSeries,
        ]);
    }
    
    /**
     * Get user growth data for the specified number of months
     */
    private function getUserGrowthData($months = 6, $userIds = null)
    {
        $categories = [];
        $series = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $categories[] = $date->format('M Y');
            
            // Count users created in this month (optionally limited to specific user IDs)
            $query = User::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            if ($userIds && count($userIds) > 0) {
                $query->whereIn('id', $userIds);
            }
            $count = $query->count();
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
    private function getJobListingsGrowthData($months = 6, $recruiterId = null)
    {
        $categories = [];
        $series = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $categories[] = $date->format('M Y');
            
            // Count job listings created in this month (optionally limited to one recruiter)
            $query = JobListing::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            if ($recruiterId) {
                $query->where('recruiter_id', $recruiterId);
            }
            $count = $query->count();
            $series[] = $count;
        }
        
        return [
            'categories' => $categories,
            'series' => $series
        ];
    }
}

