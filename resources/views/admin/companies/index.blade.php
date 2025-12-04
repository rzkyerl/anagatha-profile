@extends('admin.admin_layouts.app')

@section('title', 'Company Management')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">Companies</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="card-title mb-1">Company Management</h4>
                            <p class="card-title-desc mb-0">Manage all registered companies</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.companies.export') }}" class="btn btn-success">
                                <i class="ri-download-line me-1"></i> Export to CSV
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-checkbox-circle-line me-2"></i>{{ session('success') }}
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
                           data-datatable-config='{"pageLength": 10, "orderColumn": 4, "orderDirection": "desc", "searchPlaceholder": "Search companies..."}'>
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Company Name</th>
                                    <th>Location</th>
                                    <th>Industry</th>
                                    <th>Recruiters</th>
                                    <th class="text-center">Job Listings</th>
                                    <th>Registered</th>
                                    <th style="width: 100px;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($companies as $index => $company)
                                    <tr>
                                        <td class="text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($company->logo)
                                                    <img src="{{ route('company.logo', $company->logo) }}" 
                                                         alt="{{ $company->name }} Logo" 
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: contain; border: 1px solid #ddd;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="ri-building-line text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $company->name }}</strong>
                                                    @if($company->industry_other)
                                                        <br><small class="text-muted">{{ $company->industry_other }}</small>
                                                    @endif
                                                </div>
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
                                                <span>{{ $company->industry }}</span>
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($company->recruiters) && $company->recruiters->count() > 0)
                                                @foreach($company->recruiters as $index => $recruiter)
                                                    @if($index > 0)
                                                        <br>
                                                    @endif
                                                    <span>{{ $recruiter->first_name }} {{ $recruiter->last_name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No recruiters</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="">{{ $company->job_listings_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $company->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.companies.show', urlencode($company->name)) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="View Details">
                                                <i class="ri-eye-line"></i>
                                            </a>
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
@endsection
