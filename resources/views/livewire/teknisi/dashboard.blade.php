<div>
    {{-- Stats row --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        @foreach ([
            ['Total Ditugaskan', $stats['total_ditugaskan'], 'bg-blue-50 text-blue-600',    'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18'],
            ['Antri',            $stats['antri'],            'bg-amber-50 text-amber-600',   'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['Dikerjakan',       $stats['dalam_pengerjaan'], 'bg-violet-50 text-violet-600', 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
            ['Selesai',          $stats['selesai'],          'bg-emerald-50 text-emerald-600','M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as [$label, $value, $color, $path])
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="truncate text-xs font-medium text-slate-500">{{ $label }}</p>
                    <p class="mt-1 text-xl font-bold text-slate-900">{{ $value }}</p>
                </div>
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $color }}">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
                    </svg>
                </span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Active queue --}}
    <div class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">Pekerjaan Aktif</h2>
            <a href="{{ route('teknisi.antrian-servis') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/60">
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Nomor Nota</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Pelanggan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Perangkat</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Keluhan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Masuk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($activeServis as $s)
                    @php
                        [$badgeClass, $badgeLabel] = match($s->status) {
                            'antri'            => ['bg-amber-100 text-amber-800', 'Antri'],
                            'dalam_pengerjaan' => ['bg-blue-100 text-blue-800', 'Dikerjakan'],
                            default            => ['bg-slate-100 text-slate-700', $s->status],
                        };
                    @endphp
                    <tr class="transition-colors hover:bg-slate-50/50">
                        <td class="px-5 py-3 font-medium text-blue-700">{{ $s->nomor_nota ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-900">{{ $s->perangkat->pelanggan->nama }}</p>
                            <p class="text-xs text-slate-400">{{ $s->perangkat->pelanggan->telepon }}</p>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $s->perangkat->jenis_perangkat }} — {{ $s->perangkat->merek }}</td>
                        <td class="max-w-xs px-5 py-3 text-slate-500">
                            <p class="truncate">{{ $s->perangkat->keluhan }}</p>
                        </td>
                        <td class="px-5 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </td>
                        <td class="px-5 py-3 text-slate-400">{{ \Carbon\Carbon::parse($s->tanggal_masuk)->format('d F Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-10 text-center text-sm text-slate-400">Tidak ada pekerjaan aktif saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
