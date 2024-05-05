<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Accademy Innovators') }} | @yield('title', 'Accademy')</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}
        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <!-- Icons -->
        <link rel="shortcut icon" href="{{ asset('/assets/media/favicons/favicon.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/assets/media/favicons/favicon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/media/favicons/apple-touch-icon-180x180.png') }}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Page JS Plugins CSS -->
        <link rel="stylesheet" href="{{ asset('/assets/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/assets/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/assets/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/assets/js/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/js/plugins/flatpickr/flatpickr.min.css') }}">

        <!-- Dashmix framework -->
        <link rel="stylesheet" id="css-main" href="{{ asset('/assets/css/dashmix.min.css') }}">

        <!-- custom style css-->
        <link rel="stylesheet" id="css-main" href="{{ asset('/assets/css/style.css') }}">
        
        <!-- Include SweetAlert CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        {{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> --}}
        {{-- <script>
            window.addEventListener("beforeunload", function (event) {
                Dashmix.loader('show', 'bg-gd-sea');

            });
        </script> --}}
    </head>
    <body class="">
        <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed main-content-narrow side-trans-enabled">
            <!-- Page Header -->
            @include('includes.side-overlay')
            <!-- Page Header -->
            @include('includes.nav-sidebar')
            <!-- Page Header -->
            @include('includes.header')
            <!-- Main Container -->
            <main id="main-container">
                {{ $slot }}
            </main>
            <!-- Page footer -->
            @include('includes.footer')
        </div>

        {{-- js --}}
        <script src="{{ asset('/assets/js/dashmix.app.min.js') }}"></script>

        <!-- jQuery (required for DataTables plugin) -->
        <script src="{{ asset('/assets/js/lib/jquery.min.js') }}"></script>

        <!-- Page JS Plugins -->
        <script src="{{ asset('/assets/js/plugins/chart.js/chart.umd.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-buttons/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-buttons-jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-buttons/buttons.print.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/datatables-buttons/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

        <!-- Page JS Code -->
        <script src="{{ asset('/assets/js/pages/be_pages_dashboard.min.js') }}"></script>
        <script src="{{ asset('/assets/js/pages/be_tables_datatables.min.js') }}"></script>
        <script src="{{ asset('/assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery.maskedinput/jquery.maskedinput.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/dropzone/min/dropzone.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/pwstrength-bootstrap/pwstrength-bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/flatpickr/flatpickr.min.js') }}"></script>

        <!-- Include SweetAlert JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Page JS Helpers (BS Notify Plugin) -->
        <script>Dashmix.helpersOnLoad(['js-flatpickr', 'jq-datepicker', 'jq-maxlength', 'jq-select2', 'jq-rangeslider', 'jq-masked-inputs', 'jq-pw-strength']);</script>

        {{-- show message success and error in toaster --}}
        @if (Session::has('success'))
        <script>
            const success_msg = "{{ Session::get('success') }}"
            Dashmix.helpers('jq-notify', {type: 'success', icon: 'fa fa-check me-1', message: success_msg});
        </script>
        @endif
        @if (Session::has('error'))
        <script>
            const error_msg = "{{ Session::get('error') }}"
            Dashmix.helpers('jq-notify', {type: 'danger', icon: 'fa fa-times me-1', message: error_msg});
        </script>
        @endif

        <script>
            // toaster generic function for request
            function showNotification(type, message) {
                Dashmix.helpers('jq-notify', {
                    type: type,
                    position: 'top-center',
                    icon: '',
                    message: message,
                    z_index: 1251,
                    containerClass: 'custom-notification-width'
                });
            }
        </script>

        <script>
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        </script>
        
        @yield('scripts')

        {{-- <script>
            $(document).on('ready', function () {
                Dashmix.loader('hide');
            });
        </script> --}}
    </body>
</html>
