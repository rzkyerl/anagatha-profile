<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApply;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JobApplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $isRecruiter = $user && $user->role === 'recruiter';

        $showTrashed = $request->has('trashed') && $request->trashed == '1';
        
        if ($showTrashed) {
            $query = JobApply::onlyTrashed()
                ->with(['user', 'jobListing'])
                ->orderBy('deleted_at', 'desc');
        } else {
            $query = JobApply::with(['user', 'jobListing'])
                ->orderBy('created_at', 'desc');
        }

        // Recruiter only sees applications to their own job listings
        if ($isRecruiter) {
            $query->whereHas('jobListing', function ($q) use ($user) {
                $q->where('recruiter_id', $user->id);
            });
        }

        $jobApplies = $query->get();
        
        return view('admin.job_apply.index', [
            'title' => 'Job Applications Management',
            'jobApplies' => $jobApplies,
            'showTrashed' => $showTrashed
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        $users = User::orderBy('first_name')->get();

        if ($user && $user->role === 'recruiter') {
            // Only active listings owned by this recruiter
            $jobListings = JobListing::where('status', 'active')
                ->where('recruiter_id', $user->id)
                ->orderBy('title')
                ->get();
        } else {
        $jobListings = JobListing::where('status', 'active')->orderBy('title')->get();
        }
        
        return view('admin.job_apply.create', [
            'title' => 'Create New Job Application',
            'users' => $users,
            'jobListings' => $jobListings
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'job_listing_id' => 'required|exists:job_listings,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'current_salary' => 'nullable|string|max:100',
            'expected_salary' => 'required|string|max:100',
            'availability' => 'required|string',
            'relocation' => 'required|in:Yes,No,Other',
            'relocation_other' => 'required_if:relocation,Other|nullable|string|max:255',
            'linkedin' => 'nullable|url|max:500',
            'github' => 'nullable|url|max:500',
            'social_media' => 'nullable|url|max:500',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'portfolio_file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
            'cover_letter' => 'nullable|string',
            'reason_applying' => 'required|string',
            'relevant_experience' => 'nullable|string',
            'status' => 'required|in:pending,shortlisted,interview,hired,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except(['cv', 'portfolio_file']);
            $data['applied_at'] = now();
            
            // Handle relocation_other
            if ($request->relocation === 'Other') {
                $data['relocation_other'] = $request->relocation_other;
            } else {
                $data['relocation_other'] = null;
            }

            // Handle CV file upload
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('resume', 'local');
                $data['cv'] = $cvPath;
            }

            // Handle Portfolio file upload
            if ($request->hasFile('portfolio_file')) {
                $portfolioPath = $request->file('portfolio_file')->store('job_applies/portfolio', 'local');
                $data['portfolio_file'] = $portfolioPath;
            }

            JobApply::create($data);

            return redirect()->route('admin.job-apply.index')
                ->with('success', 'Job application created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create job application. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobApply = JobApply::with(['user', 'jobListing'])->findOrFail($id);
        $this->ensureJobApplyAccessible($jobApply);
        
        return view('admin.job_apply.show', [
            'title' => 'Job Application Details',
            'jobApply' => $jobApply
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobApply = JobApply::with(['user', 'jobListing'])->findOrFail($id);
        $this->ensureJobApplyAccessible($jobApply);

        $user = auth()->user();

        if ($user && $user->role === 'recruiter') {
            $jobListings = JobListing::where('status', 'active')
                ->where('recruiter_id', $user->id)
                ->orderBy('title')
                ->get();
        } else {
        $jobListings = JobListing::where('status', 'active')->orderBy('title')->get();
        }
        
        return view('admin.job_apply.edit', [
            'title' => 'Edit Job Application',
            'jobApply' => $jobApply,
            'jobListings' => $jobListings
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jobApply = JobApply::findOrFail($id);
        $this->ensureJobApplyAccessible($jobApply);

        $validator = Validator::make($request->all(), [
            'job_listing_id' => 'nullable|exists:job_listings,id',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'current_salary' => 'nullable|string|max:100',
            'expected_salary' => 'required|string|max:100',
            'availability' => 'required|string',
            'relocation' => 'required|in:Yes,No,Other',
            'relocation_other' => 'required_if:relocation,Other|nullable|string|max:255',
            'linkedin' => 'nullable|url|max:500',
            'github' => 'nullable|url|max:500',
            'social_media' => 'nullable|url|max:500',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'portfolio_file' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
            'cover_letter' => 'nullable|string',
            'reason_applying' => 'required|string',
            'relevant_experience' => 'nullable|string',
            'status' => 'required|in:pending,shortlisted,interview,hired,rejected',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except(['cv', 'portfolio_file']);
            
            // Handle relocation_other
            if ($request->relocation === 'Other') {
                $data['relocation_other'] = $request->relocation_other;
            } else {
                $data['relocation_other'] = null;
            }

            // Handle CV file upload
            if ($request->hasFile('cv')) {
                // Delete old CV if exists (check both local and public for backward compatibility)
                if ($jobApply->cv) {
                    if (Storage::disk('local')->exists($jobApply->cv)) {
                        Storage::disk('local')->delete($jobApply->cv);
                    } elseif (Storage::disk('public')->exists($jobApply->cv)) {
                        Storage::disk('public')->delete($jobApply->cv);
                    }
                }
                $cvPath = $request->file('cv')->store('resume', 'local');
                $data['cv'] = $cvPath;
            }

            // Handle Portfolio file upload
            if ($request->hasFile('portfolio_file')) {
                // Delete old portfolio if exists (check both local and public for backward compatibility)
                if ($jobApply->portfolio_file) {
                    if (Storage::disk('local')->exists($jobApply->portfolio_file)) {
                        Storage::disk('local')->delete($jobApply->portfolio_file);
                    } elseif (Storage::disk('public')->exists($jobApply->portfolio_file)) {
                        Storage::disk('public')->delete($jobApply->portfolio_file);
                    }
                }
                $portfolioPath = $request->file('portfolio_file')->store('job_applies/portfolio', 'local');
                $data['portfolio_file'] = $portfolioPath;
            }

            $jobApply->update($data);

            return redirect()->route('admin.job-apply.show', $jobApply->id)
                ->with('success', 'Job application updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update job application. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $jobApply = JobApply::findOrFail($id);
            $this->ensureJobApplyAccessible($jobApply);
            
            // Delete associated files (check both local and public for backward compatibility)
            if ($jobApply->cv) {
                if (Storage::disk('local')->exists($jobApply->cv)) {
                    Storage::disk('local')->delete($jobApply->cv);
                } elseif (Storage::disk('public')->exists($jobApply->cv)) {
                    Storage::disk('public')->delete($jobApply->cv);
                }
            }
            
            if ($jobApply->portfolio_file) {
                if (Storage::disk('local')->exists($jobApply->portfolio_file)) {
                    Storage::disk('local')->delete($jobApply->portfolio_file);
                } elseif (Storage::disk('public')->exists($jobApply->portfolio_file)) {
                    Storage::disk('public')->delete($jobApply->portfolio_file);
                }
            }
            
            $jobApply->delete();

            return redirect()->route('admin.job-apply.index')
                ->with('success', 'Job application deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.job-apply.index')
                ->with('error', 'Failed to delete job application. Please try again.');
        }
    }

    /**
     * Download CV file.
     */
    public function downloadCv(string $id)
    {
        $jobApply = JobApply::findOrFail($id);
        $this->ensureJobApplyAccessible($jobApply);
        
        if (!$jobApply->cv) {
            abort(404, 'CV file not found.');
        }

        // Check local storage first (new files)
        if (Storage::disk('local')->exists($jobApply->cv)) {
            $filePath = Storage::disk('local')->path($jobApply->cv);
        }
        // Fallback to public storage for backward compatibility (old files)
        elseif (Storage::disk('public')->exists($jobApply->cv)) {
            $filePath = Storage::disk('public')->path($jobApply->cv);
        }
        else {
            abort(404, 'CV file not found.');
        }

        $fileName = 'CV_' . str_replace(' ', '_', $jobApply->full_name) . '_' . basename($jobApply->cv);

        return response()->download($filePath, $fileName);
    }

    /**
     * Download Portfolio file.
     */
    public function downloadPortfolio(string $id)
    {
        $jobApply = JobApply::findOrFail($id);
        $this->ensureJobApplyAccessible($jobApply);
        
        if (!$jobApply->portfolio_file) {
            abort(404, 'Portfolio file not found.');
        }

        // Check local storage first (new files)
        if (Storage::disk('local')->exists($jobApply->portfolio_file)) {
            $filePath = Storage::disk('local')->path($jobApply->portfolio_file);
        }
        // Fallback to public storage for backward compatibility (old files)
        elseif (Storage::disk('public')->exists($jobApply->portfolio_file)) {
            $filePath = Storage::disk('public')->path($jobApply->portfolio_file);
        }
        else {
            abort(404, 'Portfolio file not found.');
        }

        $fileName = 'Portfolio_' . str_replace(' ', '_', $jobApply->full_name) . '_' . basename($jobApply->portfolio_file);

        return response()->download($filePath, $fileName);
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trashed()
    {
        $user = auth()->user();
        $isRecruiter = $user && $user->role === 'recruiter';

        $query = JobApply::onlyTrashed()
            ->with(['user', 'jobListing'])
            ->orderBy('deleted_at', 'desc');

        if ($isRecruiter) {
            $query->whereHas('jobListing', function ($q) use ($user) {
                $q->where('recruiter_id', $user->id);
            });
        }

        $jobApplies = $query->get();
        
        return view('admin.job_apply.index', [
            'title' => 'Deleted Job Applications',
            'jobApplies' => $jobApplies,
            'showTrashed' => true
        ]);
    }

    /**
     * Restore a soft-deleted resource.
     */
    public function restore(string $id)
    {
        try {
            $jobApply = JobApply::onlyTrashed()->findOrFail($id);
            $this->ensureJobApplyAccessible($jobApply);
            $jobApply->restore();

            return redirect()->route('admin.job-apply.index', ['trashed' => '1'])
                ->with('success', 'Job application restored successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.job-apply.index', ['trashed' => '1'])
                ->with('error', 'Failed to restore job application. Please try again.');
        }
    }

    /**
     * Permanently delete a soft-deleted resource.
     */
    public function forceDelete(string $id)
    {
        try {
            $jobApply = JobApply::onlyTrashed()->findOrFail($id);
            $this->ensureJobApplyAccessible($jobApply);
            
            // Delete associated files
            if ($jobApply->cv) {
                if (Storage::disk('local')->exists($jobApply->cv)) {
                    Storage::disk('local')->delete($jobApply->cv);
                } elseif (Storage::disk('public')->exists($jobApply->cv)) {
                    Storage::disk('public')->delete($jobApply->cv);
                }
            }
            
            if ($jobApply->portfolio_file) {
                if (Storage::disk('local')->exists($jobApply->portfolio_file)) {
                    Storage::disk('local')->delete($jobApply->portfolio_file);
                } elseif (Storage::disk('public')->exists($jobApply->portfolio_file)) {
                    Storage::disk('public')->delete($jobApply->portfolio_file);
                }
            }
            
            $jobApply->forceDelete();

            return redirect()->route('admin.job-apply.index', ['trashed' => '1'])
                ->with('success', 'Job application permanently deleted!');
        } catch (\Exception $e) {
            return redirect()->route('admin.job-apply.index', ['trashed' => '1'])
                ->with('error', 'Failed to permanently delete job application. Please try again.');
        }
    }

    /**
     * Export job applications to CSV.
     */
    public function export(Request $request)
    {
        try {
            $user = $request->user();
            $isRecruiter = $user && $user->role === 'recruiter';

            $showTrashed = $request->has('trashed') && $request->trashed == '1';
            
            if ($showTrashed) {
                $query = JobApply::onlyTrashed()
                    ->with(['user', 'jobListing'])
                    ->orderBy('deleted_at', 'desc');
                $filename = 'deleted_job_applications_' . date('Y-m-d_His') . '.csv';
            } else {
                $query = JobApply::with(['user', 'jobListing'])
                    ->orderBy('created_at', 'desc');
                $filename = 'job_applications_' . date('Y-m-d_His') . '.csv';
            }

            if ($isRecruiter) {
                $query->whereHas('jobListing', function ($q) use ($user) {
                    $q->where('recruiter_id', $user->id);
                });
            }

            $jobApplies = $query->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

        $callback = function() use ($jobApplies, $showTrashed) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            $csvHeaders = [
                'ID', 'Full Name', 'Email', 'Phone', 'Address',
                'Current Salary', 'Expected Salary', 'Availability', 'Relocation',
                'LinkedIn', 'GitHub', 'Job Position', 'Company',
                'Status', 'Applied At', 'Created At'
            ];
            if ($showTrashed) {
                $csvHeaders[] = 'Deleted At';
            }
            fputcsv($file, $csvHeaders);
            
            // Data rows
            foreach ($jobApplies as $apply) {
                $row = [
                    $apply->id,
                    $apply->full_name,
                    $apply->email,
                    $apply->phone,
                    $apply->address,
                    $apply->current_salary ?? 'N/A',
                    $apply->expected_salary,
                    $apply->availability,
                    $apply->relocation,
                    $apply->linkedin ?? 'N/A',
                    $apply->github ?? 'N/A',
                    $apply->jobListing ? $apply->jobListing->title : 'N/A',
                    $apply->jobListing ? $apply->jobListing->company : 'N/A',
                    ucfirst($apply->status),
                    $apply->applied_at ? \Carbon\Carbon::parse($apply->applied_at)->format('Y-m-d H:i:s') : '',
                    $apply->created_at->format('Y-m-d H:i:s')
                ];
                if ($showTrashed) {
                    $row[] = $apply->deleted_at ? $apply->deleted_at->format('Y-m-d H:i:s') : '';
                }
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Job application export error: ' . $e->getMessage());
            return redirect()->route('admin.job-apply.index')
                ->with('error', 'Failed to export job applications. Please try again.');
        }
    }

    /**
     * Ensure the current user (recruiter) can access this job application,
     * or is an admin. Abort with 403 otherwise.
     */
    protected function ensureJobApplyAccessible(JobApply $jobApply): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'recruiter') {
            $jobListing = $jobApply->jobListing;
            if ($jobListing && (int) $jobListing->recruiter_id === (int) $user->id) {
                return;
            }
        }

        abort(403);
    }
}
