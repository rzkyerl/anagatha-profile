@extends('admin.admin_layouts.app')

@section('title', 'My Company')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="javascript:void(0)">Anagata Executive</a>
    </li>
    <li class="breadcrumb-item active">My Company</li>
@endsection

@section('content')

    {{-- Toast Alert --}}
    @if (session('status'))
        <div class="alert alert-{{ session('toast_type') === 'success' ? 'success' : (session('toast_type') === 'error' ? 'danger' : 'info') }} 
                    alert-dismissible fade show modern-alert" role="alert">
            <i class="ri-{{ session('toast_type') === 'success' ? 'checkbox-circle-line' :
                (session('toast_type') === 'error' ? 'error-warning-line' : 'information-line') }} me-2"></i>
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-1">Company Information</h4>
                <p class="text-muted mb-0">Manage and update your company details</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.recruiter.company.edit') }}" class="btn btn-primary modern-btn">
                    <i class="ri-edit-line me-1"></i> Edit Company
                </a>
            </div>
        </div>
    </div>


    {{-- Header Card --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        
                        {{-- Logo --}}
                        <div class="col-auto">
                            <div class="company-logo-wrapper border rounded-3 bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 110px; height: 110px;">
                                @if($user->company_logo)
                                    <img src="{{ route('admin.recruiter.company.logo', $user->company_logo) }}"
                                         alt="{{ $user->company_name ?? 'Company' }}"
                                         class="img-fluid"
                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @else
                                    <i class="ri-building-line text-secondary" style="font-size: 3rem;"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="col">
                            <h3 class="mb-2 fw-semibold" style="color: #2c3e50;">
                                {{ $user->company_name ?? 'Company Name Not Set' }}
                            </h3>

                            @if($user->job_title)
                            <p class="text-muted mb-1">
                                <i class="ri-briefcase-line me-1"></i>
                                {{ $user->job_title }}
                                @if($user->job_title === 'Other' && $user->job_title_other)
                                    - {{ $user->job_title_other }}
                                @endif
                            </p>
                            @endif

                            @if($user->industry)
                            <p class="text-muted mb-0">
                                <i class="ri-building-2-line me-1"></i>
                                {{ $user->industry }}
                                @if($user->industry === 'Other' && $user->industry_other)
                                    - {{ $user->industry_other }}
                                @endif
                            </p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm modern-card">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-semibold">
                        <i class="ri-information-line text-primary me-2"></i> Company Details
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-3">

                         @php
                             $details = [
                                 ['label' => 'Company Name', 'icon' => 'ri-building-line', 'value' => $user->company_name ?? 'Not Set'],
                                 ['label' => 'Industry', 'icon' => 'ri-building-2-line', 'value' => $user->industry ? ($user->industry . ($user->industry === 'Other' && $user->industry_other ? " ({$user->industry_other})" : '')) : 'Not Set'],
                                 ['label' => 'Job Title', 'icon' => 'ri-user-settings-line', 'value' => $user->job_title ? ($user->job_title . ($user->job_title === 'Other' && $user->job_title_other ? " ({$user->job_title_other})" : '')) : 'Not Set'],
                                 ['label' => 'Phone / WhatsApp', 'icon' => 'ri-phone-line', 'value' => $user->phone ?? 'Not Set'],
                                 ['label' => 'Email', 'icon' => 'ri-mail-line', 'value' => $user->email],
                                 ['label' => 'Registered Date', 'icon' => 'ri-calendar-line', 'value' => $user->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i')],
                             ];
                         @endphp

                        @foreach ($details as $item)
                            <div class="col-md-6">
                                <div class="detail-box">
                                    <label class="text-muted small mb-1">
                                        <i class="{{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                                    </label>

                                    @if($item['label'] === 'Email')
                                        <h6 class="fw-semibold">
                                            <a href="mailto:{{ $item['value'] }}" class="text-decoration-none">
                                                {{ $item['value'] }}
                                            </a>
                                        </h6>
                                    @else
                                        <h6 class="fw-semibold mb-0">{{ $item['value'] }}</h6>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('styles')
<style>
    /* ============ Modern UI Tweaks ============ */

    .modern-btn {
        border-radius: 10px !important;
        padding: 0.55rem 1.1rem;
    }

    .modern-alert {
        border-radius: 12px;
        font-size: 0.9rem;
    }

    .company-header-card {
        border-radius: 16px;
        background: linear-gradient(135deg, #5a67d8, #805ad5);
        color: #fff;
        border: none;
    }

    .company-logo img {
        width: 95px;
        height: 95px;
        object-fit: contain;
        background: #fff;
        padding: 10px;
        border-radius: 14px;
    }
    .company-logo .default-icon {
        font-size: 4rem;
        color: #fff;
        opacity: 0.7;
    }

    .modern-card {
        border-radius: 16px;
    }

    .detail-box {
        background: #f6f7fb;
        padding: 1rem;
        border-radius: 12px;
        transition: 0.2s ease;
    }
    .detail-box:hover {
        background: #eef0f4;
        transform: translateY(-2px);
    }

    .empty-state-card {
        border-radius: 16px;
    }
    .empty-icon {
        font-size: 5rem;
        color: #d4d4d4;
    }
</style>
@endpush
