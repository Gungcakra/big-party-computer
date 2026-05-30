<div>
    {{-- Alert --}}
    @if ($message)
    <div class="mb-4 flex items-center gap-3 rounded-xl border px-4 py-3
                {{ $messageType === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-800' }}"
         x-data="{ show: true }" x-show="show">
        <p class="flex-1 text-sm font-medium">{{ $message }}</p>
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100">✕</button>
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center">
        <div class="relative flex-1 max-w-xs">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
            </svg>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari nomor nota / pelanggan…"
                   class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-4 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <div class="flex gap-2">
            @foreach ([''=>'Semua', 'antri'=>'Antri', 'dalam_pengerjaan'=>'Dikerjakan', 'selesai'=>'Selesai'] as $val => $lbl)
            <button wire:click="$set('filterStatus', '{{ $val }}')"
                    class="rounded-lg border px-3 py-2 text-xs font-medium transition-colors
                           {{ $filterStatus === $val ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 bg-white text-slate-600 hover:bg-slate-50' }}">
                {{ $lbl }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/60">
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Nomor Nota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Pelanggan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Perangkat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Teknisi</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Masuk</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($data as $servis)
                    @php
                        [$badgeClass, $badgeLabel] = match($servis->status) {
                            'antri'            => ['bg-amber-100 text-amber-800', 'Antri'],
                            'dalam_pengerjaan' => ['bg-blue-100 text-blue-800', 'Dikerjakan'],
                            'selesai'          => ['bg-emerald-100 text-emerald-800', 'Selesai'],
                            default            => ['bg-slate-100 text-slate-700', $servis->status],
                        };
                    @endphp
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="px-4 py-3 font-medium text-blue-700">{{ $servis->nomor_nota ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-900">{{ $servis->perangkat->pelanggan->nama }}</p>
                            <p class="text-xs text-slate-400">{{ $servis->perangkat->pelanggan->telepon }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $servis->perangkat->jenis_perangkat }} — {{ $servis->perangkat->merek }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $servis->teknisi?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-400">{{ $servis->tanggal_masuk->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="openEdit({{ $servis->id }})"
                                    class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                Kelola
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-sm text-slate-400">Tidak ada data servis.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($data->hasPages())
        <div class="border-t border-slate-100 px-5 py-3">{{ $data->links() }}</div>
        @endif
    </div>

    {{-- ===== KELOLA MODAL ===== --}}
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative z-10 w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">Kelola Servis</h3>
                <button wire:click="closeModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="space-y-4 p-6">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Assign Teknisi</label>
                    <select wire:model="teknisiId" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <option value="">— Belum ditugaskan —</option>
                        @foreach ($teknisiList as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Status <span class="text-red-500">*</span></label>
                    <select wire:model="statusEdit" class="w-full rounded-lg border px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('statusEdit') border-red-400 @else border-slate-300 @enderror">
                        <option value="antri">Antri</option>
                        <option value="dalam_pengerjaan">Dalam Pengerjaan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    @error('statusEdit') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Diagnosa Kerusakan</label>
                    <textarea wire:model="diagnosa" rows="3" placeholder="Hasil diagnosa dari teknisi…"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Catatan Tambahan</label>
                    <textarea wire:model="catatan" rows="2" placeholder="Catatan lainnya…"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                <button wire:click="closeModal" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                <button wire:click="save" wire:loading.attr="disabled"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-70">
                    <span wire:loading.remove wire:target="save">Simpan</span>
                    <span wire:loading wire:target="save">Menyimpan…</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
