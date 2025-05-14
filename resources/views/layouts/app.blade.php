<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Language" content="id">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport"
            content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
        {{--
        <meta name="viewport" content="width=device-width, initial-scale=1"> --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Disable tap highlight on IE -->
        <meta name="msapplication-tap-highlight" content="no">

        <title>{{ config('app.name', 'Laravel') }} | {{ $title ?? '' }}</title>

        @assets
        {{-- @persist('styles')  --}}
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

        <link rel="preload" href="{{ asset('assets/plugins/restables/restables.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/plugins/restables/restables.css') }}">
        </noscript>

        <link rel="preload" href="{{ asset('assets/css/mobile-optimised.css') }}" as="style"
            onload="this.onload=null;this.rel='stylesheet'">
        <noscript>
            <link rel="stylesheet" href="{{ asset('assets/css/mobile-optimised.css') }}">
        </noscript>
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/base.min.css') }}"> --}}
        {{-- @endpersist --}}

        @stack('styles')
        @endassets
    </head>
    <body>
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
        
        {{-- @script --}}
        {{-- @persist('scripts') --}}
        <script src="{{ asset('assets/plugins/jquery/3.3.1/jquery.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/bootstrap/bootstrap.bundle.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/metismenu/metismenu.js') }}" defer></script>
        <script src="{{ asset('assets/js/app.min.js') }}" defer></script>
        <script src="{{ asset('assets/js/demo.min.js') }}" defer></script>

        <!--Perfect Scrollbar -->
        <script src="{{ asset('assets/plugins/scrollbar/scrollbar.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/scrollbar/scripts-init/scrollbar.min.js') }}" defer></script>
        <script src="{{ asset('assets/plugins/restables/restables.min.js') }}" defer></script>
        {{-- @endpersist --}}
        @stack('scripts')
        {{-- @endscript --}}
    </body>
</html>
