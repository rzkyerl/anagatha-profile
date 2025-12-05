<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the user profile page.
     */
    public function show()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('status', 'Please login to view your profile.')
                ->with('toast_type', 'info');
        }
        
        return view('pages.profile', [
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
            return redirect()->route('login')
                ->with('status', 'Please login to update your profile.')
                ->with('toast_type', 'info');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'github' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'x' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'github.url' => 'Please enter a valid GitHub URL.',
            'linkedin.url' => 'Please enter a valid LinkedIn URL.',
            'x.url' => 'Please enter a valid X (Twitter) URL.',
            'instagram.url' => 'Please enter a valid Instagram URL.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.max' => 'Avatar size must not exceed 2MB.',
        ]);

        // Update password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
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
                
                // Ensure avatar directory exists
                $avatarDir = storage_path('app/avatar');
                if (!file_exists($avatarDir)) {
                    if (!mkdir($avatarDir, 0755, true)) {
                        throw new \Exception('Failed to create avatar directory: ' . $avatarDir);
                    }
                }
                
                // Compress and resize image
                $compressedImage = $this->compressImage($avatar);
                
                // Store to local disk in avatar folder
                Storage::disk('local')->put('avatar/' . $avatarName, $compressedImage);
                
                // Store just the filename (not full path)
                $validated['avatar'] = $avatarName;
            } catch (\Exception $e) {
                Log::error('Avatar upload/compression error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
                return redirect()->back()
                    ->with('status', 'Failed to upload avatar. Please try again.')
                    ->with('toast_type', 'error')
                    ->withInput();
            }
        }

        try {
            // Update user profile
            $user->update($validated);

            return redirect()->route('profile')
                ->with('status', 'Profile updated successfully.')
                ->with('toast_type', 'success');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()
                ->with('status', 'Failed to update profile. Please try again.')
                ->with('toast_type', 'error')
                ->withInput();
        }
    }

    /**
     * Compress and resize image
     */
    private function compressImage($image, $maxWidth = 400, $maxHeight = 400, $quality = 85)
    {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension is not available. Please install php-gd extension.');
        }
        
        // Verify required GD functions are available
        $requiredFunctions = ['imagejpeg', 'imagecreatefromjpeg', 'imagecreatetruecolor', 'imagecopyresampled'];
        $missingFunctions = [];
        foreach ($requiredFunctions as $func) {
            if (!function_exists($func)) {
                $missingFunctions[] = $func;
            }
        }
        
        if (!empty($missingFunctions)) {
            $missingList = implode(', ', $missingFunctions);
            throw new \Exception("Required GD functions ({$missingList}) are not available. Please ensure GD extension is compiled with JPEG support.");
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
     * Serve avatar image from local storage
     */
    public function avatar($filename)
    {
        // Check if file exists
        $filePath = 'avatar/' . $filename;
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404);
        }
        
        // Get file (avatars are always JPEG after compression)
        $file = Storage::disk('local')->get($filePath);
        
        return response($file, 200)
            ->header('Content-Type', 'image/jpeg')
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}

