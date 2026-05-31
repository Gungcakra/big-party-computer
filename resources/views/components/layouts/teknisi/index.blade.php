@props(['heading' => 'Teknisi'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $heading }} — {{ config('app.name') }}</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 antialiased" x-data="{ sidebarOpen: false }">

    @include('components.layouts.teknisi.sidebar')

    <div class="flex min-h-screen flex-col lg:pl-64">

        @include('components.layouts.teknisi.navbar')

        <main class="flex-1 p-4 sm:p-6">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
