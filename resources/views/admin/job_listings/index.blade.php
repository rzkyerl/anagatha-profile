@extends('admin.admin_layouts.app')

@section('title', 'Job Listings')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">
        Job Listings
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
                                    Deleted Job Listings
                                @else
                                    Job Listings Management
                                @endif
                            </h4>
                            <p class="card-title-desc mb-0">
                                @if(isset($showTrashed) && $showTrashed)
                                    View and restore deleted job listings
                                @else
                                    Manage all job listings in the system
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            @if(isset($showTrashed) && $showTrashed)
                                <a href="{{ route('admin.job-listings.export', ['trashed' => '1']) }}" class="btn btn-success">
                                    <i class="ri-download-line align-middle me-1"></i> Export Deleted
                                </a>
                                <a href="{{ route('admin.job-listings.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line align-middle me-1"></i> Back to Active
                                </a>
                            @else
                                <a href="{{ route('admin.job-listings.export') }}" class="btn btn-success">
                                    <i class="ri-download-line align-middle me-1"></i> Export
                                </a>
                                <a href="{{ route('admin.job-listings.index', ['trashed' => '1']) }}" class="btn btn-warning">
                                    <i class="ri-delete-bin-line align-middle me-1"></i> View Deleted
                                </a>
                                <a href="{{ route('admin.job-listings.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line align-middle me-1"></i> Add New Job Listing
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
                           data-datatable-config='{"pageLength": 10, "orderColumn": 4, "orderDirection": "desc", "searchPlaceholder": "Search job listings..."}'>

                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>@if(isset($showTrashed) && $showTrashed) Deleted At @else Created At @endif</th>
                                    <th style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobListings ?? [] as $listing)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($listing->company_logo)
                                                <img src="{{ $listing->company_logo }}" 
                                                     alt="{{ $listing->company }}" 
                                                     class="rounded me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="avatar-xs me-2">
                                                    <span class="avatar-title bg-primary rounded-circle font-size-12">
                                                        {{ strtoupper(substr($listing->company, 0, 2)) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $listing->title }}</h6>
                                                <small class="text-muted">{{ $listing->contract_type }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $listing->company }}</td>
                                    <td>
                                        <i class="ri-map-pin-line me-1"></i>{{ $listing->location }}
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'secondary',
                                                'active' => 'success',
                                                'inactive' => 'warning',
                                                'closed' => 'danger'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$listing->status] ?? 'secondary' }}">
                                            {{ ucfirst($listing->status) }}
                                        </span>
                                        @if($listing->verified)
                                            <span class="badge bg-info ms-1" data-bs-toggle="tooltip" title="Verified">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($showTrashed) && $showTrashed)
                                            <small class="text-muted">Deleted: {{ $listing->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i') }}</small>
                                        @else
                                            {{ $listing->created_at->setTimezone('Asia/Jakarta')->format('d M, Y') }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if(isset($showTrashed) && $showTrashed)
                                                @include('admin.components.restoreModal', [
                                                    'modalId' => 'restoreJobListingModal' . $listing->id,
                                                    'route' => 'admin.job-listings.restore',
                                                    'itemId' => $listing->id,
                                                    'entityName' => 'job listing',
                                                    'itemData' => [
                                                        'Title' => $listing->title,
                                                        'Company' => $listing->company,
                                                        'Location' => $listing->location,
                                                        'Deleted At' => $listing->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i')
                                                    ],
                                                    'buttonClass' => 'btn btn-sm btn-success',
                                                    'buttonIcon' => 'ri-restart-line'
                                                ])
                                                @include('admin.components.forceDeleteModal', [
                                                    'modalId' => 'forceDeleteJobListingModal' . $listing->id,
                                                    'route' => 'admin.job-listings.force-delete',
                                                    'itemId' => $listing->id,
                                                    'entityName' => 'job listing',
                                                    'itemData' => [
                                                        'Title' => $listing->title,
                                                        'Company' => $listing->company,
                                                        'Location' => $listing->location,
                                                        'Deleted At' => $listing->deleted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i')
                                                    ],
                                                    'buttonClass' => 'btn btn-sm btn-danger',
                                                    'buttonIcon' => 'ri-delete-bin-7-line'
                                                ])
                                            @else
                                            <a href="{{ route('admin.job-listings.show', $listing->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="View">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ route('admin.job-listings.edit', $listing->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="top" 
                                               title="Edit">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            @include('admin.components.deleteModal', [
                                                    'modalId' => 'deleteJobListingModal' . $listing->id,
                                                    'route' => 'admin.job-listings.destroy',
                                                    'itemId' => $listing->id,
                                                    'entityName' => 'job listing',
                                                'itemData' => [
                                                        'Title' => $listing->title,
                                                        'Company' => $listing->company,
                                                        'Location' => $listing->location
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
                                    <td colspan="6" class="text-center">No job listings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

