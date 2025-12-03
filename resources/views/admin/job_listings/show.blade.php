@extends('admin.admin_layouts.app')

@section('title', 'Job Listing Details')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-listings.index') }}">Job Listings</a>
    </li>
    <li class="breadcrumb-item active">{{ $jobListing->title }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-10 mx-auto">
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

            <!-- Job Listing Header Card -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Job Listing Details</h4>
                            <p class="card-title-desc mb-0">View job listing information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.job-listings.edit', $jobListing->id) }}" class="btn btn-primary">
                                <i class="ri-pencil-line align-middle me-1"></i> Edit
                            </a>
                            <a href="{{ route('admin.job-listings.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Company Logo / Avatar -->
                        <div class="col-md-3 text-center mb-4 mb-md-0">
                            @if($jobListing->company_logo)
                                <img src="{{ route('company.logo', $jobListing->company_logo) }}" 
                                     alt="{{ $jobListing->company }}" 
                                     class="rounded mb-3" 
                                     style="width: 120px; height: 120px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/megamenu.png') }}';">
                            @else
                                <div class="avatar-xl mx-auto mb-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-24">
                                        {{ strtoupper(substr($jobListing->company, 0, 2)) }}
                                    </span>
                                </div>
                            @endif
                            <h5 class="mb-1">{{ $jobListing->title }}</h5>
                            <p class="text-muted mb-2">{{ $jobListing->company }}</p>
                            <div>
                                @php
                                    $statusColors = [
                                        'draft' => 'secondary',
                                        'active' => 'success',
                                        'inactive' => 'warning',
                                        'closed' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$jobListing->status] ?? 'secondary' }} me-1">
                                    {{ ucfirst($jobListing->status) }}
                                </span>
                                @if($jobListing->verified)
                                    <span class="badge bg-info">
                                        <i class="ri-checkbox-circle-line me-1"></i>Verified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Job Information -->
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 200px;">Job Title:</th>
                                            <td><strong>{{ $jobListing->title }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Company:</th>
                                            <td>{{ $jobListing->company_logo }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Location:</th>
                                            <td>
                                                <i class="ri-map-pin-line me-1"></i>{{ $jobListing->location }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Salary Display:</th>
                                            <td>
                                                @if($jobListing->salary_display && $jobListing->salary_display !== 'Not Disclose')
                                                    <span class="text-success fw-bold">{{ $jobListing->salary_display }}</span>
                                                @else
                                                    <span class="text-muted">Not Disclose</span>
                                                @endif
                                                @if($jobListing->salary_min && $jobListing->salary_max)
                                                    <small class="text-muted d-block">
                                                        (Min: {{ number_format($jobListing->salary_min, 2) }} - Max: {{ number_format($jobListing->salary_max, 2) }})
                                                    </small>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Work Preference:</th>
                                            <td>
                                                @php
                                                    $workPrefLabels = [
                                                        'wfo' => 'Work From Office',
                                                        'wfh' => 'Work From Home',
                                                        'hybrid' => 'Hybrid'
                                                    ];
                                                    $workPrefColors = [
                                                        'wfo' => 'primary',
                                                        'wfh' => 'info',
                                                        'hybrid' => 'success'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $workPrefColors[$jobListing->work_preference] ?? 'secondary' }}">
                                                    {{ $workPrefLabels[$jobListing->work_preference] ?? ucfirst($jobListing->work_preference) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Contract Type:</th>
                                            <td>{{ $jobListing->contract_type }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Experience Level:</th>
                                            <td>{{ $jobListing->experience_level ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Industry:</th>
                                            <td>{{ $jobListing->industry ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Minimum Degree:</th>
                                            <td>{{ $jobListing->minimum_degree ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Status:</th>
                                            <td>
                                                <span class="badge bg-{{ $statusColors[$jobListing->status] ?? 'secondary' }}">
                                                    {{ ucfirst($jobListing->status) }}
                                                </span>
                                                @if($jobListing->verified)
                                                    <span class="badge bg-info ms-1">
                                                        <i class="ri-checkbox-circle-line me-1"></i>Verified
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Recruiter:</th>
                                            <td>
                                                @if($jobListing->recruiter)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <span class="avatar-title bg-info rounded-circle font-size-12">
                                                                {{ strtoupper(substr($jobListing->recruiter->first_name, 0, 1) . substr($jobListing->recruiter->last_name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <strong>{{ $jobListing->recruiter->first_name }} {{ $jobListing->recruiter->last_name }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $jobListing->recruiter->email }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Posted At:</th>
                                            <td>
                                                @if($jobListing->posted_at)
                                                    {{ $jobListing->posted_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}
                                                @else
                                                    <span class="text-muted">Not posted yet</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Created At:</th>
                                            <td>{{ $jobListing->created_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Last Updated:</th>
                                            <td>{{ $jobListing->updated_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Description Card -->
            @if($jobListing->description)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Job Description</h5>
                    <div class="text-muted">
                        {!! nl2br(e($jobListing->description)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons Card -->
            @include('admin.components.actionCard', [
                'editRoute' => 'admin.job-listings.edit',
                'deleteRoute' => 'admin.job-listings.destroy',
                'indexRoute' => 'admin.job-listings.index',
                'itemId' => $jobListing->id,
                'entityName' => 'job listing',
                'itemData' => [
                    'Job Title' => $jobListing->title,
                    'Company' => $jobListing->company
                ],
                'layout' => 'flex',
                'editButtonText' => 'Edit Job Listing',
                'deleteButtonText' => 'Delete Job Listing',
                'backButtonText' => 'View All Job Listings',
                'modalId' => 'deleteJobListingModal'
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

