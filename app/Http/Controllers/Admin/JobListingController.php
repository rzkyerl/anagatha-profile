<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JobListingController extends Controller
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
            $query = JobListing::onlyTrashed()
                ->with('recruiter')
                ->orderBy('deleted_at', 'desc');
        } else {
            $query = JobListing::with('recruiter')
                ->orderBy('created_at', 'desc');
        }

        // Recruiter only sees their own job listings
        if ($isRecruiter) {
            $query->where('recruiter_id', $user->id);
        }

        $jobListings = $query->get();
        
        return view('admin.job_listings.index', [
            'title' => 'Job Listings Management',
            'jobListings' => $jobListings,
            'showTrashed' => $showTrashed
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        if ($user && $user->role === 'recruiter') {
            // Recruiter can only create jobs for themselves
            $recruiters = User::where('id', $user->id)->get();
        } else {
            // Admin can assign any recruiter
            $recruiters = User::where('role', 'recruiter')->get();
        }
        
        return view('admin.job_listings.create', [
            'title' => 'Create New Job Listing',
            'recruiters' => $recruiters
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // For recruiter, force recruiter_id to current user to prevent assigning jobs to others
        if ($user && $user->role === 'recruiter') {
            $request->merge(['recruiter_id' => $user->id]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_display' => 'required|string|max:255',
            'work_preference' => 'required|in:wfo,wfh,hybrid',
            'contract_type' => 'required|in:Full Time,Contract,Part Time,Other',
            'contract_type_other' => 'required_if:contract_type,Other|nullable|string|max:255',
            'experience_level' => 'nullable|in:Entry,1-3 Years,3-5 Years,5+ Years,Senior,Mid Level,Other',
            'experience_level_other' => 'required_if:experience_level,Other|nullable|string|max:255',
            'location' => 'required|string|max:255',
            'industry' => 'nullable|in:Technology,Finance,Healthcare,Education,E-commerce,Manufacturing,Consulting,Media,Other',
            'industry_other' => 'required_if:industry,Other|nullable|string|max:255',
            'minimum_degree' => 'nullable|in:Senior High School,Diploma,Bachelor,Master,MBA,Ph.D,Other',
            'minimum_degree_other' => 'required_if:minimum_degree,Other|nullable|string|max:255',
            'recruiter_id' => 'required|exists:users,id',
            'verified' => 'nullable|boolean',
            'status' => 'required|in:draft,active,inactive,closed',
            'posted_at' => 'nullable|date',
        ], [
            'title.required' => 'Job title is required.',
            'company.required' => 'Company name is required.',
            'company_logo.image' => 'Company logo must be an image file.',
            'company_logo.mimes' => 'Company logo must be a file of type: jpeg, png, jpg, gif, webp.',
            'company_logo.max' => 'Company logo size must not exceed 2MB.',
            'salary_display.required' => 'Salary display is required.',
            'work_preference.required' => 'Work preference is required.',
            'work_preference.in' => 'Invalid work preference selected.',
            'contract_type.required' => 'Contract type is required.',
            'contract_type.in' => 'Invalid contract type selected.',
            'contract_type_other.required_if' => 'Please specify the contract type.',
            'experience_level_other.required_if' => 'Please specify the experience level.',
            'industry_other.required_if' => 'Please specify the industry.',
            'minimum_degree_other.required_if' => 'Please specify the minimum degree.',
            'location.required' => 'Location is required.',
            'recruiter_id.required' => 'Recruiter is required.',
            'recruiter_id.exists' => 'Selected recruiter does not exist.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'salary_max.gte' => 'Maximum salary must be greater than or equal to minimum salary.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle company logo upload
            $companyLogo = null;
            if ($request->hasFile('company_logo')) {
                try {
                    $logo = $request->file('company_logo');
                    $logoName = time() . '_' . uniqid() . '.jpg'; // Always save as JPG after compression
                    
                    // Ensure company directory exists
                    $companyDir = storage_path('app/company');
                    if (!file_exists($companyDir)) {
                        if (!mkdir($companyDir, 0755, true)) {
                            throw new \Exception('Failed to create company directory: ' . $companyDir);
                        }
                    }
                    
                    // Compress and resize image
                    $compressedImage = $this->compressImage($logo, 800, 800, 85);
                    
                    // Store to local disk in company folder
                    Storage::disk('local')->put('company/' . $logoName, $compressedImage);
                    
                    $companyLogo = $logoName;
                } catch (\Exception $e) {
                    Log::error('Company logo upload/compression error: ' . $e->getMessage(), [
                        'exception' => $e,
                        'trace' => $e->getTraceAsString(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                    return redirect()->back()
                        ->with('error', 'Failed to upload company logo: ' . $e->getMessage())
                        ->withInput();
                }
            }

            $jobListing = JobListing::create([
                'title' => $request->title,
                'company' => $request->company,
                'company_logo' => $companyLogo,
                'description' => $request->filled('description') ? $request->description : null,
                'salary_min' => $request->filled('salary_min') ? $request->salary_min : null,
                'salary_max' => $request->filled('salary_max') ? $request->salary_max : null,
                'salary_display' => $request->salary_display,
                'work_preference' => $request->work_preference,
                'contract_type' => $request->contract_type,
                'contract_type_other' => ($request->contract_type === 'Other' && $request->filled('contract_type_other')) ? $request->contract_type_other : null,
                'experience_level' => $request->filled('experience_level') ? $request->experience_level : null,
                'experience_level_other' => ($request->experience_level === 'Other' && $request->filled('experience_level_other')) ? $request->experience_level_other : null,
                'location' => $request->location,
                'industry' => $request->filled('industry') ? $request->industry : null,
                'industry_other' => ($request->industry === 'Other' && $request->filled('industry_other')) ? $request->industry_other : null,
                'minimum_degree' => $request->filled('minimum_degree') ? $request->minimum_degree : null,
                'minimum_degree_other' => ($request->minimum_degree === 'Other' && $request->filled('minimum_degree_other')) ? $request->minimum_degree_other : null,
                'recruiter_id' => $request->recruiter_id,
                'verified' => $request->has('verified') ? true : false,
                'status' => $request->status,
                'posted_at' => $request->filled('posted_at') ? $request->posted_at : ($request->status === 'active' ? now() : null),
            ]);

            return redirect()->route('admin.job-listings.index')
                ->with('success', 'Job listing created successfully!');
        } catch (\Exception $e) {
            Log::error('Job listing creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'request_data' => $request->except(['company_logo', '_token']),
                'user_id' => $user->id ?? null,
                'user_role' => $user->role ?? null,
            ]);
            
            $errorMessage = config('app.debug') 
                ? 'Failed to create job listing: ' . $e->getMessage() 
                : 'Failed to create job listing. Please try again.';
            
            return redirect()->back()
                ->with('error', $errorMessage)
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jobListing = JobListing::with('recruiter')->findOrFail($id);

        $this->ensureJobListingAccessible($jobListing);
        
        return view('admin.job_listings.show', [
            'title' => 'Job Listing Details',
            'jobListing' => $jobListing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jobListing = JobListing::findOrFail($id);
        $this->ensureJobListingAccessible($jobListing);

        $user = auth()->user();
        if ($user && $user->role === 'recruiter') {
            $recruiters = User::where('id', $user->id)->get();
        } else {
            $recruiters = User::where('role', 'recruiter')->get();
        }
        
        return view('admin.job_listings.edit', [
            'title' => 'Edit Job Listing',
            'jobListing' => $jobListing,
            'recruiters' => $recruiters
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jobListing = JobListing::findOrFail($id);
        $this->ensureJobListingAccessible($jobListing);

        $user = $request->user();
        if ($user && $user->role === 'recruiter') {
            $request->merge(['recruiter_id' => $user->id]);
        }
        
        Log::info('Job listing update request received', [
            'job_listing_id' => $id,
            'current_status' => $jobListing->status,
            'request_status' => $request->status,
            'all_request_data' => $request->except(['company_logo', '_token', '_method']),
            'user_id' => $user->id ?? null,
            'user_role' => $user->role ?? null
        ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_display' => 'required|string|max:255',
            'work_preference' => 'required|in:wfo,wfh,hybrid',
            'contract_type' => 'required|in:Full Time,Contract,Part Time,Other',
            'contract_type_other' => 'required_if:contract_type,Other|nullable|string|max:255',
            'experience_level' => 'nullable|in:Entry,1-3 Years,3-5 Years,5+ Years,Senior,Mid Level,Other',
            'experience_level_other' => 'required_if:experience_level,Other|nullable|string|max:255',
            'location' => 'required|string|max:255',
            'industry' => 'nullable|in:Technology,Finance,Healthcare,Education,E-commerce,Manufacturing,Consulting,Media,Other',
            'industry_other' => 'required_if:industry,Other|nullable|string|max:255',
            'minimum_degree' => 'nullable|in:Senior High School,Diploma,Bachelor,Master,MBA,Ph.D,Other',
            'minimum_degree_other' => 'required_if:minimum_degree,Other|nullable|string|max:255',
            'recruiter_id' => 'required|exists:users,id',
            'verified' => 'nullable|boolean',
            'status' => 'required|in:draft,active,inactive,closed',
            'posted_at' => 'nullable|date',
        ], [
            'title.required' => 'Job title is required.',
            'company.required' => 'Company name is required.',
            'company_logo.image' => 'Company logo must be an image file.',
            'company_logo.mimes' => 'Company logo must be a file of type: jpeg, png, jpg, gif, webp.',
            'company_logo.max' => 'Company logo size must not exceed 2MB.',
            'salary_display.required' => 'Salary display is required.',
            'work_preference.required' => 'Work preference is required.',
            'work_preference.in' => 'Invalid work preference selected.',
            'contract_type.required' => 'Contract type is required.',
            'contract_type.in' => 'Invalid contract type selected.',
            'contract_type_other.required_if' => 'Please specify the contract type.',
            'experience_level_other.required_if' => 'Please specify the experience level.',
            'industry_other.required_if' => 'Please specify the industry.',
            'minimum_degree_other.required_if' => 'Please specify the minimum degree.',
            'location.required' => 'Location is required.',
            'recruiter_id.required' => 'Recruiter is required.',
            'recruiter_id.exists' => 'Selected recruiter does not exist.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'salary_max.gte' => 'Maximum salary must be greater than or equal to minimum salary.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle company logo upload
            if ($request->hasFile('company_logo')) {
                try {
                    // Delete old logo if exists
                    if ($jobListing->company_logo && Storage::disk('local')->exists('company/' . $jobListing->company_logo)) {
                        Storage::disk('local')->delete('company/' . $jobListing->company_logo);
                    }

                    $logo = $request->file('company_logo');
                    $logoName = time() . '_' . uniqid() . '.jpg'; // Always save as JPG after compression
                    
                    // Ensure company directory exists
                    $companyDir = storage_path('app/company');
                    if (!file_exists($companyDir)) {
                        if (!mkdir($companyDir, 0755, true)) {
                            throw new \Exception('Failed to create company directory: ' . $companyDir);
                        }
                    }
                    
                    // Compress and resize image
                    $compressedImage = $this->compressImage($logo, 800, 800, 85);
                    
                    // Store to local disk in company folder
                    Storage::disk('local')->put('company/' . $logoName, $compressedImage);
                    
                    $companyLogo = $logoName;
                } catch (\Exception $e) {
                    Log::error('Company logo upload/compression error: ' . $e->getMessage(), [
                        'exception' => $e,
                        'trace' => $e->getTraceAsString(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                    return redirect()->back()
                        ->with('error', 'Failed to upload company logo: ' . $e->getMessage())
                        ->withInput();
                }
            } else {
                // Keep existing logo if no new file uploaded
                $companyLogo = $jobListing->company_logo;
            }

            $updateData = [
                'title' => $request->title,
                'company' => $request->company,
                'company_logo' => $companyLogo,
                'description' => $request->description,
                'salary_min' => $request->salary_min,
                'salary_max' => $request->salary_max,
                'salary_display' => $request->salary_display,
                'work_preference' => $request->work_preference,
                'contract_type' => $request->contract_type,
                'contract_type_other' => $request->contract_type === 'Other' ? $request->contract_type_other : null,
                'experience_level' => $request->experience_level,
                'experience_level_other' => $request->experience_level === 'Other' ? $request->experience_level_other : null,
                'location' => $request->location,
                'industry' => $request->industry,
                'industry_other' => $request->industry === 'Other' ? $request->industry_other : null,
                'minimum_degree' => $request->minimum_degree,
                'minimum_degree_other' => $request->minimum_degree === 'Other' ? $request->minimum_degree_other : null,
                'recruiter_id' => $request->recruiter_id,
                'verified' => $request->has('verified') ? true : false,
                'status' => $request->status,
            ];

            // Update posted_at if status changes to active and wasn't posted before
            if ($request->status === 'active' && !$jobListing->posted_at) {
                $updateData['posted_at'] = now();
            } elseif ($request->status !== 'active' && $request->posted_at) {
                $updateData['posted_at'] = $request->posted_at;
            } else {
                $updateData['posted_at'] = $jobListing->posted_at;
            }

            $jobListing->update($updateData);
            
            // Refresh to get updated data
            $jobListing->refresh();
            
            Log::info('Job listing updated successfully', [
                'job_listing_id' => $id,
                'new_status' => $jobListing->status,
                'posted_at' => $jobListing->posted_at
            ]);

            return redirect()->route('admin.job-listings.show', $jobListing->id)
                ->with('success', 'Job listing updated successfully!');
        } catch (\Exception $e) {
            Log::error('Job listing update error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['company_logo', '_token']),
                'job_listing_id' => $id,
                'user_id' => $user->id ?? null,
                'user_role' => $user->role ?? null,
            ]);
            return redirect()->back()
                ->with('error', 'Failed to update job listing. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $jobListing = JobListing::findOrFail($id);
            $this->ensureJobListingAccessible($jobListing);
            // Note: Logo file is kept for soft delete, will be deleted on force delete
            $jobListing->delete();

            return redirect()->route('admin.job-listings.index')
                ->with('success', 'Job listing deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.job-listings.index')
                ->with('error', 'Failed to delete job listing. Please try again.');
        }
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trashed()
    {
        $user = auth()->user();
        $isRecruiter = $user && $user->role === 'recruiter';

        $query = JobListing::onlyTrashed()
            ->with('recruiter')
            ->orderBy('deleted_at', 'desc');

        if ($isRecruiter) {
            $query->where('recruiter_id', $user->id);
        }

        $jobListings = $query->get();
        
        return view('admin.job_listings.index', [
            'title' => 'Deleted Job Listings',
            'jobListings' => $jobListings,
            'showTrashed' => true
        ]);
    }

    /**
     * Restore a soft-deleted resource.
     */
    public function restore(string $id)
    {
        try {
            $jobListing = JobListing::onlyTrashed()->findOrFail($id);
            $this->ensureJobListingAccessible($jobListing);
            $jobListing->restore();

            return redirect()->route('admin.job-listings.index', ['trashed' => '1'])
                ->with('success', 'Job listing restored successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.job-listings.index', ['trashed' => '1'])
                ->with('error', 'Failed to restore job listing. Please try again.');
        }
    }

    /**
     * Permanently delete a soft-deleted resource.
     */
    public function forceDelete(string $id)
    {
        try {
            $jobListing = JobListing::onlyTrashed()->findOrFail($id);
            $this->ensureJobListingAccessible($jobListing);
            
            // Delete company logo file
            if ($jobListing->company_logo && Storage::disk('local')->exists('company/' . $jobListing->company_logo)) {
                Storage::disk('local')->delete('company/' . $jobListing->company_logo);
            }
            
            $jobListing->forceDelete();

            return redirect()->route('admin.job-listings.index', ['trashed' => '1'])
                ->with('success', 'Job listing permanently deleted!');
        } catch (\Exception $e) {
            Log::error('Job listing force delete error: ' . $e->getMessage());
            return redirect()->route('admin.job-listings.index', ['trashed' => '1'])
                ->with('error', 'Failed to permanently delete job listing. Please try again.');
        }
    }

    /**
     * Export job listings to CSV.
     */
    public function export(Request $request)
    {
        try {
            $user = $request->user();
            $isRecruiter = $user && $user->role === 'recruiter';

            $showTrashed = $request->has('trashed') && $request->trashed == '1';
            
            if ($showTrashed) {
                $query = JobListing::onlyTrashed()
                    ->with('recruiter')
                    ->orderBy('deleted_at', 'desc');
                $filename = 'deleted_job_listings_' . date('Y-m-d_His') . '.csv';
            } else {
                $query = JobListing::with('recruiter')
                    ->orderBy('created_at', 'desc');
                $filename = 'job_listings_' . date('Y-m-d_His') . '.csv';
            }

            if ($isRecruiter) {
                $query->where('recruiter_id', $user->id);
            }

            $jobListings = $query->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

        $callback = function() use ($jobListings, $showTrashed) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            $csvHeaders = [
                'ID', 'Title', 'Company', 'Location', 'Work Preference', 'Contract Type',
                'Experience Level', 'Salary Min', 'Salary Max', 'Salary Display',
                'Industry', 'Minimum Degree', 'Status', 'Verified', 'Recruiter',
                'Posted At', 'Created At'
            ];
            if ($showTrashed) {
                $csvHeaders[] = 'Deleted At';
            }
            fputcsv($file, $csvHeaders);
            
            // Data rows
            foreach ($jobListings as $listing) {
                $row = [
                    $listing->id,
                    $listing->title,
                    $listing->company,
                    $listing->location,
                    strtoupper($listing->work_preference),
                    $listing->contract_type,
                    $listing->experience_level ?? 'N/A',
                    $listing->salary_min ? number_format($listing->salary_min, 2) : '',
                    $listing->salary_max ? number_format($listing->salary_max, 2) : '',
                    $listing->salary_display,
                    $listing->industry ?? 'N/A',
                    $listing->minimum_degree ?? 'N/A',
                    ucfirst($listing->status),
                    $listing->verified ? 'Yes' : 'No',
                    $listing->recruiter ? $listing->recruiter->email : 'N/A',
                    $listing->posted_at ? $listing->posted_at->format('Y-m-d H:i:s') : '',
                    $listing->created_at->format('Y-m-d H:i:s')
                ];
                if ($showTrashed) {
                    $row[] = $listing->deleted_at ? $listing->deleted_at->format('Y-m-d H:i:s') : '';
                }
                fputcsv($file, $row);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Job listing export error: ' . $e->getMessage());
            return redirect()->route('admin.job-listings.index')
                ->with('error', 'Failed to export job listings. Please try again.');
        }
    }

    /**
     * Ensure the current user (recruiter) can access this job listing,
     * or is an admin. Abort with 403 otherwise.
     */
    protected function ensureJobListingAccessible(JobListing $jobListing): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403);
        }

        if ($user->role === 'admin') {
            return;
        }

        if ($user->role === 'recruiter' && (int) $jobListing->recruiter_id === (int) $user->id) {
            return;
        }

        abort(403);
    }

    /**
     * Compress and resize company logo image
     */
    private function compressImage($image, $maxWidth = 800, $maxHeight = 800, $quality = 85)
    {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension is not available. Please install php-gd extension.');
        }
        
        $imagePath = $image->getRealPath();
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Calculate new dimensions maintaining aspect ratio
        // Only resize if image is larger than max dimensions
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            // No resize needed, just compress
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
            $ratio = 1;
        } else {
            // Calculate ratio to fit within max dimensions
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = (int)($originalWidth * $ratio);
            $newHeight = (int)($originalHeight * $ratio);
        }
        
        // Create image resource based on mime type
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $sourceImage = @imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $sourceImage = @imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $sourceImage = @imagecreatefromgif($imagePath);
                break;
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    $sourceImage = @imagecreatefromwebp($imagePath);
                } else {
                    throw new \Exception('WebP support is not available');
                }
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }
        
        if (!$sourceImage) {
            throw new \Exception('Failed to create image resource');
        }
        
        // Create new image with calculated dimensions
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mimeType == 'image/png' || $mimeType == 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }
        
        // Resize image if needed
        if ($ratio < 1) {
            imagecopyresampled(
                $newImage, 
                $sourceImage, 
                0, 0, 0, 0, 
                $newWidth, 
                $newHeight, 
                $originalWidth, 
                $originalHeight
            );
        } else {
            // No resize, just copy
            imagecopy($newImage, $sourceImage, 0, 0, 0, 0, $originalWidth, $originalHeight);
        }
        
        // Output to buffer as JPEG
        ob_start();
        imagejpeg($newImage, null, $quality);
        $compressedImage = ob_get_clean();
        
        // Clean up
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return $compressedImage;
    }

    /**
     * Quick update job listing status.
     */
    public function updateStatus(Request $request, string $id)
    {
        try {
            $jobListing = JobListing::findOrFail($id);
            $this->ensureJobListingAccessible($jobListing);

            $request->validate([
                'status' => 'required|in:draft,active,inactive,closed',
            ]);

            $oldStatus = $jobListing->status;
            $newStatus = $request->status;

            // Update status
            $jobListing->status = $newStatus;

            // Update posted_at if status changes to active and wasn't posted before
            if ($newStatus === 'active' && !$jobListing->posted_at) {
                $jobListing->posted_at = now();
            }

            $jobListing->save();

            Log::info('Job listing status updated', [
                'job_listing_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => $request->user()->id ?? null,
                'user_role' => $request->user()->role ?? null
            ]);

            return redirect()->back()
                ->with('success', 'Job listing status updated successfully from ' . ucfirst($oldStatus) . ' to ' . ucfirst($newStatus) . '!');
        } catch (\Exception $e) {
            Log::error('Job listing status update error: ' . $e->getMessage(), [
                'exception' => $e,
                'job_listing_id' => $id,
                'request_status' => $request->status ?? null
            ]);
            return redirect()->back()
                ->with('error', 'Failed to update job listing status. Please try again.');
        }
    }

    /**
     * Serve company logo image from local storage.
     */
    public function companyLogo($filename)
    {
        // Check if file exists in the 'company' folder within local storage
        $path = 'company/' . $filename;
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }
        
        // Get file content (company logos are always JPEG after compression)
        $file = Storage::disk('local')->get($path);
        
        return response($file, 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=31536000') // Cache for 1 year
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}

