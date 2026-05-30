<div class="flex min-h-screen">

    {{-- ===== LEFT PANEL — branding (hidden on mobile) ===== --}}
    <div class="relative hidden lg:flex lg:w-1/2 flex-col items-center justify-center overflow-hidden bg-[#0d1b2e] px-12">

        {{-- Subtle grid background --}}
        <div class="pointer-events-none absolute inset-0 opacity-[0.03]"
             style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 40px 40px;">
        </div>

        {{-- Glow --}}
        <div class="pointer-events-none absolute left-1/4 top-1/3 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-600/20 blur-3xl"></div>

        <div class="relative z-10 w-full max-w-sm">
            {{-- Logo --}}
            <div class="mb-10 flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600">
                    <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                    </svg>
                </div>
                <div>
                    <p class="text-lg font-bold leading-none text-white">Big Party</p>
                    <p class="text-lg font-bold leading-none text-blue-400">Computer</p>
                </div>
            </div>

            <h1 class="mb-3 text-3xl font-bold text-white">Sistem Monitoring<br>Layanan Servis</h1>
            <p class="mb-10 text-sm leading-relaxed text-slate-400">
                Platform digital terpadu untuk manajemen perbaikan komputer — dari penerimaan perangkat hingga penyelesaian transaksi.
            </p>

            {{-- Feature list --}}
            <ul class="space-y-4">
                @foreach ([
                    ['Monitoring status perbaikan real-time',    'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Manajemen antrian & distribusi teknisi',   'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['Laporan transaksi & rekap pendapatan',     'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ] as [$label, $path])
                <li class="flex items-start gap-3">
                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-600/20 mt-0.5">
                        <svg class="h-3.5 w-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                        </svg>
                    </span>
                    <span class="text-sm text-slate-300">{{ $label }}</span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ===== RIGHT PANEL — form ===== --}}
    <div class="flex w-full flex-col items-center justify-center bg-white px-6 py-12 sm:px-12 lg:w-1/2">

        {{-- Mobile logo --}}
        <div class="mb-8 flex items-center gap-3 lg:hidden">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600">
                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18"/>
                </svg>
            </div>
            <div>
                <p class="font-bold leading-none text-slate-900">Big Party Computer</p>
                <p class="text-xs text-slate-500">Sistem Monitoring Servis</p>
            </div>
        </div>

        <div class="w-full max-w-sm">

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Masuk ke Sistem</h2>
                <p class="mt-1 text-sm text-slate-500">Gunakan akun Admin atau Teknisi Anda</p>
            </div>

            <form wire:submit="login" class="space-y-5">

                {{-- Email --}}
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                    <input
                        wire:model="email"
                        id="email"
                        type="email"
                        autocomplete="email"
                        placeholder="admin@example.com"
                        class="block w-full rounded-lg border px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1
                               @error('email') border-red-400 bg-red-50 @else border-slate-300 bg-white @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <input
                            wire:model="password"
                            id="password"
                            :type="show ? 'text' : 'password'"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full rounded-lg border px-4 py-2.5 pr-11 text-sm text-slate-900 placeholder-slate-400 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1
                                   @error('password') border-red-400 bg-red-50 @else border-slate-300 bg-white @enderror"
                        >
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600"
                            tabindex="-1"
                        >
                            <svg x-show="!show" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="show" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" x-cloak>
                                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input
                        wire:model="remember"
                        id="remember"
                        type="checkbox"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                    >
                    <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="relative w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-70"
                >
                    <span wire:loading.remove>Masuk</span>
                    <span wire:loading class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Memproses…
                    </span>
                </button>

            </form>

            <p class="mt-8 text-center text-xs text-slate-400">
                Big Party Computer &copy; {{ date('Y') }}
            </p>
        </div>
    </div>
</div>
