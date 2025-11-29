@extends('admin.admin_layouts.app')

@section('title', 'Job Applications Management')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">
        Job Applications
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
                                    Deleted Job Applications
                                @else
                                    Job Applications Management
                                @endif
                            </h4>
                            <p class="card-title-desc mb-0">
                                @if(isset($showTrashed) && $showTrashed)
                                    View and restore deleted job applications
                                @else
                                    Manage all job applications in the system
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            @if(isset($showTrashed) && $showTrashed)
                                <a href="{{ route('admin.job-apply.export', ['trashed' => '1']) }}" class="btn btn-success">
                                    <i class="ri-download-line align-middle me-1"></i> Export Deleted
                                </a>
                                <a href="{{ route('admin.job-apply.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Active
                                </a>
                            @else
                                <a href="{{ route('admin.job-apply.export') }}" class="btn btn-success">
                                    <i class="ri-download-line align-middle me-1"></i> Export
                                </a>
                                <a href="{{ route('admin.job-apply.index', ['trashed' => '1']) }}" class="btn btn-warning">
                                    <i class="ri-delete-bin-line align-middle me-1"></i> View Deleted
                                </a>
                                <a href="{{ route('admin.job-apply.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line align-middle me-1"></i> Add New Application
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
                           data-datatable-config='{"pageLength": 10, "orderColumn": 5, "orderDirection": "desc", "searchPlaceholder": "Search job applications..."}'>
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Job Position</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>@if(isset($showTrashed) && $showTrashed) Deleted At @else Applied Date @endif</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobApplies ?? [] as $apply)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-xs me-2">
                                                <span class="avatar-title bg-primary rounded-circle font-size-12">
                                                    {{ strtoupper(substr($apply->full_name ?? 'A', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $apply->full_name }}</h6>
                                                @if($apply->user)
                                                    <small class="text-muted">{{ $apply->user->email }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($apply->jobListing)
                                            <strong>{{ $apply->jobListing->title }}</strong><br>
                                            <small class="text-muted">{{ $apply->jobListing->company }}</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $apply->email }}">{{ $apply->email }}</a>
                                    </td>
                                    <td>{{ $apply->phone }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'shortlisted' => 'info',
                                                'interview' => 'primary',
                                                'hired' => 'success',
                                                'rejected' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$apply->status] ?? 'secondary' }}">
                                            {{ ucfirst($apply->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(isset($showTrashed) && $showTrashed)
                                            <small class="text-muted">Deleted: {{ $apply->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i') }}</small>
                                        @else
                                            {{ $apply->applied_at ? \Carbon\Carbon::parse($apply->applied_at)->setTimezone('Asia/Jakarta')->format('d M, Y') : $apply->created_at->setTimezone('Asia/Jakarta')->format('d M, Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if(isset($showTrashed) && $showTrashed)
                                                @include('admin.components.restoreModal', [
                                                    'modalId' => 'restoreJobApplyModal' . $apply->id,
                                                    'route' => 'admin.job-apply.restore',
                                                    'itemId' => $apply->id,
                                                    'entityName' => 'job application',
                                                    'itemData' => [
                                                        'Applicant' => $apply->full_name,
                                                        'Email' => $apply->email,
                                                        'Job Position' => $apply->jobListing ? $apply->jobListing->title : 'N/A',
                                                        'Deleted At' => $apply->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i')
                                                    ],
                                                    'buttonClass' => 'btn btn-sm btn-success',
                                                    'buttonIcon' => 'ri-restart-line'
                                                ])
                                                @include('admin.components.forceDeleteModal', [
                                                    'modalId' => 'forceDeleteJobApplyModal' . $apply->id,
                                                    'route' => 'admin.job-apply.force-delete',
                                                    'itemId' => $apply->id,
                                                    'entityName' => 'job application',
                                                    'itemData' => [
                                                        'Applicant' => $apply->full_name,
                                                        'Email' => $apply->email,
                                                        'Job Position' => $apply->jobListing ? $apply->jobListing->title : 'N/A',
                                                        'Deleted At' => $apply->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i')
                                                    ],
                                                    'buttonClass' => 'btn btn-sm btn-danger',
                                                    'buttonIcon' => 'ri-delete-bin-7-line'
                                                ])
                                            @else
                                                <a href="{{ route('admin.job-apply.show', $apply->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.job-apply.edit', $apply->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Edit">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                @include('admin.components.deleteModal', [
                                                    'modalId' => 'deleteJobApplyModal' . $apply->id,
                                                    'route' => 'admin.job-apply.destroy',
                                                    'itemId' => $apply->id,
                                                    'entityName' => 'job application',
                                                    'itemData' => [
                                                        'Applicant' => $apply->full_name,
                                                        'Email' => $apply->email,
                                                        'Job Position' => $apply->jobListing ? $apply->jobListing->title : 'N/A'
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
                                    <td colspan="7" class="text-center">No job applications found</td>
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


