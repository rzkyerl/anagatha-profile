<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>@yield('title', 'Dashboard') | Anagata Executive Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/hero-sec.png') }}" />

    @php
        // Use app version if available, otherwise use a timestamp for cache busting
        $cssVersion = config('app.version', '1.0.0');
        if (app()->environment('local')) {
            $cssVersion = $cssVersion . '.' . time();
        }
    @endphp

    <!-- Bootstrap Css -->
    <link href="{{ asset('dashboard/css/bootstrap.min.css') }}?v={{ $cssVersion }}" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('dashboard/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}?v={{ $cssVersion }}" rel="stylesheet"
        type="text/css" />

        <!-- Datatables Buttons CSS -->
<link href="{{ asset('dashboard/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}?v={{ $cssVersion }}" rel="stylesheet">


    <!-- Responsive datatable examples -->
    <link href="{{ asset('dashboard/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}?v={{ $cssVersion }}"
        rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('dashboard/css/icons.min.css') }}?v={{ $cssVersion }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('dashboard/css/app.min.css') }}?v={{ $cssVersion }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Admin Custom Color Override -->
    <link href="{{ asset('dashboard/css/admin-custom.css') }}?v={{ $cssVersion }}" rel="stylesheet" type="text/css" />

    @stack('styles')
</head>


<body data-topbar="dark">
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('admin.admin_layouts.partials.header')

        @include('admin.admin_layouts.partials.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                   <!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">@yield('title', 'Dashboard')</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    @yield('breadcrumb')
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->



                    @yield('content')
                </div>
            </div>
            <!-- End Page-content -->
            
            @include('admin.admin_layouts.partials.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
<script src="{{ asset('dashboard/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/node-waves/waves.min.js') }}"></script>

<!-- apexcharts -->
<script src="{{ asset('dashboard/libs/apexcharts/apexcharts.min.js') }}"></script>

<!-- jquery.vectormap map -->
<script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}">
</script>

<!-- Required datatable js -->
<script src="{{ asset('dashboard/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<!-- Responsive examples -->
<script src="{{ asset('dashboard/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

<script src="{{ asset('dashboard/js/pages/dashboard.init.js') }}"></script>

<!-- Datatables Buttons JS -->
<script src="{{ asset('dashboard/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('dashboard/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('dashboard/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ asset('dashboard/js/pages/datatables.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('dashboard/js/app.js') }}"></script>

<!-- Include reusable DataTables script -->
@include('admin.admin_layouts.partials.datatables-script')

@stack('scripts')

</body>

</html>
