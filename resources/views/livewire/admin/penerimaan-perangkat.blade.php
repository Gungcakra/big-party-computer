<div>
    {{-- Alert --}}
    @if ($message)
    <div class="mb-4 flex items-center gap-3 rounded-xl border px-4 py-3
                {{ $messageType === 'success' ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-red-200 bg-red-50 text-red-800' }}"
         x-data="{ show: true }" x-show="show">
        <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            @if ($messageType === 'success')
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            @else
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            @endif
        </svg>
        <p class="flex-1 text-sm font-medium">{{ $message }}</p>
        <button @click="show = false" class="shrink-0 rounded p-0.5 opacity-60 hover:opacity-100">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
        </button>
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 flex-col gap-2 sm:flex-row">
            <div class="relative flex-1 max-w-xs">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="Cari nomor nota / pelanggan…"
                       class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-4 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            </div>
            <select wire:model.live="filterStatus" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="antri">Antri</option>
                <option value="dalam_pengerjaan">Dalam Pengerjaan</option>
                <option value="selesai">Selesai</option>
            </select>
        </div>
        <button wire:click="openCreate"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Tambah Penerimaan
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
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Perangkat</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Keluhan</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-slate-400">Tanggal Masuk</th>
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
                        <td class="px-4 py-3 text-slate-600">
                            <p>{{ $servis->perangkat->jenis_perangkat }}</p>
                            <p class="text-xs text-slate-400">{{ $servis->perangkat->merek }}</p>
                        </td>
                        <td class="max-w-[200px] px-4 py-3 text-slate-600">
                            <p class="line-clamp-2 text-xs">{{ $servis->perangkat->keluhan }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $badgeClass }}">{{ $badgeLabel }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ \Carbon\Carbon::parse($servis->tanggal_masuk)->format('d Fz Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <button wire:click="openEdit({{ $servis->id }})"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $servis->id }})"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:border-red-300 hover:bg-red-50 hover:text-red-700 transition-colors">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-sm text-slate-400">
                            <svg class="mx-auto mb-2 h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Belum ada data penerimaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($data->hasPages())
        <div class="border-t border-slate-100 px-5 py-3">
            {{ $data->links() }}
        </div>
        @endif
    </div>

    {{-- ===== CREATE / EDIT MODAL ===== --}}
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto p-4 sm:items-center">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative z-10 my-6 w-full max-w-2xl rounded-2xl bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-base font-semibold text-slate-900">
                    {{ $editId ? 'Edit Data Penerimaan' : 'Tambah Penerimaan Perangkat' }}
                </h3>
                <button wire:click="closeModal" class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                </button>
            </div>

            <div class="p-6">
                {{-- Pelanggan section --}}
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Data Pelanggan</p>
                <div class="mb-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input wire:model="nama" type="text" placeholder="Nama lengkap"
                               class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('nama') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                        @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input wire:model="telepon" type="text" placeholder="08xx-xxxx-xxxx"
                               class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('telepon') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                        @error('telepon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Alamat</label>
                        <input wire:model="alamat" type="text" placeholder="Alamat (opsional)"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Perangkat section --}}
                <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-400">Data Perangkat</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Jenis Perangkat <span class="text-red-500">*</span></label>
                        <select wire:model="jenisPerangkat"
                                class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('jenisPerangkat') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                            <option value="">Pilih jenis…</option>
                            @foreach (\App\Livewire\Admin\PenerimaanPerangkat::JENIS_PERANGKAT as $j)
                            <option value="{{ $j }}">{{ $j }}</option>
                            @endforeach
                        </select>
                        @error('jenisPerangkat') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Merek <span class="text-red-500">*</span></label>
                        <input wire:model="merek" type="text" placeholder="Contoh: Asus, HP, Dell…"
                               class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('merek') border-red-400 bg-red-50 @else border-slate-300 @enderror">
                        @error('merek') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Spesifikasi</label>
                        <input wire:model="spesifikasi" type="text" placeholder="RAM, Prosesor, dll. (opsional)"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Keluhan <span class="text-red-500">*</span></label>
                        <textarea wire:model="keluhan" rows="3" placeholder="Deskripsikan keluhan / kerusakan perangkat…"
                                  class="w-full rounded-lg border px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('keluhan') border-red-400 bg-red-50 @else border-slate-300 @enderror"></textarea>
                        @error('keluhan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Kelengkapan</label>
                        <input wire:model="kelengkapan" type="text" placeholder="Charger, tas, dll. (opsional)"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                <button wire:click="closeModal" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                <button wire:click="save" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-70">
                    <span wire:loading.remove wire:target="save">{{ $editId ? 'Perbarui' : 'Simpan' }}</span>
                    <span wire:loading wire:target="save">Menyimpan…</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== DELETE MODAL ===== --}}
    @if ($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" wire:click="closeModal"></div>
        <div class="relative z-10 w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Hapus Data Penerimaan?</h3>
                    <p class="mt-1 text-sm text-slate-500">Data pelanggan dan perangkat terkait juga akan dihapus. Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <div class="flex gap-3">
                <button wire:click="closeModal" class="flex-1 rounded-lg border border-slate-300 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Batal</button>
                <button wire:click="delete" wire:loading.attr="disabled"
                        class="flex-1 rounded-lg bg-red-600 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-70">
                    <span wire:loading.remove wire:target="delete">Ya, Hapus</span>
                    <span wire:loading wire:target="delete">Menghapus…</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
