@extends('admin.admin_layouts.app')

@section('title', 'Users Management')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">
        Users
    </li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title mb-1">
                                @if(isset($showTrashed) && $showTrashed)
                                    Deleted Users
                                @else
                                    User Management
                                @endif
                            </h4>
                            <p class="card-title-desc mb-0">
                                @if(isset($showTrashed) && $showTrashed)
                                    View and restore deleted users
                                @else
                                    Manage all users in the system
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            @if(isset($showTrashed) && $showTrashed)
                                <a href="{{ route('admin.users.export', ['trashed' => '1']) }}" class="btn btn-success">
                                    <i class="ri-download-line align-middle me-1"></i> Export Deleted
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Active
                                </a>
                            @else
                                <a href="{{ route('admin.users.export') }}" class="btn btn-success">
                                    <i class="ri-download-line align-middle me-1"></i> Export
                                </a>
                                <a href="{{ route('admin.users.index', ['trashed' => '1']) }}" class="btn btn-warning">
                                    <i class="ri-delete-bin-line align-middle me-1"></i> View Deleted
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line align-middle me-1"></i> Add New User
                                </a>
                            @endif
                        </div>
                    </div>

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

                    <div class="table-responsive">
                    <table id="datatable" 
                           class="table table-striped table-bordered dt-responsive nowrap"
                           data-datatable-config='{"pageLength": 10, "orderColumn": 4, "orderDirection": "desc", "searchPlaceholder": "Search users..."}'>
                        <thead>
                            <tr>
                                <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>@if(isset($showTrashed) && $showTrashed) Deleted At @else Created At @endif</th>
                                    <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                                @forelse($users ?? [] as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title bg-primary rounded-circle font-size-12">
                                                    {{ strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? 'S', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : ($user->role === 'recruiter' ? 'warning' : 'info') }}">
                                            {{ ucfirst($user->role ?? 'user') }}
                                        </span>
                                    </td>
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
                                    <td>
                                        @if(isset($showTrashed) && $showTrashed)
                                            <small class="text-muted">Deleted: {{ $user->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i') }}</small>
                                        @else
                                            {{ $user->created_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if(isset($showTrashed) && $showTrashed)
                                                @include('admin.components.restoreModal', [
                                                    'modalId' => 'restoreUserModal' . $user->id,
                                                    'route' => 'admin.users.restore',
                                                    'itemId' => $user->id,
                                                    'entityName' => 'user',
                                                    'itemData' => [
                                                        'Name' => $user->first_name . ' ' . $user->last_name,
                                                        'Email' => $user->email,
                                                        'Role' => ucfirst($user->role),
                                                        'Deleted At' => $user->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i')
                                                    ],
                                                    'buttonClass' => 'btn btn-sm btn-success',
                                                    'buttonIcon' => 'ri-restart-line'
                                                ])
                                                @include('admin.components.forceDeleteModal', [
                                                    'modalId' => 'forceDeleteUserModal' . $user->id,
                                                    'route' => 'admin.users.force-delete',
                                                    'itemId' => $user->id,
                                                    'entityName' => 'user',
                                                    'itemData' => [
                                                        'Name' => $user->first_name . ' ' . $user->last_name,
                                                        'Email' => $user->email,
                                                        'Role' => ucfirst($user->role),
                                                        'Deleted At' => $user->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i')
                                                    ],
                                                    'buttonClass' => 'btn btn-sm btn-danger',
                                                    'buttonIcon' => 'ri-delete-bin-7-line'
                                                ])
                                            @else
                                            <a href="{{ route('admin.users.show', $user->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-sm btn-warning" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            @include('admin.components.deleteModal', [
                                                'modalId' => 'deleteUserModal' . $user->id,
                                                'route' => 'admin.users.destroy',
                                                'itemId' => $user->id,
                                                'entityName' => 'user',
                                                'itemData' => [
                                                        'Name' => $user->first_name . ' ' . $user->last_name,
                                                        'Email' => $user->email,
                                                        'Role' => ucfirst($user->role)
                                                    ],
                                                    'buttonClass' => 'btn btn-sm btn-danger',
                                                    'buttonIcon' => 'ri-delete-bin-line'
                                            ])
                                            @endif
                                        </div>
                                    </td>
                            </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No users found</td>
                            </tr>
                                @endforelse
                        </tbody>
                    </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

