<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="theme-color" content="#000000" />
    <link rel="icon" href="{{ asset('images/letter-r.jpeg') }}">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />

    <title>{{ trans('panel.site_title') }}</title>
    @stack('scripts-header')
    @stack('styles')
    @wireUiScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <x-notifications />
    <x-dialog z-index="z-50" blur="md" align="top" />
    <!-- strat wrapper -->
    <noscript>You need to enable JavaScript to run this app.</noscript>
    @include('components.navbar')
    <!-- start wrapper -->
    <div class="h-screen flex flex-row flex-wrap">
        @include('components.sidebar')

        <!-- start content -->
        <div class="bg-gray-100 flex-1 p-6 md:mt-16">
            @yield('content')
        </div>
        <!-- end content -->
    </div>
    <!-- end wrapper -->

    @livewireScripts
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/datepicker.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    @yield('scripts')
    @stack('scripts')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
@include('components.end')
