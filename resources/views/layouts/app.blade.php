<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="id">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport"
            content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Disable tap highlight on IE -->
        <meta name="msapplication-tap-highlight" content="no">

        <title>{{ config('app.name', 'Laravel') }} | {{ $title ?? '' }}</title>

        {{-- @persist('styles')  --}}
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/base.min.css') }}"> --}}
        <link rel="preload" href="{{ asset('assets/css/base.min.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/css/base.min.css') }}">
        </noscript>

        <link rel="preload" href="{{ asset('assets/plugins/css-skeletons/css-skeletons.min.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/plugins/css-skeletons/css-skeletons.min.css') }}">
        </noscript>

        {{-- <link rel="preload" href="{{ asset('assets/plugins/restables/restables.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/plugins/restables/restables.css') }}">
        </noscript> --}}

        {{-- <link rel="preload" href="{{ asset('assets/css/mobile-optimised.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/css/mobile-optimised.css') }}">
        </noscript> --}}

        <link rel="preload" href="{{ asset('assets/plugins/animatecss/animate.min.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/plugins/animatecss/animate.min.css') }}">
        </noscript>

        <link rel="preload" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
        </noscript>

        <link rel="preload" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.min.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.min.css') }}">
        </noscript>

        <link rel="preload" href="{{ asset('assets/css/custom-bs.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/css/custom-bs.css') }}">
        </noscript>
        {{-- @endpersist --}}

        <style>
            #loading-page { position: fixed; z-index: 9999; inset: 0; background: #fff; display: flex; align-items: center; justify-content: center; transition: opacity 0.7s cubic-bezier(.4,0,.2,1); opacity: 1; } #loading-page.fade-out { opacity: 0; pointer-events: none; } #loading-page .loading-logo { width: 120px; max-width: 60vw; height: auto; animation: pulse 1.2s infinite alternate; } @keyframes pulse { 0% { transform: scale(1); filter: brightness(1); } 100% { transform: scale(1.08); filter: brightness(1.15);} }
        </style>

        @stack('styles')

    </head>
    <body>
        <div id="loading-page">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="loading-logo">
        </div>

        <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
            <livewire:components.header />

            <div class="app-main">
                <livewire:components.sidebar />

                <div class="app-main__outer">
                    <div class="app-main__inner">
                        {{ $slot }}
                    </div>
                </div>

                <div class="scrollbar-container"></div>
            </div>
        </div>

        @stack("modals")

        {{-- @persist('scripts') --}}
        <script src="{{ asset('assets/plugins/jquery/3.3.1/jquery.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/metismenu/metismenu.js') }}" defer></script>
        <script src="{{ asset('assets/js/app.min.js') }}" defer></script>
        <script src="{{ asset('assets/js/demo.min.js') }}" defer></script>

        <!--Perfect Scrollbar -->
        <script src="{{ asset('assets/plugins/scrollbar/scrollbar.min.js') }}" defer></script>
        {{-- <script src="{{ asset('assets/plugins/scrollbar/scripts-init/scrollbar.min.js') }}" defer></script> --}}

        <!--Input Mask -->
        <script src="{{ asset('assets/plugins/input-mask/input-mask.min.js') }}" defer></script>
        {{-- <script src="{{ asset('assets/plugins/input-mask/scripts-init/input-mask.js') }}" defer></script> --}}

        <!--Textarea Autosize -->
        <script src="{{ asset('assets/plugins/textarea-autosize/textarea-autosize.min.js') }}" defer></script>
        {{-- <script src="{{ asset('assets/plugins/textarea-autosize/scripts-init/textarea-autosize.js') }}" defer></script> --}}

        <!-- Sweetalert2 -->
        <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.all.min.js') }}" defer></script>
        
        <!-- MomentJS -->
        <script src="{{ asset('assets/plugins/moment/moment.min.js') }}" defer></script>
        
        <!-- Daterangepicker -->
        <script src="{{ asset('assets/plugins/daterangepicker/js/daterangepicker.min.js') }}" defer></script>
        
        <!-- Datepicker -->
        <script src="{{ asset('assets/plugins/datepicker/js/datepicker.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/datepicker/i18n/datepicker.id-ID.js') }}" defer></script>
        

        <!--General -->
        <script src="{{ asset('assets/js/general.js') }}" defer></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() { const loadingPage = document.getElementById('loading-page'); const appContainer = document.querySelector('.app-container'); if (loadingPage && appContainer) { appContainer.style.display = 'none'; window.addEventListener('load', function() { setTimeout(() => { appContainer.style.display = 'block'; loadingPage.classList.add('fade-out'); setTimeout(() => { loadingPage.remove(); }, 800); }, 3000); }); } });
        </script>

        {{-- <script src="{{ asset('assets/plugins/restables/restables.min.js') }}" defer></script> --}}
        {{-- @endpersist --}}
        @stack('scripts')
    </body>
</html>
