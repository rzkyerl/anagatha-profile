<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $showTrashed = $request->has('trashed') && $request->trashed == '1';
        
        if ($showTrashed) {
            $users = User::onlyTrashed()
                ->orderBy('deleted_at', 'desc')
                ->get();
        } else {
        $users = User::orderBy('created_at', 'desc')->get();
        }
        
        return view('admin.user.index', [
            'title' => 'User Management',
            'users' => $users,
            'showTrashed' => $showTrashed
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create', [
            'title' => 'Create New User'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,recruiter',
        ], [
            'first_name.required' => 'First name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Role is required.',
            'role.in' => 'Invalid role selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name ?? null,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create user. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.user.show', [
            'title' => 'User Details',
            'user' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        
        return view('admin.user.edit', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user,recruiter',
        ], [
            'first_name.required' => 'First name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Role is required.',
            'role.in' => 'Invalid role selected.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $updateData = [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name ?? null,
                'email' => $request->email,
                'role' => $request->role,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            return redirect()->route('admin.users.show', $user->id)
                ->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent deleting own account (optional - remove if not needed)
            if (auth()->check() && auth()->id() == $user->id) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'You cannot delete your own account.');
            }
            
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to delete user. Please try again.');
        }
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trashed()
    {
        $users = User::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();
        
        return view('admin.user.index', [
            'title' => 'Deleted Users',
            'users' => $users,
            'showTrashed' => true
        ]);
    }

    /**
     * Restore a soft-deleted resource.
     */
    public function restore(string $id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->route('admin.users.index', ['trashed' => '1'])
                ->with('success', 'User restored successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index', ['trashed' => '1'])
                ->with('error', 'Failed to restore user. Please try again.');
        }
    }

    /**
     * Permanently delete a soft-deleted resource.
     */
    public function forceDelete(string $id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->forceDelete();

            return redirect()->route('admin.users.index', ['trashed' => '1'])
                ->with('success', 'User permanently deleted!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index', ['trashed' => '1'])
                ->with('error', 'Failed to permanently delete user. Please try again.');
        }
    }

    /**
     * Export users to CSV.
     */
    public function export(Request $request)
    {
        try {
            $showTrashed = $request->has('trashed') && $request->trashed == '1';
            
            if ($showTrashed) {
                $users = User::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
                $filename = 'deleted_users_' . date('Y-m-d_His') . '.csv';
            } else {
                $users = User::orderBy('created_at', 'desc')->get();
                $filename = 'users_' . date('Y-m-d_His') . '.csv';
            }

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0',
            ];

            $callback = function() use ($users, $showTrashed) {
                $file = fopen('php://output', 'w');
                
                // Add UTF-8 BOM for Excel compatibility
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Headers
                $csvHeaders = ['ID', 'First Name', 'Last Name', 'Email', 'Role', 'Email Verified', 'Created At'];
                if ($showTrashed) {
                    $csvHeaders[] = 'Deleted At';
                }
                fputcsv($file, $csvHeaders);
                
                // Data rows
                foreach ($users as $user) {
                    $row = [
                        $user->id,
                        $user->first_name,
                        $user->last_name,
                        $user->email,
                        ucfirst($user->role),
                        $user->email_verified_at ? 'Yes' : 'No',
                        $user->created_at->format('Y-m-d H:i:s')
                    ];
                    if ($showTrashed) {
                        $row[] = $user->deleted_at ? $user->deleted_at->format('Y-m-d H:i:s') : '';
                    }
                    fputcsv($file, $row);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('User export error: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                ->with('error', 'Failed to export users. Please try again.');
        }
    }
}
