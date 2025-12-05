@extends('admin.admin_layouts.app')

@section('title', $title ?? 'Dashboard')

@php
    $isRecruiter = auth()->user()->role === 'recruiter';
@endphp

@section('content')
    <!-- Statistics Cards - 3x3 Layout -->
    <div class="row">
        @if(!$isRecruiter)
        {{-- Row 1 --}}
        {{-- Admin: Total Users Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Users</p>
                            <h4 class="mb-2">{{ number_format($totalUsers ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success fw-bold font-size-12 me-2">
                                    <i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    {{ $newUsersToday ?? 0 }} today
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

        {{-- Admin: Job Listings Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
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

        {{-- Admin: Job Applications Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Job Applications</p>
                            <h4 class="mb-2">{{ number_format($totalJobApplications ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-warning fw-bold font-size-12 me-2">
                                    <i class="ri-file-paper-line me-1 align-middle"></i>
                                    Pending review
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

        {{-- Row 2 --}}
        {{-- Admin: Recruiters Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Recruiters</p>
                            <h4 class="mb-2">{{ number_format($totalRecruiters ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success fw-bold font-size-12 me-2">
                                    <i class="ri-user-star-line me-1 align-middle"></i>
                                    Active recruiters
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

        {{-- Admin: Companies Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Companies</p>
                            <h4 class="mb-2">{{ number_format($totalCompanies ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-info fw-bold font-size-12 me-2">
                                    <i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    {{ $newCompaniesToday ?? 0 }} today
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

        {{-- Row 3 --}}
        {{-- Admin: Job Seekers Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Job Seekers</p>
                            <h4 class="mb-2">{{ number_format($totalJobSeekers ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-primary fw-bold font-size-12 me-2">
                                    <i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    {{ $newJobSeekersToday ?? 0 }} today
                                </span>
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-light text-primary rounded-3">
                                <i class="ri-user-search-line font-size-24"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        {{-- Recruiter: Total Applicants Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Applicants</p>
                            <h4 class="mb-2">{{ number_format($totalUsers ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success fw-bold font-size-12 me-2">
                                    <i class="ri-arrow-right-up-line me-1 align-middle"></i>
                                    {{ $newUsersToday ?? 0 }} new today
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

        {{-- Recruiter: My Job Listings Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">My Job Listings</p>
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

        {{-- Recruiter: My Job Applications Card --}}
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">My Job Applications</p>
                            <h4 class="mb-2">{{ number_format($totalJobApplications ?? 0) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-warning fw-bold font-size-12 me-2">
                                    <i class="ri-file-paper-line me-1 align-middle"></i>
                                    Pending review
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
        @endif
    </div>
    <!-- end row -->

    <!-- Chart dan Quick Stats -->
    <div class="row">
        @if(!$isRecruiter)
        {{-- Admin: User Growth Chart --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">User Growth (6 Month Ago)</h6>
                </div>
                <div class="card-body">
                    @if(isset($userGrowthSeries) && count($userGrowthSeries) > 0)
                        <div class="chart-area" style="position: relative; height: 300px;">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-line-chart-line" style="font-size: 3rem; color: #d3d3d3;"></i>
                            <p class="text-muted mt-3">Belum ada data user untuk ditampilkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Admin: Quick Stats --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Verified Users</span>
                            <span class="font-weight-bold">
                                {{ $totalUsers > 0 ? number_format(($verifiedUsers / $totalUsers) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                style="width: {{ $totalUsers > 0 ? ($verifiedUsers / $totalUsers) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Active Job Listings</span>
                            <span class="font-weight-bold">
                                {{ $totalJobListings > 0 ? number_format(($activeJobListings / $totalJobListings) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $totalJobListings > 0 ? ($activeJobListings / $totalJobListings) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">New Users Today</span>
                            <span class="font-weight-bold">
                                {{ number_format($newUsersToday ?? 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Job Listings Growth Chart --}}
        <div class="{{ $isRecruiter ? 'col-xl-8 col-lg-7' : 'col-xl-12' }}">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @if($isRecruiter) My Job Listings Growth (6 Month Ago) @else Job Listings Growth (6 Month Ago) @endif
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($jobListingsGrowthSeries) && count($jobListingsGrowthSeries) > 0)
                        <div class="chart-area" style="position: relative; height: 300px;">
                            <canvas id="jobListingsGrowthChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-line-chart-line" style="font-size: 3rem; color: #d3d3d3;"></i>
                            <p class="text-muted mt-3">Belum ada data job listings untuk ditampilkan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($isRecruiter)
        {{-- Recruiter: Quick Stats --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Active Listings</span>
                            <span class="font-weight-bold">
                                {{ $totalJobListings > 0 ? number_format(($activeJobListings / $totalJobListings) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $totalJobListings > 0 ? ($activeJobListings / $totalJobListings) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Total Applicants</span>
                            <span class="font-weight-bold">
                                {{ number_format($totalJobApplications ?? 0) }}
                            </span>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">New Applications Today</span>
                            <span class="font-weight-bold">
                                {{ number_format($newUsersToday ?? 0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <!-- end row -->

    <!-- Recent Activity Row -->
    <div class="row">
        <div class="{{ $isRecruiter ? 'col-xl-12' : 'col-xl-8' }}">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown float-end">
                        <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="javascript:void(0);" class="dropdown-item">View All</a>
                            <a href="javascript:void(0);" class="dropdown-item">Export</a>
                            <a href="javascript:void(0);" class="dropdown-item">Refresh</a>
                        </div>
                    </div>

                    <h4 class="card-title mb-4">
                        @if($isRecruiter) Recent Applicants @else Recent Users @endif
                    </h4>

                    <div class="table-responsive">
                        <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Joined</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers ?? [] as $user)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'recruiter' ? 'info' : 'primary') }}">
                                                {{ ucfirst($user->role ?? 'user') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="font-size-13">
                                                @if ($user->email_verified_at)
                                                    <i
                                                        class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Verified
                                                @else
                                                    <i
                                                        class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>Unverified
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $user->created_at->setTimezone('Asia/Jakarta')->format('d M, Y') }}</td>
                                        <td>
                                            @if($isRecruiter)
                                                <a href="{{ route('admin.recruiter.job-apply.index', ['user_id' => $user->id]) }}"
                                                    class="btn btn-sm btn-primary" title="View Applications">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.users.show', $user->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            @if($isRecruiter) No applicants found @else No users found @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end row -->

    @if(!$isRecruiter)
    <!-- Recent Companies -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Companies</h6>
                    <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-primary">
                        <i class="ri-building-line me-1"></i> View All Companies
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Company Name</th>
                                    <th>Location</th>
                                    <th>Industry</th>
                                    <th>Recruiters</th>
                                    <th>Registered</th>
                                    <th style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCompanies ?? [] as $company)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($company->logo)
                                                    <img src="{{ route('company.logo', $company->logo) }}" 
                                                         alt="{{ $company->name }} Logo" 
                                                         class="rounded me-2" 
                                                         style="width: 32px; height: 32px; object-fit: contain; border: 1px solid #ddd;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 32px; height: 32px;">
                                                        <i class="ri-building-line text-muted"></i>
                                                    </div>
                                                @endif
                                                <h6 class="mb-0">{{ $company->name }}</h6>
                                            </div>
                                        </td>
                                        <td>
                                            @if($company->location)
                                                <i class="ri-map-pin-line me-1 text-muted"></i>{{ $company->location }}
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($company->industry)
                                                <span class="badge bg-info">{{ $company->industry }}</span>
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $recruiterCount = \App\Models\User::where('role', 'recruiter')
                                                    ->where(function ($query) use ($company) {
                                                        $query->whereHas('company', function ($q) use ($company) {
                                                            $q->where('name', $company->name);
                                                        })
                                                        ->orWhere('company_name', $company->name);
                                                    })
                                                    ->count();
                                            @endphp
                                            <span class="badge bg-primary">{{ $recruiterCount }}</span>
                                        </td>
                                        <td>{{ $company->created_at->setTimezone('Asia/Jakarta')->format('d M, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.companies.show', urlencode($company->name)) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No companies found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    @endif
@endsection

@push('scripts')
@if(isset($userGrowthSeries) && count($userGrowthSeries) > 0)
<script>
    // Chart User Growth (Chart.js v2 syntax) - Admin only
    var ctxUserGrowth = document.getElementById("userGrowthChart");
    if (ctxUserGrowth) {
        var userGrowthChart = new Chart(ctxUserGrowth, {
            type: 'line',
            data: {
                labels: @json($userGrowthCategories ?? []),
                datasets: [{
                    label: 'Jumlah User',
                    data: @json($userGrowthSeries ?? []),
                    borderColor: 'rgb(30, 136, 229)',
                    backgroundColor: 'rgba(30, 136, 229, 0.1)',
                    lineTension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        });
    }
</script>
@endif

@if(isset($jobListingsGrowthSeries) && count($jobListingsGrowthSeries) > 0)
<script>
    // Chart Job Listings Growth (Chart.js v2 syntax)
    var ctxJobListings = document.getElementById("jobListingsGrowthChart");
    if (ctxJobListings) {
        var jobListingsChart = new Chart(ctxJobListings, {
            type: 'line',
            data: {
                labels: @json($jobListingsGrowthCategories ?? []),
                datasets: [{
                    label: @if($isRecruiter) 'My Job Listings' @else 'Job Listings' @endif,
                    data: @json($jobListingsGrowthSeries ?? []),
                    borderColor: 'rgb(52, 195, 143)',
                    backgroundColor: 'rgba(52, 195, 143, 0.1)',
                    lineTension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                },
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        });
    }
</script>
@endif
@endpush
