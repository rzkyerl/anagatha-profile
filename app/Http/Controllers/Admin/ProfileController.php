<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile settings page for admin/recruiter.
     */
    public function show()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('admin.login');
        }
        
        return view('admin.profile.settings', [
            'title' => 'Profile Settings',
            'user' => $user,
        ]);
    }

    /**
     * Update user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('admin.login');
        }

        // Validation rules based on role
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Add recruiter-specific fields
        if ($user->role === 'recruiter') {
            $rules['company_name'] = 'nullable|string|max:255';
            $rules['job_title'] = 'nullable|in:HR Manager,HR Business Partner,Talent Acquisition Specialist,Recruitment Manager,HR Director,HR Coordinator,Recruiter,Senior Recruiter,HR Generalist,People Operations Manager,Other';
            $rules['job_title_other'] = 'required_if:job_title,Other|nullable|string|max:255';
        }

        $validated = $request->validate($rules, [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.max' => 'Avatar size must not exceed 2MB.',
            'job_title_other.required_if' => 'Please specify the job title.',
        ]);

        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            try {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('local')->exists('avatar/' . basename($user->avatar))) {
                    Storage::disk('local')->delete('avatar/' . basename($user->avatar));
                }
                
                $avatar = $request->file('avatar');
                $avatarName = time() . '_' . uniqid() . '.jpg';
                
                // Compress and resize image
                $compressedImage = $this->compressImage($avatar);
                
                // Store to local disk in avatar folder
                Storage::disk('local')->put('avatar/' . $avatarName, $compressedImage);
                
                // Store just the filename (not full path)
                $validated['avatar'] = $avatarName;
            } catch (\Exception $e) {
                Log::error('Avatar upload/compression error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Failed to upload avatar. Please try again.')
                    ->withInput();
            }
        }

        try {
            // Handle job_title_other for recruiters
            if ($user->role === 'recruiter') {
                if ($request->job_title === 'Other') {
                    $validated['job_title_other'] = $request->job_title_other;
                } else {
                    $validated['job_title_other'] = null;
                }
            }
            
            // Update user profile
            $user->update($validated);

            return redirect()->route('admin.profile.settings')
                ->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update profile. Please try again.')
                ->withInput();
        }
    }

    /**
     * Compress and resize image
     */
    private function compressImage($image, $maxWidth = 400, $maxHeight = 400, $quality = 85)
    {
        $imagePath = $image->getRealPath();
        $imageInfo = getimagesize($imagePath);
        
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Calculate new dimensions maintaining aspect ratio
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
            $ratio = 1;
        } else {
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
     * Serve avatar image from local storage
     */
    public function avatar($filename)
    {
        $filePath = 'avatar/' . $filename;
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404);
        }
        
        $file = Storage::disk('local')->get($filePath);
        
        return response($file, 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}

