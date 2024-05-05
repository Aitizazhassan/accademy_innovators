<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Accademy Innovators') }}</title>

        <!-- Fonts -->
        {{-- <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> --}}

        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset('/assets/media/favicons/favicon.png')}}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/assets/media/favicons/favicon-192x192.png')}}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/assets/media/favicons/apple-touch-icon-180x180.png')}}">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <link rel="stylesheet" id="css-main" href="{{ asset('/assets/css/dashmix.min.css')}}">
        
        <!-- custom style css-->
        <link rel="stylesheet" id="css-main" href="{{ asset('/assets/css/guest-style.css') }}">

    </head>
    <body>
        <div id="page-container">

            <!-- Main Container -->
            <main id="main-container">
                {{ $slot }}
            </main>
        </div>    


    {{-- js --}}
    <script src="{{ asset('/assets/js/dashmix.app.min.js') }}"></script>

    <!-- jQuery (required for jQuery Validation plugin) -->
    <script src="{{ asset('/assets/js/lib/jquery.min.js') }}"></script>

    <!-- Page JS Plugins -->
    <script src="{{ asset('/assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('/assets/js/pages/op_auth_signin.min.js') }}"></script>  
    <script src="{{ asset('/assets/js/pages/op_auth_reminder.min.js') }}"></script> 
    <script src="{{ asset('/assets/js/pages/op_auth_signup.min.js') }}"></script>  
    </body>
</html>
