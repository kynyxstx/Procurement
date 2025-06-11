<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('Images/favicon.ico') }}" type="image/x-icon">

    <title>{{ config('app.name', 'Procurement') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body class="h-full bg-cover bg-center bg-no-repeat font-sans text-gray-900 antialiased"
    style="background-image: url('{{ asset('Images/bg_1.png') }}');">

    <div class="absolute inset-0 bg-black opacity-30"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center">
        {{ $slot }}
    </div>

    @livewireScripts
</body>

</html>