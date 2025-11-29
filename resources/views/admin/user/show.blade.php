@extends('admin.admin_layouts.app')

@section('title', 'User Details')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class="breadcrumb-item active">{{ $user->first_name }} {{ $user->last_name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-8 mx-auto">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- User Profile Card -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">User Details</h4>
                            <p class="card-title-desc mb-0">View user information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                                <i class="ri-pencil-line align-middle me-1"></i> Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Profile Picture / Avatar -->
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            <div class="avatar-xl mx-auto mb-3">
                                <span class="avatar-title bg-primary rounded-circle font-size-24">
                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                </span>
                            </div>
                            <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                            <p class="text-muted mb-0">
                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'recruiter' ? 'info' : 'primary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </p>
                        </div>

                        <!-- User Information -->
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 200px;">First Name:</th>
                                            <td>{{ $user->first_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Last Name:</th>
                                            <td>{{ $user->last_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email:</th>
                                            <td>
                                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Role:</th>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'recruiter' ? 'info' : 'primary') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Status:</th>
                                            <td>
                                                @if ($user->email_verified_at)
                                                    <span class="badge bg-success">
                                                        <i class="ri-checkbox-circle-line me-1"></i>Verified
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="ri-time-line me-1"></i>Unverified
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email Verified At:</th>
                                            <td>
                                                @if ($user->email_verified_at)
                                                    {{ $user->email_verified_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">Not verified</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Created At:</th>
                                            <td>{{ $user->created_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Last Updated:</th>
                                            <td>{{ $user->updated_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Card -->
            @include('admin.components.actionCard', [
                'editRoute' => 'admin.users.edit',
                'deleteRoute' => 'admin.users.destroy',
                'indexRoute' => 'admin.users.index',
                'itemId' => $user->id,
                'entityName' => 'user',
                'itemData' => [
                    'User' => $user->first_name . ' ' . $user->last_name,
                    'Email' => $user->email
                ],
                'layout' => 'flex',
                'editButtonText' => 'Edit User',
                'deleteButtonText' => 'Delete User',
                'backButtonText' => 'View All Users',
                'modalId' => 'deleteUserModal'
            ])
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endpush
