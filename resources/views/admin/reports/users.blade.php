@extends('admin.admin_layouts.app')

@section('title', 'User Reports')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.reports.index') }}">Reports</a>
    </li>
    <li class="breadcrumb-item active">User Reports</li>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to Overview
                </a>
                <a href="{{ route('admin.reports.export.users', ['period' => $period]) }}" class="btn btn-success">
                    <i class="ri-download-line"></i> Export to CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Period Filter -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.users') }}" class="d-flex align-items-center gap-3">
                        <label class="mb-0">Period:</label>
                        <select name="period" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                            <option value="3" {{ $period == '3' ? 'selected' : '' }}>Last 3 Months</option>
                            <option value="6" {{ $period == '6' ? 'selected' : '' }}>Last 6 Months</option>
                            <option value="12" {{ $period == '12' ? 'selected' : '' }}>Last 12 Months</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- User Growth Chart -->
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Growth ({{ $period }} Months)</h6>
                </div>
                <div class="card-body">
                    @if(isset($userGrowthSeries) && count($userGrowthSeries) > 0)
                        <div class="chart-area" style="position: relative; height: 300px;">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-line-chart-line" style="font-size: 3rem; color: #d3d3d3;"></i>
                            <p class="text-muted mt-3">No user data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Distribution -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Distribution</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Admins</span>
                            <span class="font-weight-bold">{{ number_format($usersByRole['admin'] ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-danger" role="progressbar" 
                                style="width: {{ ($usersByRole['admin'] ?? 0) / max(array_sum($usersByRole->toArray()), 1) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Recruiters</span>
                            <span class="font-weight-bold">{{ number_format($usersByRole['recruiter'] ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" role="progressbar" 
                                style="width: {{ ($usersByRole['recruiter'] ?? 0) / max(array_sum($usersByRole->toArray()), 1) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Employees</span>
                            <span class="font-weight-bold">{{ number_format($usersByRole['user'] ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                style="width: {{ ($usersByRole['user'] ?? 0) / max(array_sum($usersByRole->toArray()), 1) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Verified</span>
                            <span class="font-weight-bold">{{ number_format($verifiedUsers ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ ($verifiedUsers ?? 0) / max(($verifiedUsers ?? 0) + ($unverifiedUsers ?? 0), 1) * 100 }}%">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Unverified</span>
                            <span class="font-weight-bold">{{ number_format($unverifiedUsers ?? 0) }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" 
                                style="width: {{ ($unverifiedUsers ?? 0) / max(($verifiedUsers ?? 0) + ($unverifiedUsers ?? 0), 1) * 100 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users by Month Breakdown -->
    @if(isset($usersByMonth) && count($usersByMonth) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Users by Month ({{ $period }} Months)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Admins</th>
                                    <th>Recruiters</th>
                                    <th>Employees</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usersByMonth as $monthData)
                                    <tr>
                                        <td><strong>{{ $monthData['month'] }}</strong></td>
                                        <td>{{ number_format($monthData['admin']) }}</td>
                                        <td>{{ number_format($monthData['recruiter']) }}</td>
                                        <td>{{ number_format($monthData['user']) }}</td>
                                        <td><strong>{{ number_format($monthData['admin'] + $monthData['recruiter'] + $monthData['user']) }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recent Users -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Registrations</h6>
                </div>
                <div class="card-body">
                    @if($recentUsers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Registered</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr>
                                            <td><strong>{{ $user->first_name }} {{ $user->last_name }}</strong></td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'recruiter' ? 'info' : 'primary') }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <span class="badge bg-warning">Unverified</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->setTimezone('Asia/Jakarta')->format('d M, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $user->id) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No users found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@if(isset($userGrowthSeries) && count($userGrowthSeries) > 0)
<script>
    var ctxUserGrowth = document.getElementById("userGrowthChart");
    if (ctxUserGrowth) {
        var userGrowthChart = new Chart(ctxUserGrowth, {
            type: 'line',
            data: {
                labels: @json($userGrowthCategories ?? []),
                datasets: [{
                    label: 'New Users',
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
@endpush
