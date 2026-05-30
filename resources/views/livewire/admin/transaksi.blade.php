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
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative max-w-xs flex-1">
            <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
            </svg>
            <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari nomor nota / pelanggan…"
                   class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-4 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <button wire:click="openCreate()"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Buat Transaksi
        </button>
    </div>

    {{-- Table --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/60">
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Nomor Nota</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Pelanggan</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">Biaya Jasa</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">Biaya Sparepart</th>
                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-slate-400">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Tgl Bayar</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Admin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($data as $t)
                    <tr class="transition-colors hover:bg-slate-50/40">
                        <td class="px-4 py-3 font-medium text-blue-700">{{ $t->servis->nomor_nota ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-900">{{ $t->servis->perangkat->pelanggan->nama }}</p>
                            <p class="text-xs text-slate-400">{{ $t->servis->perangkat->jenis_perangkat }}</p>
                        </td>
                        <td class="px-4 py-3 text-right text-slate-700">Rp {{ number_format($t->biaya_jasa, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right text-slate-700">Rp {{ number_format($t->biaya_sparepart, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-semibold text-slate-900">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $t->tanggal_bayar->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $t->admin->name }}</td>
                        <td class="px-4 py-3">
                            <div class="flex gap-2">
                                <button wire:click="openEdit({{ $t->id }})"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-colors">Edit</button>
                                <button wire:click="confirmDelete({{ $t->id }})"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-red-300 hover:bg-red-50 hover:text-red-700 transition-colors">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-sm text-slate-400">Belum ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($data->hasPages())
        <div class="border-t border-slate-100 px-5 py-3">{{ $data->links() }}</div>
        @endif
    </div>

    {{-- ===== CREATE / EDIT MODAL ===== --}}
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto p-4 sm:items-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative z-10 my-6 w-full max-w-lg rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">{{ $editId ? 'Edit Transaksi' : 'Buat Transaksi' }}</h3>
                <button wire:click="closeModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="space-y-4 p-6">
                @if (!$editId)
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Servis (Selesai) <span class="text-red-500">*</span></label>
                    <select wire:model="servisId" class="w-full rounded-lg border px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('servisId') border-red-400 @else border-slate-300 @enderror">
                        <option value="">Pilih nomor nota…</option>
                        @foreach ($servisTersedia as $s)
                        <option value="{{ $s->id }}">{{ $s->nomor_nota }} — {{ $s->perangkat->pelanggan->nama }}</option>
                        @endforeach
                    </select>
                    @error('servisId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    @if ($servisTersedia->isEmpty())
                    <p class="mt-1 text-xs text-amber-600">Tidak ada servis selesai yang belum memiliki transaksi.</p>
                    @endif
                </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Biaya Jasa (Rp) <span class="text-red-500">*</span></label>
                        <input wire:model.live="biayaJasa" type="number" min="0" step="1000"
                               class="w-full rounded-lg border px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('biayaJasa') border-red-400 @else border-slate-300 @enderror">
                        @error('biayaJasa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Biaya Sparepart (Rp) <span class="text-red-500">*</span></label>
                        <input wire:model.live="biayaSparepart" type="number" min="0" step="1000"
                               class="w-full rounded-lg border px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('biayaSparepart') border-red-400 @else border-slate-300 @enderror">
                        @error('biayaSparepart') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="rounded-xl bg-slate-50 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-600">Total Pembayaran</span>
                        <span class="text-lg font-bold text-slate-900">Rp {{ number_format((float)$total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal Bayar <span class="text-red-500">*</span></label>
                    <input wire:model="tanggalBayar" type="date"
                           class="w-full rounded-lg border px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('tanggalBayar') border-red-400 @else border-slate-300 @enderror">
                    @error('tanggalBayar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Catatan</label>
                    <textarea wire:model="catatan" rows="2" placeholder="Catatan pembayaran (opsional)…"
                              class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                <button wire:click="closeModal" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                <button wire:click="save" wire:loading.attr="disabled"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-70">
                    <span wire:loading.remove wire:target="save">{{ $editId ? 'Perbarui' : 'Simpan' }}</span>
                    <span wire:loading wire:target="save">Menyimpan…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Modal --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative z-10 w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Hapus Transaksi?</h3>
                    <p class="mt-1 text-sm text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button wire:click="closeModal" class="flex-1 rounded-lg border border-slate-300 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                <button wire:click="delete" class="flex-1 rounded-lg bg-red-600 py-2 text-sm font-semibold text-white hover:bg-red-700">Ya, Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
