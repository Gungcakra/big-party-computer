{{-- Mobile backdrop --}}
<div
    x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-linear duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
    x-cloak
></div>

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-[#0d1b2e] transition-transform duration-300 ease-in-out lg:translate-x-0"
>
    {{-- Logo --}}
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-5">
        <div class="rounded-lg bg-white px-2 py-1">
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-8 w-auto">
        </div>
        <button @click="sidebarOpen = false" class="ml-auto rounded-md p-1 text-slate-500 hover:text-white lg:hidden">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-3 py-4">
        @php
            $navItem = function (string $route, string $label, string $icon) {
                $active = request()->routeIs($route);
                $base   = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors';
                $cls    = $active
                    ? "$base bg-blue-600 text-white"
                    : "$base text-slate-400 hover:bg-white/5 hover:text-white";
                return compact('route', 'label', 'icon', 'cls');
            };
        @endphp

        <p class="mb-1 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Utama</p>

        @php $item = $navItem('admin.dashboard', 'Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6') @endphp
        <a href="{{ route($item['route']) }}" class="{{ $item['cls'] }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
            {{ $item['label'] }}
        </a>

        <p class="mb-1 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Layanan</p>

        @php $item = $navItem('admin.penerimaan-perangkat', 'Penerimaan Perangkat', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4') @endphp
        <a href="{{ route($item['route']) }}" class="{{ $item['cls'] }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
            {{ $item['label'] }}
        </a>

        @php $item = $navItem('admin.antrian-servis', 'Antrian & Servis', 'M4 6h16M4 10h16M4 14h16M4 18h16') @endphp
        <a href="{{ route($item['route']) }}" class="{{ $item['cls'] }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
            {{ $item['label'] }}
        </a>

        @php $item = $navItem('admin.transaksi', 'Transaksi', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z') @endphp
        <a href="{{ route($item['route']) }}" class="{{ $item['cls'] }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
            {{ $item['label'] }}
        </a>

        <p class="mb-1 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Laporan</p>

        @php $item = $navItem('admin.laporan', 'Laporan', 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z') @endphp
        <a href="{{ route($item['route']) }}" class="{{ $item['cls'] }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
            {{ $item['label'] }}
        </a>

        <p class="mb-1 mt-5 px-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Pengaturan</p>

        @php $item = $navItem('admin.pengguna', 'Kelola Pengguna', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z') @endphp
        <a href="{{ route($item['route']) }}" class="{{ $item['cls'] }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"/></svg>
            {{ $item['label'] }}
        </a>
    </nav>

    {{-- User info --}}
    <div class="border-t border-white/10 p-3">
        <div class="flex items-center gap-3 rounded-lg px-3 py-2.5">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name ?? 'User' }}</p>
                <p class="truncate text-xs text-slate-500">{{ ucfirst(auth()->user()->role ?? '') }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="shrink-0 rounded-md p-1 text-slate-500 transition-colors hover:text-white" title="Keluar">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>
