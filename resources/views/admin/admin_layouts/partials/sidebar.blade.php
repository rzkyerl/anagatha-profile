<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                @php
                    $user = auth()->user();
                    $role = $user->role ?? 'user';
                    $avatarRoute = ($role === 'recruiter') ? 'recruiter.profile.avatar' : 'admin.profile.avatar';
                @endphp
                @if($user && $user->avatar)
                    <img src="{{ route($avatarRoute, $user->avatar) }}" alt="Avatar"
                        class="avatar-md rounded-circle" />
                @else
                    <img src="{{ asset('dashboard/images/users/profile-default.jpg') }}" alt="Avatar"
                        class="avatar-md rounded-circle" />
                @endif
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">{{ auth()->user() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : 'Admin' }}</h4>
                <h6 class="text-muted mb-1">{{ auth()->user() ? auth()->user()->email : 'Admin' }}</h6>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    @if(auth()->user()->role == 'admin')
                        Admin
                    @elseif(auth()->user()->role == 'recruiter')
                        Recruiter
                    @else
                        User
                    @endif
                </span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
                @php
                    $role = auth()->user()->role ?? 'user';
                @endphp

                <li>
                    @if ($role === 'recruiter')
                        <a href="{{ route('recruiter.dashboard') }}" class="waves-effect">
                            <i class="ri-dashboard-line"></i>
                            <span>Recruiter Dashboard</span>
                        </a>
                    @else
                    <a href="{{ route('admin.dashboard') ?? 'index.html' }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                    @endif
                </li>

                @if ($role === 'admin')
                    {{-- Users Management --}}
                <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-user-line"></i>
                        <span>Users</span>
                    </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin.users.index') }}">All Users</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index', ['role' => 'recruiter']) }}">Recruiters</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index', ['role' => 'user']) }}">Job Seeker</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Companies --}}
                    <li>
                        <a href="{{ route('admin.companies.index') }}" class="waves-effect">
                            <i class="ri-building-line"></i>
                            <span>Companies</span>
                        </a>
                </li>

                    {{-- Jobs Management --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-briefcase-line"></i>
                            <span>Jobs</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin.job-listings.index') }}">Job Listings</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.job-apply.index') }}">Job Applications</a>
                            </li>
                        </ul>
                    </li>

                    {{-- Reports & Analytics --}}
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-bar-chart-line"></i>
                            <span>Reports</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li>
                                <a href="{{ route('admin.reports.index') }}">Overview</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.users') }}">User Reports</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.reports.jobs') }}">Job Reports</a>
                            </li>
                        </ul>
                    </li>
                @elseif ($role === 'recruiter')
                <li>
                    <a href="{{ route('recruiter.company.show') }}" class="waves-effect">
                        <i class="ri-building-line"></i>
                        <span>My Company</span>
                    </a>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-layout-3-line"></i>
                            <span>My Jobs</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                                <a href="{{ route('recruiter.job-listings.index') }}">My Job Listings</a>
                                <a href="{{ route('recruiter.job-apply.index') }}">Job Applications</a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
