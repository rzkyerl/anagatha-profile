<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- <a href="{{ route('admin.dashboard') ?? 'index.html' }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/logo-bnsp.png') }}" alt="logo-sm" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/logo-bnsp.png') }}" alt="logo-dark" height="20">
                    </span>
                </a> -->

                <a href="{{ route('admin.dashboard') ?? 'index.html' }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/hero-sec-white.png') }}" alt="logo-sm-light" height="22">
                    </span>
                    <span class="logo-lg">
                        <h3 class="text-white font-weight-bold ">Anagata Executive</h3>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>

            
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="mb-3 m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @php
                        $user = auth()->user();
                        $role = $user->role ?? 'user';
                        $profileRoute = ($role === 'recruiter') ? 'recruiter.profile.settings' : 'admin.profile.settings';
                        $logoutRoute = ($role === 'recruiter') ? 'recruiter.logout' : 'admin.logout';
                        $avatarRoute = ($role === 'recruiter') ? 'recruiter.profile.avatar' : 'admin.profile.avatar';
                    @endphp
                    @if($user && $user->avatar)
                        <img class="rounded-circle header-profile-user"
                            src="{{ route($avatarRoute, $user->avatar) }}" alt="Header Avatar">
                    @else
                        <img class="rounded-circle header-profile-user"
                            src="{{ asset('dashboard/images/users/profile-default.jpg') }}" alt="Header Avatar">
                    @endif
                    <span class="d-none d-xl-inline-block ms-1">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item d-block" href="{{ route($profileRoute) }}">
                        <i class="ri-settings-2-line align-middle me-1"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route($logoutRoute) }}" method="POST" class="d-inline w-100">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger w-100" style="border: none; background: none; text-align: left; padding: 0.5rem 1rem; cursor: pointer;">
                            <i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>
