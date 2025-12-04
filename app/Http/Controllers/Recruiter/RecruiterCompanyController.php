<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RecruiterCompanyController extends Controller
{
    /**
     * Display the recruiter's company information.
     */
    public function show()
    {
        $user = Auth::user();
        
        // Ensure user is a recruiter
        if ($user->role !== 'recruiter') {
            return redirect()->route('recruiter.dashboard')
                ->with('status', 'Access denied.')
                ->with('toast_type', 'error');
        }

        return view('recruiter.company.show', [
            'title' => 'My Company',
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing company information.
     * Recruiters always have company data from registration, so this is always accessible.
     */
    public function edit()
    {
        $user = Auth::user();
        
        // Ensure user is a recruiter
        if ($user->role !== 'recruiter') {
            return redirect()->route('recruiter.dashboard')
                ->with('status', 'Access denied.')
                ->with('toast_type', 'error');
        }

        return view('recruiter.company.edit', [
            'title' => 'Edit Company Information',
            'user' => $user,
        ]);
    }

    /**
     * Update company information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Ensure user is a recruiter
        if ($user->role !== 'recruiter') {
            return redirect()->route('recruiter.dashboard')
                ->with('status', 'Access denied.')
                ->with('toast_type', 'error');
        }

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|in:HR Manager,HR Business Partner,Talent Acquisition Specialist,Recruitment Manager,HR Director,HR Coordinator,Recruiter,Senior Recruiter,HR Generalist,People Operations Manager,Other',
            'job_title_other' => 'required_if:job_title,Other|nullable|string|max:255',
            'industry' => 'required|in:Technology,Healthcare,Finance,Education,Manufacturing,Retail,Real Estate,Hospitality,Transportation & Logistics,Energy,Telecommunications,Media & Entertainment,Consulting,Legal,Construction,Agriculture,Food & Beverage,Automotive,Aerospace,Pharmaceuticals,Other',
            'industry_other' => 'required_if:industry,Other|nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
        ], [
            'company_name.required' => 'Company Name is required.',
            'job_title.required' => 'Job Title / Position is required.',
            'job_title.in' => 'Please select a valid job title.',
            'job_title_other.required_if' => 'Please enter your custom job title.',
            'industry.required' => 'Industry is required.',
            'industry.in' => 'Please select a valid industry.',
            'industry_other.required_if' => 'Please enter your custom industry.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle company logo upload
            $companyLogo = $user->company_logo; // Keep existing logo if no new one uploaded
            if ($request->hasFile('company_logo')) {
                try {
                    // Delete old logo if exists
                    if ($user->company_logo && Storage::disk('local')->exists('company/' . $user->company_logo)) {
                        Storage::disk('local')->delete('company/' . $user->company_logo);
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
                        ->with('status', 'Failed to upload company logo: ' . $e->getMessage())
                        ->with('toast_type', 'error')
                        ->withInput();
                }
            }

            // Handle 'Other' selection for job_title
            $jobTitleOther = $request->job_title === 'Other' ? $request->job_title_other : null;
            
            // Handle 'Other' selection for industry
            $industryOther = $request->industry === 'Other' ? $request->industry_other : null;

            // Update user data (for backward compatibility)
            $user->update([
                'company_name' => $request->company_name,
                'company_logo' => $companyLogo,
                'job_title' => $request->job_title,
                'job_title_other' => $jobTitleOther,
                'industry' => $request->industry,
                'industry_other' => $industryOther,
                'phone' => $request->phone ?? $user->phone,
            ]);

            // Update or create company record in companies table
            $company = Company::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'name' => $request->company_name,
                    'logo' => $companyLogo,
                    'industry' => $request->industry,
                    'industry_other' => $industryOther,
                    'location' => $request->location ?? null,
                ]
            );

            return redirect()->route('recruiter.company.show')
                ->with('status', 'Company information updated successfully!')
                ->with('toast_type', 'success');
        } catch (\Exception $e) {
            Log::error('Company update error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->with('status', 'Failed to update company information. Please try again.')
                ->with('toast_type', 'error')
                ->withInput();
        }
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
                $sourceImage = @imagecreatefromwebp($imagePath);
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $mimeType);
        }
        
        if (!$sourceImage) {
            throw new \Exception('Failed to create image resource from file');
        }
        
        // Create new image with calculated dimensions
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG and GIF
        if ($mimeType === 'image/png' || $mimeType === 'image/gif') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefill($newImage, 0, 0, $transparent);
        }
        
        // Resize image
        imagecopyresampled(
            $newImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth,
            $newHeight,
            $originalWidth,
            $originalHeight
        );
        
        // Output to buffer as JPEG
        ob_start();
        imagejpeg($newImage, null, $quality);
        $compressedImageData = ob_get_contents();
        ob_end_clean();
        
        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($newImage);
        
        return $compressedImageData;
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

