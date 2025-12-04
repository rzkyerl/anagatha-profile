@extends('admin.admin_layouts.app')

@section('title', 'Company Details')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript:void(0)">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.companies.index') }}">Companies</a>
    </li>
    <li class="breadcrumb-item active">{{ $company->name }}</li>
@endsection

@section('content')

    {{-- Back Button --}}
    <div class="mb-3">
        <a href="{{ route('admin.companies.index') }}" class="btn btn-light border">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>

    <div class="row g-4">

        {{-- Company Info --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="fw-semibold mb-0">
                        <i class="ri-building-line me-1"></i> Company Information
                    </h5>
                </div>

                <div class="card-body">

                    {{-- Logo --}}
                    <div class="text-center mb-4">
                        @if($company->logo)
                            <img src="{{ route('company.logo', $company->logo) }}"
                                 alt="Logo"
                                 class="img-fluid rounded-3 border p-2"
                                 style="max-width: 160px;">
                        @else
                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center"
                                 style="width:160px; height:160px; margin:auto;">
                                <i class="ri-building-line" style="font-size:3rem; color:#ccc;"></i>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Company Name</label>
                        <p class="fw-semibold mb-0">{{ $company->name }}</p>
                    </div>

                    @if($company->location)
                        <div class="mb-3">
                            <label class="text-muted small">Location</label>
                            <p class="mb-0">{{ $company->location }}</p>
                        </div>
                    @endif

                    @if($company->industry)
                        <div class="mb-3">
                            <label class="text-muted small">Industry</label>
                            <p class="mb-0">{{ $company->industry }}</p>
                            @if($company->industry_other)
                                <small class="text-muted">{{ $company->industry_other }}</small>
                            @endif
                        </div>
                    @endif

                    <div class="row text-center my-3">
                        <div class="col-6">
                            <p class="text-muted small mb-1">Recruiters</p>
                            <h5 class="mb-0 text-primary fw-bold">{{ $totalRecruiters }}</h5>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small mb-1">Job Listings</p>
                            <h5 class="mb-0 text-success fw-bold">{{ $jobListingsCount ?? 0 }}</h5>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <label class="text-muted small">First Registered</label>
                        <p class="mb-0">
                            <i class="ri-calendar-line me-1"></i>
                            {{ $firstRegistered->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <div class="mb-2">
                        <label class="text-muted small">Last Registered</label>
                        <p class="mb-0">
                            <i class="ri-calendar-line me-1"></i>
                            {{ $lastRegistered->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Recruiter List --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-semibold mb-0">
                        <i class="ri-user-line me-1"></i> Recruiters
                    </h5>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2">
                        {{ $totalRecruiters }} total
                    </span>
                </div>

                <div class="card-body">

                    @if($recruiters->count())
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Job Title</th>
                                        <th>Registered</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recruiters as $i => $recruiter)
                                        <tr>
                                            <td class="text-muted">{{ $i+1 }}</td>
                                            
                                            {{-- Name --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($recruiter->avatar)
                                                        <img src="{{ asset('storage/'.$recruiter->avatar) }}"
                                                             class="rounded-circle me-2"
                                                             style="width:32px; height:32px; object-fit:cover;">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center"
                                                             style="width:32px; height:32px;">
                                                            {{ strtoupper(substr($recruiter->first_name,0,1)) }}
                                                        </div>
                                                    @endif
                                                    <span>{{ $recruiter->first_name }} {{ $recruiter->last_name }}</span>
                                                </div>
                                            </td>

                                            <td><a href="mailto:{{ $recruiter->email }}">{{ $recruiter->email }}</a></td>

                                            <td>
                                                @if($recruiter->phone)
                                                    <a href="tel:{{ $recruiter->phone }}">
                                                        {{ $recruiter->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>

                                            <td>
                                                {{ $recruiter->job_title ?? 'N/A' }}
                                                @if($recruiter->job_title_other)
                                                    <br><small class="text-muted">{{ $recruiter->job_title_other }}</small>
                                                @endif
                                            </td>

                                            <td class="text-muted">
                                                {{ $recruiter->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}
                                            </td>

                                            <td class="text-center">
                                                <a href="{{ route('admin.users.show', $recruiter->id) }}"
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
                        <div class="text-center py-5">
                            <i class="ri-user-line" style="font-size:3rem; color:#ccc;"></i>
                            <p class="text-muted mt-3">No recruiters found.</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>
@endsection
