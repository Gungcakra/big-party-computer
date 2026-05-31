<div class="min-h-screen flex flex-col bg-[#0d1b2e]">

    {{-- ===== HERO ===== --}}
    <section class="relative flex flex-1 flex-col items-center justify-center overflow-hidden px-4 py-24 sm:px-6">

        {{-- Decorative glow blobs --}}
        <div class="pointer-events-none absolute -top-32 left-1/2 h-96 w-96 -translate-x-1/2 rounded-full bg-blue-600/20 blur-3xl"></div>
        <div class="pointer-events-none absolute bottom-0 right-8 h-64 w-64 rounded-full bg-blue-500/10 blur-3xl"></div>
        <div class="pointer-events-none absolute left-8 top-1/3 h-48 w-48 rounded-full bg-blue-800/20 blur-2xl"></div>

        <div class="relative z-10 flex w-full max-w-lg flex-col items-center text-center">

            {{-- Logo --}}
            <div class="mb-10 rounded-xl bg-white px-5 py-2.5 shadow-lg shadow-black/40">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-10 w-auto">
            </div>

            {{-- Headline --}}
            <h1 class="text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl">
                Cek Status Perbaikan
                <span class="block text-blue-400">Perangkat Anda</span>
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-slate-400 sm:text-base">
                Masukkan nomor nota servis yang diterima saat menyerahkan perangkat<br class="hidden sm:block">
                untuk melihat status perbaikan secara <span class="text-slate-300">real-time</span>.
            </p>

            {{-- Search form --}}
            <div class="mt-10 w-full">
                <div class="flex flex-col gap-2.5 sm:flex-row">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <input
                            wire:model="nomorNota"
                            wire:keydown.enter="cek"
                            type="text"
                            placeholder="Masukkan nomor nota servis…"
                            class="w-full rounded-xl border bg-white/8 py-3.5 pl-11 pr-4 text-sm text-white placeholder-slate-500 backdrop-blur-sm transition focus:outline-none focus:ring-2 focus:ring-blue-500/60
                                   @error('nomorNota') border-red-500/60 @else border-white/10 @enderror"
                        >
                    </div>
                    <button
                        wire:click="cek"
                        wire:loading.attr="disabled"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-500 active:bg-blue-700 disabled:opacity-60"
                    >
                        <svg wire:loading.remove wire:target="cek" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <svg wire:loading wire:target="cek" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                        </svg>
                        <span wire:loading.remove wire:target="cek">Cek Status</span>
                        <span wire:loading wire:target="cek">Mencari…</span>
                    </button>
                </div>
                @error('nomorNota')
                <p class="mt-2 text-left text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Not found --}}
            @if ($sudahCari && !$result)
            <div class="mt-6 w-full rounded-xl border border-red-500/20 bg-red-500/10 p-5 text-center">
                <svg class="mx-auto mb-2 h-8 w-8 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="font-semibold text-red-300">Nomor nota tidak ditemukan</p>
                <p class="mt-1 text-xs text-red-400/80">Pastikan nomor yang Anda masukkan sudah benar.</p>
            </div>
            @endif

        </div>
    </section>

    {{-- ===== RESULT CARD ===== --}}
    @if ($result)
    @php
        [$badgeClass, $badgeLabel, $badgeIcon, $accentBar] = match($result['status']) {
            'antri' => [
                'border-slate-600 bg-slate-700/50 text-slate-300',
                'Antri',
                'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg-slate-500',
            ],
            'dalam_pengerjaan' => [
                'border-amber-600/50 bg-amber-500/15 text-amber-300',
                'Dalam Pengerjaan',
                'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
                'bg-amber-400',
            ],
            'selesai' => [
                'border-emerald-600/50 bg-emerald-500/15 text-emerald-300',
                'Selesai',
                'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg-emerald-400',
            ],
            default => [
                'border-slate-600 bg-slate-700/50 text-slate-300',
                $result['status'],
                'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'bg-slate-500',
            ],
        };
    @endphp

    <section class="border-t border-white/5 px-4 pb-16 pt-8 sm:px-6">
        <div class="mx-auto max-w-lg">

            {{-- Card --}}
            <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#112240] shadow-2xl shadow-black/40">

                {{-- Card header: nomor nota + status --}}
                <div class="relative flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    {{-- Left accent bar --}}
                    <div class="absolute inset-y-0 left-0 w-1 {{ $accentBar }} rounded-l-2xl"></div>
                    <div class="pl-2">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Nomor Nota Servis</p>
                        <p class="mt-1 text-xl font-bold tracking-wide text-blue-400">{{ $result['nomor_nota'] }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 self-start rounded-full border px-3.5 py-1.5 text-xs font-semibold sm:self-auto {{ $badgeClass }}">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $badgeIcon }}"/>
                        </svg>
                        {{ $badgeLabel }}
                    </span>
                </div>

                <div class="divide-y divide-white/5">

                    {{-- Pelanggan + Perangkat --}}
                    <div class="grid grid-cols-1 gap-5 px-6 py-5 sm:grid-cols-2">
                        <div>
                            <p class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Pelanggan</p>
                            <p class="font-semibold text-white">{{ $result['nama_pelanggan'] }}</p>
                            <p class="mt-1 text-xs text-slate-400">Masuk: {{ \Carbon\Carbon::parse($result['tanggal_masuk'])->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <p class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Perangkat</p>
                            <p class="font-semibold text-white">{{ $result['jenis_perangkat'] }} — {{ $result['merek'] }}</p>
                            <p class="mt-1 text-xs leading-relaxed text-slate-400">{{ $result['keluhan'] }}</p>
                        </div>
                    </div>

                    {{-- Pengerjaan --}}
                    @if ($result['teknisi'] || $result['diagnosa'] || $result['catatan'] || $result['tanggal_selesai'])
                    <div class="px-6 py-5">
                        <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Info Pengerjaan</p>
                        <dl class="space-y-2.5 text-sm">
                            @if ($result['teknisi'])
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Teknisi</dt>
                                <dd class="font-medium text-slate-200">{{ $result['teknisi'] }}</dd>
                            </div>
                            @endif
                            @if ($result['diagnosa'])
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Diagnosa</dt>
                                <dd class="font-medium text-slate-200">{{ $result['diagnosa'] }}</dd>
                            </div>
                            @endif
                            @if ($result['catatan'])
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Catatan</dt>
                                <dd class="text-slate-300">{{ $result['catatan'] }}</dd>
                            </div>
                            @endif
                            @if ($result['tanggal_selesai'])
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Selesai</dt>
                                <dd class="font-semibold text-emerald-400">{{ \Carbon\Carbon::parse($result['tanggal_selesai'])->translatedFormat('d F Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @else
                    <div class="px-6 py-5">
                        <p class="text-sm text-slate-500">Perangkat masih dalam antrian dan belum ditangani teknisi.</p>
                    </div>
                    @endif

                    {{-- Transaksi / Payment --}}
                    @if ($result['transaksi'])
                    <div class="px-6 py-5">
                        <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Informasi Pembayaran</p>
                        <dl class="space-y-2.5 text-sm">
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Biaya Jasa</dt>
                                <dd class="font-medium text-slate-200">Rp {{ number_format($result['transaksi']['biaya_jasa'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Biaya Sparepart</dt>
                                <dd class="font-medium text-slate-200">Rp {{ number_format($result['transaksi']['biaya_sparepart'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex gap-3 border-t border-white/10 pt-2.5">
                                <dt class="w-28 shrink-0 font-semibold text-slate-400">Total</dt>
                                <dd class="font-bold text-emerald-400 text-base">Rp {{ number_format($result['transaksi']['total'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Metode Bayar</dt>
                                <dd>
                                    @if ($result['transaksi']['metode_bayar'] === 'transfer')
                                    <span class="inline-flex items-center rounded-full border border-blue-500/40 bg-blue-500/15 px-2.5 py-0.5 text-xs font-semibold text-blue-300">Transfer</span>
                                    @else
                                    <span class="inline-flex items-center rounded-full border border-emerald-500/40 bg-emerald-500/15 px-2.5 py-0.5 text-xs font-semibold text-emerald-300">Cash</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Tgl Bayar</dt>
                                <dd class="font-medium text-slate-200">{{ \Carbon\Carbon::parse($result['transaksi']['tanggal_bayar'])->translatedFormat('d F Y') }}</dd>
                            </div>
                            @if ($result['transaksi']['catatan'])
                            <div class="flex gap-3">
                                <dt class="w-28 shrink-0 text-slate-500">Catatan</dt>
                                <dd class="text-slate-300">{{ $result['transaksi']['catatan'] }}</dd>
                            </div>
                            @endif
                        </dl>

                        {{-- Download Invoice --}}
                        <div class="mt-5">
                            <a href="{{ route('invoice.download', ['nota' => $result['nomor_nota']]) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/30 transition hover:bg-blue-500 active:bg-blue-700">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                                </svg>
                                Download Invoice PDF
                            </a>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Cek ulang --}}
            <p class="mt-5 text-center text-xs text-slate-600">
                Ingin cek nomor lain?
                <button wire:click="$set('result', null); $set('sudahCari', false); $set('nomorNota', '')"
                        class="font-medium text-blue-400 transition hover:text-blue-300">
                    Cari lagi
                </button>
            </p>
        </div>
    </section>
    @endif

    {{-- ===== FOOTER ===== --}}
    <footer class="mt-auto border-t border-white/5 py-6 text-center">
        <p class="text-xs text-slate-600">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </footer>

</div>
