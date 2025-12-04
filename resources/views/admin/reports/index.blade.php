@extends('admin.admin_layouts.app')

@section('title', 'Reports Overview')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">Reports Overview</li>
@endsection

@section('content')
    <!-- Export Button -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.reports.export.overview') }}" class="btn btn-success">
                    <i class="ri-download-line"></i> Export to CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Users</p>
                            <h4 class="mb-2">{{ number_format($totalUsers ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success fw-bold font-size-12 me-2">
                                    <i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    {{ number_format($newUsersToday ?? 0) }} today
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded-3">
                                <i class="ri-user-3-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Job Listings</p>
                            <h4 class="mb-2">{{ number_format($totalJobListings ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-info fw-bold font-size-12 me-2">
                                    <i class="ri-file-list-line me-1 align-middle"></i>
                                    {{ number_format($activeJobListings ?? 0) }} active
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-info rounded-3">
                                <i class="ri-briefcase-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Job Applications</p>
                            <h4 class="mb-2">{{ number_format($totalJobApplications ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-warning fw-bold font-size-12 me-2">
                                    <i class="ri-file-paper-line me-1 align-middle"></i>
                                    {{ number_format($newApplicationsToday ?? 0) }} today
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-warning rounded-3">
                                <i class="ri-file-add-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Recruiters</p>
                            <h4 class="mb-2">{{ number_format($totalRecruiters ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success fw-bold font-size-12 me-2">
                                    <i class="ri-user-star-line me-1 align-middle"></i>
                                    Active
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-success rounded-3">
                                <i class="ri-user-settings-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Companies</p>
                            <h4 class="mb-2">{{ number_format($totalCompanies ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-info fw-bold font-size-12 me-2">
                                    <i class="ri-building-line me-1 align-middle"></i>
                                    Registered
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-info rounded-3">
                                <i class="ri-building-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- This Month Statistics -->
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">This Month</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">New Users</span>
                            <span class="font-weight-bold">{{ number_format($newUsersThisMonth ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                style="width: {{ $totalUsers > 0 ? ($newUsersThisMonth / $totalUsers) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">New Job Listings</span>
                            <span class="font-weight-bold">{{ number_format($newJobListingsThisMonth ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $totalJobListings > 0 ? ($newJobListingsThisMonth / $totalJobListings) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">New Applications</span>
                            <span class="font-weight-bold">{{ number_format($newApplicationsThisMonth ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                style="width: {{ $totalJobApplications > 0 ? ($newApplicationsThisMonth / $totalJobApplications) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Application Status</h6>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'shortlisted' => 'info',
                            'interview' => 'primary',
                            'hired' => 'success',
                            'rejected' => 'danger'
                        ];
                    @endphp
                    @foreach($applicationStatusStats ?? [] as $status => $count)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">{{ ucfirst($status) }}</span>
                                <span class="font-weight-bold">{{ number_format($count) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $statusColors[$status] ?? 'secondary' }}" role="progressbar" 
                                    style="width: {{ $totalJobApplications > 0 ? ($count / $totalJobApplications) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(empty($applicationStatusStats))
                        <p class="text-muted text-center">No application data available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Status</h6>
                </div>
                <div class="card-body">
                    @php
                        $jobStatusColors = [
                            'draft' => 'secondary',
                            'active' => 'success',
                            'inactive' => 'warning',
                            'closed' => 'danger'
                        ];
                    @endphp
                    @foreach($jobStatusStats ?? [] as $status => $count)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">{{ ucfirst($status) }}</span>
                                <span class="font-weight-bold">{{ number_format($count) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $jobStatusColors[$status] ?? 'secondary' }}" role="progressbar" 
                                    style="width: {{ $totalJobListings > 0 ? ($count / $totalJobListings) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(empty($jobStatusStats))
                        <p class="text-muted text-center">No job data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detailed Reports</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="font-weight-bold text-primary mb-1">User Reports</h6>
                                            <p class="text-muted small mb-0">View detailed user analytics and statistics</p>
                                        </div>
                                        <a href="{{ route('admin.reports.users') }}" class="btn btn-sm btn-primary">
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-success h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="font-weight-bold text-success mb-1">Job Reports</h6>
                                            <p class="text-muted small mb-0">View job listings and applications analytics</p>
                                        </div>
                                        <a href="{{ route('admin.reports.jobs') }}" class="btn btn-sm btn-success">
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h6 class="font-weight-bold text-info mb-1">Company Reports</h6>
                                            <p class="text-muted small mb-0">View company analytics and statistics</p>
                                        </div>
                                        <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-info">
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
