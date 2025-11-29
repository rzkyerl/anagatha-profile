<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Login to Dashboard | Anagata Executive - Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Anagata Executive Admin Dashboard" name="description" />
        <meta content="Anagata Executive" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/hero-sec.png') }}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('dashboard/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('dashboard/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('dashboard/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <!-- CSS Variables -->
        <!-- <link href="{{ asset('styles/base/variables.css') }}" rel="stylesheet" type="text/css" /> -->

        <style>
            :root {
                --brand-900: #800A0A;
                --brand-700: #800A0A;
                --brand-500: #800A0A;
                --brand-400: #D21A1A;
                --brand-200: #f5e8e8;
                --accent-500: #D21A1A;
                --text-900: #101728;
                --text-700: #2f3b52;
                --text-500: #53617c;
                --surface-100: #ffffff;
                --surface-200: #f5f7fb;
                --surface-300: #e8edf5;
                --border-200: #D9D9D9;
            }

            * {
                font-family: 'Poppins', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            }

            body {
                background-image: url("{{ asset('assets/Login-BG.jpg') }}");
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }

            body::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(128, 10, 10, 0.4);
                z-index: 0;
            }

            .login-container {
                position: relative;
                z-index: 2;
                width: 100%;
                max-width: 450px;
                padding: 20px;
            }

            .login-card {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
                border-radius: 24px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
                padding: 50px 40px;
                border: 1px solid rgba(255, 255, 255, 0.3);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .login-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 70px rgba(128, 10, 10, 0.35);
            }

            .logo-container {
                text-align: center;
                margin-bottom: 40px;
            }

            .logo-container img {
                max-width: 120px;
                height: auto;
                filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
            }

            .login-header {
                text-align: center;
                margin-bottom: 35px;
            }

            .login-header h2 {
                font-size: 28px;
                font-weight: 700;
                color: var(--brand-500);
                margin-bottom: 8px;
                letter-spacing: -0.5px;
            }

            .login-header p {
                font-size: 15px;
                color: var(--text-500);
                margin: 0;
                font-weight: 400;
            }

            .form-group {
                margin-bottom: 24px;
            }

            .form-control {
                height: 52px;
                border-radius: 12px;
                border: 2px solid var(--border-200);
                padding: 12px 20px;
                font-size: 15px;
                transition: all 0.3s ease;
                background: var(--surface-200);
                color: var(--text-700);
            }

            .form-control:focus {
                border-color: var(--brand-500);
                background: var(--surface-100);
                box-shadow: 0 0 0 4px var(--brand-200);
                outline: none;
            }

            .form-control::placeholder {
                color: var(--text-500);
                font-weight: 400;
            }

            .input-icon {
                position: relative;
            }

            .input-icon i {
                position: absolute;
                left: 18px;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-500);
                font-size: 18px;
                z-index: 2;
            }

            .input-icon .form-control {
                padding-left: 50px;
            }

            .btn-login {
                width: 100%;
                height: 52px;
                background: linear-gradient(135deg, var(--brand-500) 0%, var(--brand-400) 100%);
                border: none;
                border-radius: 12px;
                color: #ffffff;
                font-size: 16px;
                font-weight: 600;
                letter-spacing: 0.5px;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(128, 10, 10, 0.4);
                position: relative;
                overflow: hidden;
                margin-top: 8px;
            }

            .btn-login::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
                transition: left 0.5s;
            }

            .btn-login:hover::before {
                left: 100%;
            }

            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(128, 10, 10, 0.5);
            }

            .btn-login:active {
                transform: translateY(0);
            }

            .alert {
                border-radius: 12px;
                border: none;
                padding: 14px 18px;
                margin-bottom: 24px;
                font-size: 14px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .alert-success {
                background: #f0fdf4;
                color: #166534;
                border-left: 4px solid #22c55e;
            }

            .alert-danger {
                background: var(--brand-200);
                color: var(--brand-900);
                border-left: 4px solid var(--brand-400);
            }

            .invalid-feedback {
                font-size: 13px;
                margin-top: 6px;
                color: var(--brand-400);
                font-weight: 500;
            }

            .is-invalid {
                border-color: var(--brand-400) !important;
            }

            @media (max-width: 576px) {
                .login-card {
                    padding: 35px 25px;
                    border-radius: 20px;
                }

                .login-header h2 {
                    font-size: 24px;
                }

                .form-control {
                    height: 48px;
                }

                .btn-login {
                    height: 48px;
                }
            }
        </style>

    </head>

    <body>
        <div class="login-container">
            <div class="login-card">
                <div class="logo-container">
                    <img src="{{ asset('assets/hero-sec.png') }}" alt="Anagata Executive Logo">
                </div>

                <div class="login-header">
                    <h2>Login to Dashboard</h2>
                    <p>Enter your credentials to access the admin panel</p>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="mb-2"><i class="mdi mdi-alert-circle me-2"></i> Validation Error!</h5>
                    <ul class="mb-0" style="padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <div class="input-icon">
                            <i class="mdi mdi-email-outline"></i>
                            <input class="form-control @error('email') is-invalid @enderror" 
                                   type="email" 
                                   name="email"
                                   value="{{ old('email') }}"
                                   required 
                                   autofocus
                                   placeholder="Enter your email address">
                        </div>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="input-icon">
                            <i class="mdi mdi-lock-outline"></i>
                            <input class="form-control @error('password') is-invalid @enderror" 
                                   type="password" 
                                   name="password"
                                   required 
                                   placeholder="Enter your password">
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-login waves-effect waves-light" type="submit">
                        <i class="mdi mdi-login me-2"></i> Sign In to Dashboard
                    </button>
                </form>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('dashboard/js/app.js') }}"></script>

        <script>
            // Auto-dismiss alerts after 5 seconds
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);

                // Add smooth focus animation
                $('.form-control').on('focus', function() {
                    $(this).parent().addClass('focused');
                }).on('blur', function() {
                    if (!$(this).val()) {
                        $(this).parent().removeClass('focused');
                    }
                });
            });
        </script>

    </body>
</html>
