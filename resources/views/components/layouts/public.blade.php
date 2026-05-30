@props(['heading' => 'Cek Status Perbaikan'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $heading }} — {{ config('app.name') }}</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-50 antialiased">

    {{-- Header --}}
    <header class="border-b border-slate-200 bg-white shadow-sm">
        <div class="mx-auto flex h-16 max-w-3xl items-center px-4 sm:px-6">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-9 w-auto">
        </div>
    </header>

    {{-- Page content --}}
    <main class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
