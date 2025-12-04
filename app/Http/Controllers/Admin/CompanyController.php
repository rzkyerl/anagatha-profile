<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(Request $request)
    {
        // Get companies with their recruiters
        $query = Company::with('user')
            ->withCount('jobListings')
            ->orderBy('name', 'asc');
        
        $companies = $query->get();
        
        // Get unique company names (group by name to handle multiple companies with same name)
        $uniqueCompanyNames = $companies->pluck('name')->unique();
        
        // Get recruiters for each company
        $companies = $companies->groupBy('name')->map(function ($companyGroup) {
            $firstCompany = $companyGroup->first();
            $companyName = $firstCompany->name;
            
            // Get recruiters for this company (all users with company name matching)
            $recruiters = User::where('role', 'recruiter')
                ->where(function ($query) use ($companyName) {
                    $query->whereHas('company', function ($q) use ($companyName) {
                        $q->where('name', $companyName);
                    })
                    ->orWhere('company_name', $companyName); // Fallback for old data
                })
                ->orderBy('first_name', 'asc')
                ->get();
            
            $firstCompany->recruiters = $recruiters;
            $firstCompany->recruiter_count = $recruiters->count();
            return $firstCompany;
        })->values();
        
        return view('admin.companies.index', [
            'title' => 'Company List',
            'companies' => $companies,
        ]);
    }

    /**
     * Display the specified company with all recruiters.
     */
    public function show(string $companyName)
    {
        $decodedCompanyName = urldecode($companyName);
        
        // Find company by name
        $company = Company::where('name', $decodedCompanyName)
            ->with('user')
            ->first();
        
        // If not found in companies table, try to get from users table (fallback for old data)
        if (!$company) {
            $recruiters = User::where('role', 'recruiter')
                ->where('company_name', $decodedCompanyName)
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($recruiters->isEmpty()) {
                return redirect()->route('admin.companies.index')
                    ->with('error', 'Company not found.');
            }
            
            // Create a virtual company object for display
            $firstRecruiter = $recruiters->first();
            $company = (object) [
                'id' => null,
                'name' => $decodedCompanyName,
                'logo' => $firstRecruiter->company_logo,
                'location' => null,
                'industry' => $firstRecruiter->industry,
                'industry_other' => $firstRecruiter->industry_other,
                'created_at' => $recruiters->min('created_at'),
            ];
        } else {
            // Get all recruiters for this company
            $recruiters = User::where('role', 'recruiter')
                ->where(function ($query) use ($company) {
                    $query->whereHas('company', function ($q) use ($company) {
                        $q->where('name', $company->name);
                    })
                    ->orWhere('company_name', $company->name); // Fallback for old data
                })
                ->with('company')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        // Get company stats
        $totalRecruiters = $recruiters->count();
        $firstRegistered = $recruiters->min('created_at');
        $lastRegistered = $recruiters->max('created_at');
        
        // Get job listings count for this company
        $jobListingsCount = 0;
        if ($company->id) {
            $jobListingsCount = $company->jobListings()->count();
        } else {
            // Count job listings for all recruiters of this company
            $recruiterIds = $recruiters->pluck('id');
            $jobListingsCount = \App\Models\JobListing::whereIn('recruiter_id', $recruiterIds)->count();
        }
        
        return view('admin.companies.show', [
            'title' => 'Company Details: ' . $decodedCompanyName,
            'company' => $company,
            'recruiters' => $recruiters,
            'totalRecruiters' => $totalRecruiters,
            'firstRegistered' => $firstRegistered,
            'lastRegistered' => $lastRegistered,
            'jobListingsCount' => $jobListingsCount,
        ]);
    }

    /**
     * Export companies to CSV.
     */
    public function export(Request $request)
    {
        try {
            // Get companies with their recruiters (same logic as index)
            $query = Company::with('user')
                ->withCount('jobListings')
                ->orderBy('name', 'asc');
            
            $companies = $query->get();
            
            // Get unique company names (group by name to handle multiple companies with same name)
            $companies = $companies->groupBy('name')->map(function ($companyGroup) {
                $firstCompany = $companyGroup->first();
                $companyName = $firstCompany->name;
                
                // Get recruiters for this company (all users with company name matching)
                $recruiters = User::where('role', 'recruiter')
                    ->where(function ($query) use ($companyName) {
                        $query->whereHas('company', function ($q) use ($companyName) {
                            $q->where('name', $companyName);
                        })
                        ->orWhere('company_name', $companyName); // Fallback for old data
                    })
                    ->orderBy('first_name', 'asc')
                    ->get();
                
                $firstCompany->recruiters = $recruiters;
                $firstCompany->recruiter_count = $recruiters->count();
                return $firstCompany;
            })->values();

            $filename = 'companies_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function() use ($companies) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                $csvHeaders = [
                    'Company Name',
                    'Location',
                    'Industry',
                    'Industry Other',
                    'Recruiters',
                    'Recruiter Count',
                    'Job Listings Count',
                    'Registered At'
                ];
                fputcsv($file, $csvHeaders);
                
                // Data rows
                foreach ($companies as $company) {
                    // Get recruiter names as comma-separated string
                    $recruiterNames = '';
                    if (isset($company->recruiters) && $company->recruiters->count() > 0) {
                        $recruiterNames = $company->recruiters->map(function ($recruiter) {
                            return $recruiter->first_name . ' ' . $recruiter->last_name;
                        })->implode(', ');
                    }
                    
                    $row = [
                        $company->name,
                        $company->location ?? 'Not specified',
                        $company->industry ?? 'Not specified',
                        $company->industry_other ?? '',
                        $recruiterNames,
                        $company->recruiter_count ?? 0,
                        $company->job_listings_count ?? 0,
                        $company->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s')
                    ];
                    fputcsv($file, $row);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Company export error: ' . $e->getMessage());
            return redirect()->route('admin.companies.index')
                ->with('error', 'Failed to export companies. Please try again.');
        }
    }
}

