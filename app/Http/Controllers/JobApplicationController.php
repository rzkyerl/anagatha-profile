<?php

namespace App\Http\Controllers;

use App\Models\JobApply;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Store a newly created job application.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the form data
        $validated = $request->validate([
            'job_listing_id' => ['required', 'exists:job_listings,id'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:500'],
            'current_salary' => ['nullable', 'string', 'max:100'],
            'expected_salary' => ['required', 'string', 'max:100'],
            'availability' => ['required', 'string'],
            'relocation' => ['required', 'in:Yes,No,Other'],
            'relocation_other' => ['required_if:relocation,Other', 'nullable', 'string', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:500'],
            'github' => ['nullable', 'url', 'max:500'],
            'social_media' => ['nullable', 'url', 'max:500'],
            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
            'portfolio_file' => ['nullable', 'file', 'mimes:pdf,zip,rar', 'max:10240'], // 10MB max
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'reason_applying' => ['required', 'string', 'min:10', 'max:2000'],
            'relevant_experience' => ['nullable', 'string', 'max:5000'],
        ]);

        try {
            // Get authenticated user (middleware auth ensures user is logged in)
            $userId = auth()->id();

            // Check if user has already applied for this job
            $existingApplication = JobApply::where('user_id', $userId)
                ->where('job_listing_id', $validated['job_listing_id'])
                ->first();
            
            if ($existingApplication) {
                return redirect()
                    ->back()
                    ->with('status', 'You have already applied for this job position. Please check your application history.')
                    ->with('toast_type', 'warning')
                    ->withInput();
            }

            // Prepare data for saving
            $data = $validated;
            $data['user_id'] = $userId;
            $data['status'] = 'pending';
            $data['applied_at'] = now();
            
            // Handle relocation_other
            if ($request->relocation === 'Other') {
                $data['relocation_other'] = $request->relocation_other;
            } else {
                $data['relocation_other'] = null;
            }

            // Handle CV file upload - store in resume folder (local disk)
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('resume', 'local');
                $data['cv'] = $cvPath;
            }

            // Handle Portfolio file upload - store in job_applies/portfolio folder (local disk)
            if ($request->hasFile('portfolio_file')) {
                $portfolioPath = $request->file('portfolio_file')->store('job_applies/portfolio', 'local');
                $data['portfolio_file'] = $portfolioPath;
            }

            // Note: cv and portfolio_file are already in $data array from validated, 
            // but we overwrite them with file paths above, so no need to unset

            // Create job application
            JobApply::create($data);

            Log::info('Job application submitted successfully', [
                'email' => $validated['email'],
                'name' => $validated['full_name'],
                'job_listing_id' => $validated['job_listing_id'],
            ]);

            // Redirect back with success message and modal trigger
            return redirect()
                ->back()
                ->with('application_success', true)
                ->with('status', 'Your job application has been submitted successfully!')
                ->with('toast_type', 'success');
        } catch (\Exception $e) {
            Log::error('Job application submission error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->input('email'),
            ]);

            return redirect()
                ->back()
                ->with('status', 'There was an error submitting your application. Please try again.')
                ->with('toast_type', 'error')
                ->withInput();
        }
    }
}

