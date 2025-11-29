@extends('admin.admin_layouts.app')

@section('title', 'Job Application Details')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.job-apply.index') }}">Job Applications</a>
    </li>
    <li class="breadcrumb-item active">{{ $jobApply->full_name }}</li>
@endsection

@section('content')
    <div class="row">
        @if(session('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ri-check-line me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Main Information -->
        <div class="col-xl-8">
            <!-- Applicant Information Card -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Applicant Information</h4>
                            <p class="card-title-desc mb-0">View job application details</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.job-apply.edit', $jobApply->id) }}" class="btn btn-primary">
                                <i class="ri-pencil-line align-middle me-1"></i> Edit
                            </a>
                            <a href="{{ route('admin.job-apply.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line align-middle me-1"></i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 200px;">Full Name:</th>
                                            <td>{{ $jobApply->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email:</th>
                                            <td><a href="mailto:{{ $jobApply->email }}">{{ $jobApply->email }}</a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Phone:</th>
                                            <td><a href="tel:{{ $jobApply->phone }}">{{ $jobApply->phone }}</a></td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Address:</th>
                                            <td>{{ $jobApply->address }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Current Salary:</th>
                                            <td>{{ $jobApply->current_salary ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Expected Salary:</th>
                                            <td>{{ $jobApply->expected_salary }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Availability:</th>
                                            <td>{{ $jobApply->availability }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Relocation:</th>
                                            <td>
                                                <span class="badge bg-{{ $jobApply->relocation === 'yes' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($jobApply->relocation) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($jobApply->linkedin)
                                        <tr>
                                            <th scope="row">LinkedIn:</th>
                                            <td><a href="{{ $jobApply->linkedin }}" target="_blank">{{ $jobApply->linkedin }}</a></td>
                                        </tr>
                                        @endif
                                        @if($jobApply->github)
                                        <tr>
                                            <th scope="row">GitHub:</th>
                                            <td><a href="{{ $jobApply->github }}" target="_blank">{{ $jobApply->github }}</a></td>
                                        </tr>
                                        @endif
                                        @if($jobApply->social_media)
                                        <tr>
                                            <th scope="row">Social Media:</th>
                                            <td><a href="{{ $jobApply->social_media }}" target="_blank">{{ $jobApply->social_media }}</a></td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Information Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Job Information</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row" style="width: 200px;">Job Position:</th>
                                    <td>
                                        @if($jobApply->jobListing)
                                            <strong>{{ $jobApply->jobListing->title }}</strong>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($jobApply->jobListing)
                                <tr>
                                    <th scope="row">Company:</th>
                                    <td>{{ $jobApply->jobListing->company }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Location:</th>
                                    <td>{{ $jobApply->jobListing->location }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th scope="row">Status:</th>
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
                                        <span class="badge bg-{{ $statusColors[$jobApply->status] ?? 'secondary' }} fs-6">
                                            {{ ucfirst($jobApply->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Applied At:</th>
                                    <td>{{ $jobApply->applied_at ? \Carbon\Carbon::parse($jobApply->applied_at)->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') : $jobApply->created_at->setTimezone('Asia/Jakarta')->format('d M, Y H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cover Letter & Experience Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Application Details</h5>
                    @if($jobApply->cover_letter)
                    <div class="mb-4">
                        <h6>Cover Letter:</h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($jobApply->cover_letter)) !!}
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <h6>Reason for Applying:</h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($jobApply->reason_applying)) !!}
                        </div>
                    </div>

                    @if($jobApply->relevant_experience)
                    <div>
                        <h6>Relevant Experience:</h6>
                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($jobApply->relevant_experience)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Admin Notes Card -->
            @if($jobApply->notes)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Admin Notes</h5>
                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                        {!! nl2br(e($jobApply->notes)) !!}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Files Card -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Attachments</h5>
                    
                    @if($jobApply->cv)
                    <div class="mb-3">
                        <label class="form-label">CV / Resume:</label>
                        <div>
                            <a href="{{ route('admin.job-apply.download.cv', $jobApply->id) }}" 
                               class="btn btn-sm btn-outline-primary w-100">
                                <i class="ri-file-pdf-line me-1"></i> Download CV
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($jobApply->portfolio_file)
                    <div>
                        <label class="form-label">Portfolio:</label>
                        <div>
                            <a href="{{ route('admin.job-apply.download.portfolio', $jobApply->id) }}" 
                               class="btn btn-sm btn-outline-primary w-100">
                                <i class="ri-folder-line me-1"></i> Download Portfolio
                            </a>
                        </div>
                    </div>
                    @endif

                    @if(!$jobApply->cv && !$jobApply->portfolio_file)
                    <p class="text-muted mb-0">No attachments available</p>
                    @endif
                </div>
            </div>

            <!-- User Account Card -->
            @if($jobApply->user)
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">User Account</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">User:</th>
                                    <td>
                                        <a href="{{ route('admin.users.show', $jobApply->user->id) }}">
                                            {{ $jobApply->user->first_name }} {{ $jobApply->user->last_name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Email:</th>
                                    <td>{{ $jobApply->user->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Role:</th>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($jobApply->user->role) }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions Card -->
            @include('admin.components.actionCard', [
                'editRoute' => 'admin.job-apply.edit',
                'deleteRoute' => 'admin.job-apply.destroy',
                'indexRoute' => 'admin.job-apply.index',
                'itemId' => $jobApply->id,
                'entityName' => 'job application',
                'itemData' => [
                    'Applicant' => $jobApply->full_name,
                    'Email' => $jobApply->email,
                    'Job' => $jobApply->jobListing ? $jobApply->jobListing->title : 'N/A'
                ],
                'layout' => 'grid',
                'editButtonText' => 'Edit Application',
                'deleteButtonText' => 'Delete Application',
                'backButtonText' => 'View All Applications',
                'modalId' => 'deleteJobApplyModal'
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

