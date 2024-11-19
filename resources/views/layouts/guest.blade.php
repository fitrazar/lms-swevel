<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="title" content="{{ $__env->yieldContent('title') }}" />
    <meta name="description"
        content="{{ !empty(trim($__env->yieldContent('description'))) ? $__env->yieldContent('description') : $appSetting->description }}" />

    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://itsoindonesia.com/" />
    <meta property="og:title" content="{{ $__env->yieldContent('title') }}" />
    <meta property="og:description"
        content="{{ !empty(trim($__env->yieldContent('description'))) ? $__env->yieldContent('description') : $appSetting->description }}" />
    <meta property="og:image"
        content="{{ !empty(trim($__env->yieldContent('image'))) ? $__env->yieldContent('image') : asset('assets/images/logo.png') }}" />


    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://itsoindonesia.com/" />
    <meta property="twitter:title" content="{{ $__env->yieldContent('title') }}" />
    <meta property="twitter:description"
        content="{{ !empty(trim($__env->yieldContent('description'))) ? $__env->yieldContent('description') : $appSetting->description }}" />
    <meta property="twitter:image"
        content="{{ !empty(trim($__env->yieldContent('image'))) ? $__env->yieldContent('image') : asset('assets/images/logo.png') }}" />

    <title>@yield('title', config('app.name', 'Laravel')) - {{ config('app.name', 'Laravel') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/brands.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
                document.querySelector('.theme-controller').checked = savedTheme === 'dark';
            }
        });
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins antialiased">
    <div class="min-h-screen bg-base-100">
        @include('layouts.navigation')

        <main class="py-24">
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </div>
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/vendors/fontawesome/js/all.js') }}"></script>
    <script src="{{ asset('assets/vendors/fontawesome/js/solid.js') }}"></script>
    <script src="{{ asset('assets/vendors/fontawesome/js/brands.js') }}"></script>
    <script src="{{ asset('assets/vendors/fontawesome/js/fontawesome.js') }}"></script>
    @if (isset($script))
        {{ $script }}
    @endif
</body>

</html>
