<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobListing;
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
        // Check if user has the correct role (middleware should handle this, but double-check for safety)
        $user = auth()->user();
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

        // Get statistics
        $totalUsers = User::count();
        $totalJobListings = JobListing::count();
        $activeJobListings = JobListing::where('status', 'active')->count();
        $totalJobApplications = 0; // Placeholder - replace with actual model when available
        $totalRecruiters = User::where('role', 'recruiter')->count();
        
        // Get recent users (last 5)
        $recentUsers = User::orderBy('created_at', 'desc')->limit(5)->get();
        
        // Get recent job applications (placeholder - replace when model available)
        $recentJobApplications = []; // Placeholder
        
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
        
        return view('admin.dashboard', [
            'title' => 'Dashboard',
            'totalUsers' => $totalUsers,
            'totalJobListings' => $totalJobListings,
            'activeJobListings' => $activeJobListings,
            'totalJobApplications' => $totalJobApplications,
            'totalRecruiters' => $totalRecruiters,
            'recentUsers' => $recentUsers,
            'recentJobApplications' => $recentJobApplications,
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
    private function getUserGrowthData($months = 6)
    {
        $categories = [];
        $series = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $categories[] = $date->format('M Y');
            
            // Count users created in this month
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
            
            // Count job listings created in this month
            $count = JobListing::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $series[] = $count;
        }
        
        return [
            'categories' => $categories,
            'series' => $series
        ];
    }
}

