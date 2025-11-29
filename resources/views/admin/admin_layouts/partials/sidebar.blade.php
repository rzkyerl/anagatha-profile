<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                <img src="{{ asset('dashboard/images/users/profile-default.jpg') }}" alt=""
                    class="avatar-md rounded-circle" />
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">{{ auth()->user() ? auth()->user()->first_name . ' ' . auth()->user()->last_name : 'Admin' }}</h4>
                <h6 class="text-muted mb-1">{{ auth()->user() ? auth()->user()->email : 'Admin' }}</h6>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    {{ auth()->user()->role == 'admin' ? 'Admin' : 'User' }}</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('admin.dashboard') ?? 'index.html' }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}" class="waves-effect">
                        <i class="ri-user-line"></i>
                        <span>Users</span>
                    </a>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-layout-3-line"></i>
                        <span>Jobs</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li>
                            <a href="{{ route('admin.job-listings.index') }}">Job Listings</a>
                            <a href="{{ route('admin.job-apply.index') }}">Job Apply</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
