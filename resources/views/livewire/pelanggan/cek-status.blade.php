<div>
    {{-- Page title --}}
    <div class="mb-8 text-center">
        <h1 class="text-2xl font-bold text-slate-900">Cek Status Perbaikan</h1>
        <p class="mt-2 text-sm text-slate-500">Masukkan nomor nota servis yang Anda terima saat menyerahkan perangkat.</p>
    </div>

    {{-- Search form --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                <input
                    wire:model="nomorNota"
                    wire:keydown.enter="cek"
                    type="text"
                    placeholder="Contoh: BPC/2026/05/0001"
                    class="w-full rounded-lg border px-3 py-2.5 pl-9 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('nomorNota') border-red-400 bg-red-50 @else border-slate-300 @enderror"
                >
                @error('nomorNota')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button
                wire:click="cek"
                wire:loading.attr="disabled"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-70 sm:shrink-0"
            >
                <span wire:loading.remove wire:target="cek">Cek Status</span>
                <span wire:loading wire:target="cek">Mencari…</span>
            </button>
        </div>
    </div>

    {{-- Not found --}}
    @if ($sudahCari && !$result)
    <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 p-6 text-center">
        <svg class="mx-auto mb-3 h-10 w-10 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-semibold text-red-700">Nomor nota servis tidak ditemukan</p>
        <p class="mt-1 text-sm text-red-500">Pastikan nomor yang Anda masukkan sudah benar.</p>
    </div>
    @endif

    {{-- Result card --}}
    @if ($result)
    @php
        [$badgeClass, $badgeLabel, $badgeIcon] = match($result['status']) {
            'antri'            => ['bg-slate-100 text-slate-700 border-slate-300',       'Antri',           'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            'dalam_pengerjaan' => ['bg-amber-100 text-amber-800 border-amber-300',        'Dalam Pengerjaan','M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            'selesai'          => ['bg-emerald-100 text-emerald-800 border-emerald-300',  'Selesai',         'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            default            => ['bg-slate-100 text-slate-700 border-slate-300',        $result['status'], 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        };
    @endphp

    <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        {{-- Status banner --}}
        <div class="border-b border-slate-100 bg-slate-50/60 px-6 py-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-400">Nomor Nota</p>
                    <p class="mt-0.5 text-lg font-bold tracking-wide text-blue-700">{{ $result['nomor_nota'] }}</p>
                </div>
                <span class="inline-flex items-center gap-2 self-start rounded-full border px-4 py-1.5 text-sm font-semibold {{ $badgeClass }}">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $badgeIcon }}"/>
                    </svg>
                    {{ $badgeLabel }}
                </span>
            </div>
        </div>

        <div class="divide-y divide-slate-100">

            {{-- Pelanggan --}}
            <div class="px-6 py-4">
                <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Data Pelanggan</p>
                <dl class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                    <div class="flex gap-2">
                        <dt class="w-28 shrink-0 text-slate-500">Nama</dt>
                        <dd class="font-medium text-slate-900">{{ $result['nama_pelanggan'] }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="w-28 shrink-0 text-slate-500">Tanggal Masuk</dt>
                        <dd class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($result['tanggal_masuk'])->translatedFormat('d F Y') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Perangkat --}}
            <div class="px-6 py-4">
                <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Data Perangkat</p>
                <dl class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                    <div class="flex gap-2">
                        <dt class="w-28 shrink-0 text-slate-500">Jenis</dt>
                        <dd class="font-medium text-slate-900">{{ $result['jenis_perangkat'] }}</dd>
                    </div>
                    <div class="flex gap-2">
                        <dt class="w-28 shrink-0 text-slate-500">Merek</dt>
                        <dd class="font-medium text-slate-900">{{ $result['merek'] }}</dd>
                    </div>
                    <div class="flex gap-2 sm:col-span-2">
                        <dt class="w-28 shrink-0 text-slate-500">Keluhan</dt>
                        <dd class="font-medium text-slate-900">{{ $result['keluhan'] }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Servis --}}
            <div class="px-6 py-4">
                <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Info Servis</p>
                <dl class="grid grid-cols-1 gap-2 text-sm sm:grid-cols-2">
                    @if ($result['teknisi'])
                    <div class="flex gap-2">
                        <dt class="w-28 shrink-0 text-slate-500">Teknisi</dt>
                        <dd class="font-medium text-slate-900">{{ $result['teknisi'] }}</dd>
                    </div>
                    @endif
                    @if ($result['tanggal_selesai'])
                    <div class="flex gap-2">
                        <dt class="w-28 shrink-0 text-slate-500">Tanggal Selesai</dt>
                        <dd class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($result['tanggal_selesai'])->translatedFormat('d F Y') }}</dd>
                    </div>
                    @endif
                    @if ($result['diagnosa'])
                    <div class="flex gap-2 sm:col-span-2">
                        <dt class="w-28 shrink-0 text-slate-500">Diagnosa</dt>
                        <dd class="font-medium text-slate-900">{{ $result['diagnosa'] }}</dd>
                    </div>
                    @endif
                    @if ($result['catatan'])
                    <div class="flex gap-2 sm:col-span-2">
                        <dt class="w-28 shrink-0 text-slate-500">Catatan</dt>
                        <dd class="text-slate-700">{{ $result['catatan'] }}</dd>
                    </div>
                    @endif
                    @if (!$result['teknisi'] && !$result['diagnosa'] && !$result['catatan'] && !$result['tanggal_selesai'])
                    <p class="text-sm text-slate-400 sm:col-span-2">Perangkat Anda masih dalam antrian dan belum ditangani teknisi.</p>
                    @endif
                </dl>
            </div>
        </div>
    </div>
    @endif
</div>
