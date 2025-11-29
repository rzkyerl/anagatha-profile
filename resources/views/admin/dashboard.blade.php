@extends('admin.admin_layouts.app')


@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
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

        <div class="col-xl-3 col-md-6">
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

        <div class="col-xl-3 col-md-6">
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

        <div class="col-xl-3 col-md-6">
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
    </div>
    <!-- end row -->

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="float-end d-none d-md-inline-block">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="text-muted">Report<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Export</a>
                                <a class="dropdown-item" href="#">Import</a>
                                <a class="dropdown-item" href="#">Download Report</a>
                            </div>
                        </div>
                    </div>
                    <h4 class="card-title mb-4">User Growth</h4>

                    <div class="text-center pt-3">
                        <div class="row">
                            <div class="col-sm-4 mb-3 mb-sm-0">
                                <div class="d-inline-flex">
                                    <h5 class="me-2">{{ number_format($totalUsers ?? 0) }}</h5>
                                    <div class="text-success font-size-12">
                                        <i class="mdi mdi-menu-up font-size-14"></i>
                                        Total
                                    </div>
                                </div>
                                <p class="text-muted text-truncate mb-0">Users</p>
                            </div>
                            <div class="col-sm-4 mb-3 mb-sm-0">
                                <div class="d-inline-flex">
                                    <h5 class="me-2">{{ number_format($verifiedUsers ?? 0) }}</h5>
                                    <div class="text-success font-size-12">
                                        <i class="mdi mdi-menu-up font-size-14"></i>
                                        Verified
                                    </div>
                                </div>
                                <p class="text-muted text-truncate mb-0">Verified</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="d-inline-flex">
                                    <h5 class="me-2">{{ number_format($unverifiedUsers ?? 0) }}</h5>
                                    <div class="text-warning font-size-12">
                                        <i class="mdi mdi-menu-down font-size-14"></i>
                                        Unverified
                                    </div>
                                </div>
                                <p class="text-muted text-truncate mb-0">Unverified</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0 px-2">
                    <div id="area_chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="float-end d-none d-md-inline-block">
                        <div class="dropdown">
                            <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="text-muted">This Year<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Last Week</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                                <a class="dropdown-item" href="#">This Year</a>
                            </div>
                        </div>
                    </div>
                    <h4 class="card-title mb-4">Job Listings Growth</h4>

                    <div class="text-center pt-3">
                        <div class="row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <div>
                                    <h5>{{ number_format($totalJobListings ?? 0) }}</h5>
                                    <p class="text-muted text-truncate mb-0">Total Listings</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div>
                                    <h5>{{ number_format($activeJobListings ?? 0) }}</h5>
                                    <p class="text-muted text-truncate mb-0">Active Listings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0 px-2">
                    <div id="column_line_chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <!-- Recent Activity Row -->
    <div class="row">
        <div class="col-xl-8">
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

                    <h4 class="card-title mb-4">Recent Users</h4>

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
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
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

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <select class="form-select shadow-none form-select-sm" id="month-select">
                            <option value="current" selected>{{ now()->setTimezone('Asia/Jakarta')->format('M') }}</option>
                            <option value="last">Last Month</option>
                        </select>
                    </div>
                    <h4 class="card-title mb-4">User Distribution</h4>

                    <div class="row">
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <h5>{{ number_format($adminCount ?? 0) }}</h5>
                                <p class="mb-2 text-truncate">Admins</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <h5>{{ number_format($userCount ?? 0) }}</h5>
                                <p class="mb-2 text-truncate">Users</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center mt-4">
                                <h5>{{ number_format($recruiterCount ?? 0) }}</h5>
                                <p class="mb-2 text-truncate">Recruiters</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div id="donut-chart" class="apex-charts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@endsection

@push('scripts')
    <script>
        // Area Chart - User Growth Over Time
        var areaChartOptions = {
            series: [{
                name: 'New Users',
                data: @json($userGrowthSeries ?? [])
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            colors: ['#556ee6'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: @json($userGrowthCategories ?? [])
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " users"
                    }
                }
            },
            markers: {
                size: 4,
                hover: {
                    size: 6
                }
            }
        };

        var areaChart = new ApexCharts(document.querySelector("#area_chart"), areaChartOptions);
        areaChart.render();

        // Column Line Chart - Job Listings Growth
        var columnLineChartOptions = {
            series: [{
                name: 'Job Listings',
                type: 'column',
                data: @json($jobListingsGrowthSeries ?? [])
            }],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            stroke: {
                width: [0, 4]
            },
            colors: ['#34c38f'],
            dataLabels: {
                enabled: true,
                enabledOnSeries: [0]
            },
            xaxis: {
                categories: @json($jobListingsGrowthCategories ?? []),
                type: 'category'
            },
            yaxis: [{
                title: {
                    text: 'Count',
                }
            }],
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " listings"
                    }
                }
            },
            markers: {
                size: 4,
                hover: {
                    size: 6
                }
            }
        };

        var columnLineChart = new ApexCharts(document.querySelector("#column_line_chart"), columnLineChartOptions);
        columnLineChart.render();

        // Donut Chart
        var donutChartOptions = {
            series: [{{ $adminCount ?? 0 }}, {{ $userCount ?? 0 }}, {{ $recruiterCount ?? 0 }}],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: ['Admins', 'Users', 'Recruiters'],
            colors: ['#f46a6a', '#556ee6', '#34c38f'],
            legend: {
                show: true,
                position: 'bottom'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + "%"
                }
            }
        };

        var donutChart = new ApexCharts(document.querySelector("#donut-chart"), donutChartOptions);
        donutChart.render();
    </script>
@endpush
