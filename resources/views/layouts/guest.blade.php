<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="id">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Disable tap highlight on IE -->
        <meta name="msapplication-tap-highlight" content="no">

        <title>{{ config('app.name', 'Laravel') }} | {{ $title ?? '' }}</title>

        <link rel="preload" href="{{ asset('assets/css/base.min.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/css/base.min.css') }}">
        </noscript>

        <link rel="preload" href="{{ asset('assets/css/floating-labels.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/css/floating-labels.css') }}">
        </noscript>

        @stack('styles')

        <style>
            html, body { height: 100%; } .form-signin { max-width: 330px; padding: 1rem; } .form-signin .form-floating:focus-within { z-index: 2; } .bd-placeholder-img { font-size: 1.125rem; text-anchor: middle; -webkit-user-select: none; -moz-user-select: none; user-select: none; } @media (min-width: 768px) { .bd-placeholder-img-lg { font-size: 3.5rem; } } .b-example-divider { width: 100%; height: 3rem; background-color: rgba(0, 0, 0, .1); border: solid rgba(0, 0, 0, .15); border-width: 1px 0; box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15); } .b-example-vr { flex-shrink: 0; width: 1.5rem; height: 100vh; } .bi { vertical-align: -.125em; fill: currentColor; } .nav-scroller { position: relative; z-index: 2; height: 2.75rem; overflow-y: hidden; } .nav-scroller .nav { display: flex; flex-wrap: nowrap; padding-bottom: 1rem; margin-top: -1px; overflow-x: auto; text-align: center; white-space: nowrap; -webkit-overflow-scrolling: touch; } .btn-bd-primary { --bd-violet-bg: #712cf9; --bd-violet-rgb: 112.520718, 44.062154, 249.437846; --bs-btn-font-weight: 600; --bs-btn-color: var(--bs-white); --bs-btn-bg: var(--bd-violet-bg); --bs-btn-border-color: var(--bd-violet-bg); --bs-btn-hover-color: var(--bs-white); --bs-btn-hover-bg: #6528e0; --bs-btn-hover-border-color: #6528e0; --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb); --bs-btn-active-color: var(--bs-btn-hover-color); --bs-btn-active-bg: #5a23c8; --bs-btn-active-border-color: #5a23c8; } .bd-mode-toggle { z-index: 1500; } .bd-mode-toggle .bi { width: 1em; height: 1em; } .bd-mode-toggle .dropdown-menu .active .bi { display: block !important; } #loading-page { position: fixed; z-index: 9999; inset: 0; background: #fff; display: flex; align-items: center; justify-content: center; transition: opacity 0.7s cubic-bezier(.4,0,.2,1); opacity: 1; } #loading-page.fade-out { opacity: 0; pointer-events: none; } #loading-page .loading-logo { width: 120px; max-width: 60vw; height: auto; animation: pulse 1.2s infinite alternate; } @keyframes pulse { 0% { transform: scale(1); filter: brightness(1); } 100% { transform: scale(1.08); filter: brightness(1.15);} }
        </style>
    </head>
    <body class="d-flex align-items-center py-4 bg-white">
        <div id="loading-page">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="loading-logo">
        </div>

        <main class="form-signin w-100 m-auto">
            {{ $slot }}
        </main>

        <script src="{{ asset('assets/plugins/jquery/3.3.1/jquery.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/metismenu/metismenu.js') }}" defer></script>
        <script src="{{ asset('assets/js/app.min.js') }}" defer></script>
        <script src="{{ asset('assets/js/demo.min.js') }}" defer></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() { const loadingPage = document.getElementById('loading-page'); const appContainer = document.querySelector('.form-signin'); if (loadingPage && appContainer) { appContainer.style.display = 'none'; window.addEventListener('load', function() { setTimeout(() => { appContainer.style.display = 'block'; loadingPage.classList.add('fade-out'); setTimeout(() => { loadingPage.remove(); }, 800); }, 3000); }); } });
        </script>
        @stack('scripts')
    </body>
</html>
