<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        // Get companies from recruiters with their details
        $query = User::where('role', 'recruiter')
            ->whereNotNull('company_name')
            ->where('company_name', '!=', '')
            ->select(
                'company_name',
                DB::raw('COUNT(*) as recruiter_count'),
                DB::raw('MIN(created_at) as first_registered'),
                DB::raw('MAX(created_at) as last_registered')
            )
            ->groupBy('company_name')
            ->orderBy('company_name', 'asc');
        
        // Search functionality
        if ($search) {
            $query->where('company_name', 'like', '%' . $search . '%');
        }
        
        $companies = $query->get();
        
        // Get detailed recruiter info for each company
        $companiesWithRecruiters = $companies->map(function ($company) {
            $recruiters = User::where('role', 'recruiter')
                ->where('company_name', $company->company_name)
                ->select('id', 'first_name', 'last_name', 'email', 'phone', 'job_title', 'company_name', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return [
                'company_name' => $company->company_name,
                'recruiter_count' => $company->recruiter_count,
                'first_registered' => $company->first_registered,
                'last_registered' => $company->last_registered,
                'recruiters' => $recruiters,
            ];
        });
        
        return view('admin.companies.index', [
            'title' => 'Company List',
            'companies' => $companiesWithRecruiters,
            'search' => $search,
        ]);
    }

    /**
     * Display the specified company with all recruiters.
     */
    public function show(string $companyName)
    {
        $decodedCompanyName = urldecode($companyName);
        
        $recruiters = User::where('role', 'recruiter')
            ->where('company_name', $decodedCompanyName)
            ->orderBy('created_at', 'desc')
            ->get();
        
        if ($recruiters->isEmpty()) {
            return redirect()->route('admin.companies.index')
                ->with('error', 'Company not found.');
        }
        
        // Get company stats
        $totalRecruiters = $recruiters->count();
        $firstRegistered = $recruiters->min('created_at');
        $lastRegistered = $recruiters->max('created_at');
        
        return view('admin.companies.show', [
            'title' => 'Company Details: ' . $decodedCompanyName,
            'companyName' => $decodedCompanyName,
            'recruiters' => $recruiters,
            'totalRecruiters' => $totalRecruiters,
            'firstRegistered' => $firstRegistered,
            'lastRegistered' => $lastRegistered,
        ]);
    }
}

