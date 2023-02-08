<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Fontawesome -->
        <script src="https://kit.fontawesome.com/c101a12428.js" crossorigin="anonymous"></script>

        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Condition -->
            @if (request()->routeIs('product.index'))
                <script src="{{ asset('assets/js/Product.js') }}"></script>
            @endif
            @if (request()->routeIs('customer*'))
                <script src="{{ asset('assets/js/Customer.js') }}"></script>
            @endif
            @if (request()->routeIs('order.Suratjalan'))
                <script src="{{ asset('assets/js/SuratJalan.js') }}"></script>
            @endif
            @if (request()->routeIs('order*'))
                <script src="{{ asset('assets/js/POCreate.js') }}"></script>
                <script src="{{ asset('assets/js/PO.js') }}"></script>
                <script src="https://unpkg.com/@develoka/angka-rupiah-js/index.min.js" type="module">
            @endif
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        </div>
    </body>
</html>
