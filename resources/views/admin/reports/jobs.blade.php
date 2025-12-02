@extends('admin.admin_layouts.app')

@section('title', 'Job Reports')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.reports.index') }}">Reports</a>
    </li>
    <li class="breadcrumb-item active">Job Reports</li>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    <i class="ri-arrow-left-line"></i> Back to Overview
                </a>
                <a href="{{ route('admin.reports.export.jobs', ['period' => $period]) }}" class="btn btn-success">
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
                    <form method="GET" action="{{ route('admin.reports.jobs') }}" class="d-flex align-items-center gap-3">
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

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Listings Growth ({{ $period }} Months)</h6>
                </div>
                <div class="card-body">
                    @if(isset($jobListingsGrowthSeries) && count($jobListingsGrowthSeries) > 0)
                        <div class="chart-area" style="position: relative; height: 300px;">
                            <canvas id="jobListingsGrowthChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-line-chart-line" style="font-size: 3rem; color: #d3d3d3;"></i>
                            <p class="text-muted mt-3">No job listings data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Applications Growth ({{ $period }} Months)</h6>
                </div>
                <div class="card-body">
                    @if(isset($jobApplicationsGrowthSeries) && count($jobApplicationsGrowthSeries) > 0)
                        <div class="chart-area" style="position: relative; height: 300px;">
                            <canvas id="jobApplicationsGrowthChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-line-chart-line" style="font-size: 3rem; color: #d3d3d3;"></i>
                            <p class="text-muted mt-3">No job applications data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status Distribution -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Status Distribution</h6>
                </div>
                <div class="card-body">
                    @php
                        $jobStatusColors = [
                            'draft' => 'secondary',
                            'active' => 'success',
                            'inactive' => 'warning',
                            'closed' => 'danger'
                        ];
                        $totalJobs = array_sum($jobStatusStats->toArray());
                    @endphp
                    @foreach($jobStatusStats ?? [] as $status => $count)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">{{ ucfirst($status) }}</span>
                                <span class="font-weight-bold">{{ number_format($count) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $jobStatusColors[$status] ?? 'secondary' }}" role="progressbar" 
                                    style="width: {{ $totalJobs > 0 ? ($count / $totalJobs) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(empty($jobStatusStats))
                        <p class="text-muted text-center">No job status data available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Application Status Distribution</h6>
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
                        $totalApplications = array_sum($applicationStatusStats->toArray());
                    @endphp
                    @foreach($applicationStatusStats ?? [] as $status => $count)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">{{ ucfirst($status) }}</span>
                                <span class="font-weight-bold">{{ number_format($count) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ $statusColors[$status] ?? 'secondary' }}" role="progressbar" 
                                    style="width: {{ $totalApplications > 0 ? ($count / $totalApplications) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if(empty($applicationStatusStats))
                        <p class="text-muted text-center">No application status data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Top Jobs by Applications -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Jobs by Applications</h6>
                </div>
                <div class="card-body">
                    @if($topJobs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Applications</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topJobs as $job)
                                        <tr>
                                            <td><strong>{{ $job->title }}</strong></td>
                                            <td>{{ $job->company }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $job->job_applies_count }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.job-listings.show', $job->id) }}" 
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
                        <p class="text-muted text-center">No job data available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Jobs by Location</h6>
                </div>
                <div class="card-body">
                    @if($jobsByLocation->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobsByLocation as $location)
                                        <tr>
                                            <td><strong>{{ $location->location }}</strong></td>
                                            <td>
                                                <span class="badge bg-primary">{{ number_format($location->count) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No location data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Jobs by Industry -->
    @if($jobsByIndustry->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Jobs by Industry</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Industry</th>
                                    <th>Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobsByIndustry as $industry)
                                    <tr>
                                        <td><strong>{{ $industry->industry }}</strong></td>
                                        <td>
                                            <span class="badge bg-success">{{ number_format($industry->count) }}</span>
                                        </td>
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
@endsection

@push('scripts')
@if(isset($jobListingsGrowthSeries) && count($jobListingsGrowthSeries) > 0)
<script>
    var ctxJobListings = document.getElementById("jobListingsGrowthChart");
    if (ctxJobListings) {
        var jobListingsChart = new Chart(ctxJobListings, {
            type: 'line',
            data: {
                labels: @json($jobListingsGrowthCategories ?? []),
                datasets: [{
                    label: 'Job Listings',
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

@if(isset($jobApplicationsGrowthSeries) && count($jobApplicationsGrowthSeries) > 0)
<script>
    var ctxJobApplications = document.getElementById("jobApplicationsGrowthChart");
    if (ctxJobApplications) {
        var jobApplicationsChart = new Chart(ctxJobApplications, {
            type: 'line',
            data: {
                labels: @json($jobApplicationsGrowthCategories ?? []),
                datasets: [{
                    label: 'Job Applications',
                    data: @json($jobApplicationsGrowthSeries ?? []),
                    borderColor: 'rgb(255, 193, 7)',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
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
