<div>
    {{-- Filter form --}}
    <div class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="mb-4 text-sm font-semibold text-slate-900">Filter Periode Laporan</h2>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="mb-1 block text-sm font-medium text-slate-700">Dari Tanggal</label>
                <input wire:model="dari" type="date"
                       class="w-full rounded-lg border px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('dari') border-red-400 @else border-slate-300 @enderror">
                @error('dari') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex-1">
                <label class="mb-1 block text-sm font-medium text-slate-700">Sampai Tanggal</label>
                <input wire:model="sampai" type="date"
                       class="w-full rounded-lg border px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('sampai') border-red-400 @else border-slate-300 @enderror">
                @error('sampai') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="flex gap-2">
                <button wire:click="generate" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-70">
                    <span wire:loading.remove wire:target="generate">Tampilkan</span>
                    <span wire:loading wire:target="generate">Memuat…</span>
                </button>
                @if ($sudahGenerate && $laporan->count() > 0)
                <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a1 1 0 001 1h8a1 1 0 001-1v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a1 1 0 00-1-1H6a1 1 0 00-1 1zm2 0h6v3H7V4zm-1 9v-1h8v1a1 1 0 01-1 1H7a1 1 0 01-1-1zm-2-4a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                    Cetak
                </button>
                @endif
            </div>
        </div>
    </div>

    @if ($sudahGenerate)
    {{-- Summary cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ([
            ['Total Servis',        $totalServis,                              '', 'bg-blue-50 text-blue-700'],
            ['Total Pendapatan',    'Rp ' . number_format($totalPendapatan, 0, ',', '.'), '', 'bg-emerald-50 text-emerald-700'],
            ['Total Biaya Jasa',    'Rp ' . number_format($totalJasa, 0, ',', '.'),      '', 'bg-violet-50 text-violet-700'],
            ['Total Sparepart',     'Rp ' . number_format($totalSparepart, 0, ',', '.'), '', 'bg-amber-50 text-amber-700'],
        ] as [$label, $value, , $color])
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium text-slate-500">{{ $label }}</p>
            <p class="mt-1 text-lg font-bold text-slate-900 {{ $color === '' ? '' : '' }}">{{ $value }}</p>
        </div>
        @endforeach
    </div>

    {{-- Detail table --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm" id="print-area">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">
                Detail Laporan: {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} — {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/60">
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Nomor Nota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Perangkat</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">Biaya Jasa</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">Sparepart</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Tgl Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($laporan as $i => $t)
                    <tr class="hover:bg-slate-50/40">
                        <td class="px-4 py-3 text-slate-400">{{ $i + 1 }}</td>
                        <td class="px-4 py-3 font-medium text-blue-700">{{ $t->servis->nomor_nota ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-900">{{ $t->servis->perangkat->pelanggan->nama }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $t->servis->perangkat->jenis_perangkat }}</td>
                        <td class="px-4 py-3 text-right text-slate-700">Rp {{ number_format($t->biaya_jasa, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-slate-700">Rp {{ number_format($t->biaya_sparepart, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-slate-900">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $t->tanggal_bayar->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-sm text-slate-400">Tidak ada transaksi dalam periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
                @if ($laporan->count() > 0)
                <tfoot>
                    <tr class="border-t-2 border-slate-200 bg-slate-50">
                        <td colspan="4" class="px-4 py-3 text-sm font-semibold text-slate-700">Jumlah: {{ $totalServis }} transaksi</td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-slate-700">Rp {{ number_format($totalJasa, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-sm font-semibold text-slate-700">Rp {{ number_format($totalSparepart, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-sm font-bold text-slate-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
    @endif
</div>
