@extends('admin.admin_layouts.app')

@section('title', 'Company Details')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.companies.index') }}">Companies</a>
    </li>
    <li class="breadcrumb-item active">{{ $companyName }}</li>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.companies.index') }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line"></i> Back to Company List
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Company Info Card --}}
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Company Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Company Name</label>
                        <h5 class="mb-0">{{ $companyName }}</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small">Total Recruiters</label>
                        <h5 class="mb-0">{{ $totalRecruiters }}</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small">First Registered</label>
                        <p class="mb-0">{{ $firstRegistered->setTimezone('Asia/Jakarta')->format('d M, Y H:i') }}</p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small">Last Registered</label>
                        <p class="mb-0">{{ $lastRegistered->setTimezone('Asia/Jakarta')->format('d M, Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recruiters List --}}
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recruiters from {{ $companyName }}</h6>
                </div>
                <div class="card-body">
                    @if($recruiters->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Job Title</th>
                                        <th>Registered</th>
                                        <th style="width: 100px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recruiters as $recruiter)
                                        <tr>
                                            <td>
                                                <strong>{{ $recruiter->first_name }} {{ $recruiter->last_name }}</strong>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $recruiter->email }}">{{ $recruiter->email }}</a>
                                            </td>
                                            <td>{{ $recruiter->phone ?? 'N/A' }}</td>
                                            <td>{{ $recruiter->job_title ?? 'N/A' }}</td>
                                            <td>
                                                {{ $recruiter->created_at->setTimezone('Asia/Jakarta')->format('d M, Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.show', $recruiter->id) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="View User">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ri-user-line" style="font-size: 3rem; color: #d3d3d3;"></i>
                            <p class="text-muted mt-3">No recruiters found for this company.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

