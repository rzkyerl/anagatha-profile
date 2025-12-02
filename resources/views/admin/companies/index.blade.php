@extends('admin.admin_layouts.app')

@section('title', 'Company List')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript: void(0);">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">Companies</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Company List</h6>
                    <div class="d-flex gap-2">
                        <form method="GET" action="{{ route('admin.companies.index') }}" class="d-flex gap-2">
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-sm" 
                                   placeholder="Search company..." 
                                   value="{{ $search ?? '' }}"
                                   style="width: 250px;">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-search-line"></i> Search
                            </button>
                            @if($search)
                                <a href="{{ route('admin.companies.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="ri-close-line"></i> Clear
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($companies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Recruiters</th>
                                        <th>First Registered</th>
                                        <th>Last Registered</th>
                                        <th style="width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($companies as $company)
                                        <tr>
                                            <td>
                                                <strong>{{ $company['company_name'] }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $company['recruiter_count'] }} recruiter(s)</span>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($company['first_registered'])->setTimezone('Asia/Jakarta')->format('d M, Y') }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($company['last_registered'])->setTimezone('Asia/Jakarta')->format('d M, Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.companies.show', urlencode($company['company_name'])) }}" 
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
                    @else
                        <div class="text-center py-5">
                            <i class="ri-building-line" style="font-size: 4rem; color: #d3d3d3;"></i>
                            <h4 class="mt-3 text-muted">No Companies Found</h4>
                            <p class="text-muted">
                                @if($search)
                                    No companies match your search criteria.
                                @else
                                    No companies have been registered yet.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

