<?php

namespace App\Livewire\Admin;

use App\Models\Pelanggan;
use App\Models\Perangkat;
use App\Models\Servis;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class PenerimaanPerangkat extends Component
{
    use WithPagination;

    const JENIS_PERANGKAT = [
        'Laptop', 'Desktop PC', 'Printer', 'Monitor',
        'Keyboard/Mouse', 'Hard Disk/SSD', 'Lainnya',
    ];

    #[Url]
    public string $search = '';

    #[Url]
    public string $filterStatus = '';

    // Modal
    public bool $showModal       = false;
    public bool $showDeleteModal = false;
    public ?int $editId          = null;
    public ?int $deleteId        = null;

    // Alert
    public string $message     = '';
    public string $messageType = 'success';

    // Form — Pelanggan
    public string $nama    = '';
    public string $telepon = '';
    public string $alamat  = '';

    // Form — Perangkat
    public string $jenisPerangkat = '';
    public string $merek          = '';
    public string $spesifikasi    = '';
    public string $keluhan        = '';
    public string $kelengkapan    = '';

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function openCreate(): void
    {
        $this->resetFormFields();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $servis    = Servis::with('perangkat.pelanggan')->findOrFail($id);
        $perangkat = $servis->perangkat;
        $pelanggan = $perangkat->pelanggan;

        $this->editId        = $id;
        $this->nama          = $pelanggan->nama;
        $this->telepon       = $pelanggan->telepon;
        $this->alamat        = $pelanggan->alamat ?? '';
        $this->jenisPerangkat= $perangkat->jenis_perangkat;
        $this->merek         = $perangkat->merek;
        $this->spesifikasi   = $perangkat->spesifikasi ?? '';
        $this->keluhan       = $perangkat->keluhan;
        $this->kelengkapan   = $perangkat->kelengkapan ?? '';
        $this->showModal     = true;
    }

    public function save(): void
    {
        $this->validate([
            'nama'          => 'required|string|max:255',
            'telepon'       => 'required|string|max:20',
            'jenisPerangkat'=> 'required|string|max:100',
            'merek'         => 'required|string|max:100',
            'keluhan'       => 'required|string|max:2000',
        ], [
            'nama.required'          => 'Nama pelanggan wajib diisi.',
            'telepon.required'       => 'Nomor telepon wajib diisi.',
            'jenisPerangkat.required'=> 'Jenis perangkat wajib dipilih.',
            'merek.required'         => 'Merek perangkat wajib diisi.',
            'keluhan.required'       => 'Keluhan pelanggan wajib diisi.',
        ]);

        DB::transaction(function () {
            if ($this->editId) {
                $servis = Servis::with('perangkat.pelanggan')->findOrFail($this->editId);
                $servis->perangkat->pelanggan->update([
                    'nama'    => $this->nama,
                    'telepon' => $this->telepon,
                    'alamat'  => $this->alamat ?: null,
                ]);
                $servis->perangkat->update([
                    'jenis_perangkat' => $this->jenisPerangkat,
                    'merek'           => $this->merek,
                    'spesifikasi'     => $this->spesifikasi ?: null,
                    'keluhan'         => $this->keluhan,
                    'kelengkapan'     => $this->kelengkapan ?: null,
                ]);
                $this->message = 'Data penerimaan berhasil diperbarui.';
            } else {
                $pelanggan = Pelanggan::create([
                    'nama'    => $this->nama,
                    'telepon' => $this->telepon,
                    'alamat'  => $this->alamat ?: null,
                ]);
                $perangkat = Perangkat::create([
                    'pelanggan_id'    => $pelanggan->id,
                    'jenis_perangkat' => $this->jenisPerangkat,
                    'merek'           => $this->merek,
                    'spesifikasi'     => $this->spesifikasi ?: null,
                    'keluhan'         => $this->keluhan,
                    'kelengkapan'     => $this->kelengkapan ?: null,
                ]);
                Servis::create(['perangkat_id' => $perangkat->id]);
                $this->message = 'Penerimaan perangkat berhasil disimpan.';
            }
        });

        $this->messageType = 'success';
        $this->showModal   = false;
        $this->resetFormFields();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId        = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        $servis = Servis::with(['perangkat.pelanggan', 'transaksi'])->findOrFail($this->deleteId);

        if ($servis->transaksi) {
            $this->message         = 'Tidak dapat menghapus data yang sudah memiliki transaksi.';
            $this->messageType     = 'error';
            $this->showDeleteModal = false;
            return;
        }

        DB::transaction(function () use ($servis) {
            $perangkat = $servis->perangkat;
            $pelanggan = $perangkat->pelanggan;
            $perangkat->delete(); // cascades servis
            if ($pelanggan->perangkat()->count() === 0) {
                $pelanggan->delete();
            }
        });

        $this->showDeleteModal = false;
        $this->deleteId        = null;
        $this->message         = 'Data penerimaan berhasil dihapus.';
        $this->messageType     = 'success';
    }

    public function closeModal(): void
    {
        $this->showModal       = false;
        $this->showDeleteModal = false;
        $this->resetFormFields();
    }

    private function resetFormFields(): void
    {
        $this->reset([
            'nama', 'telepon', 'alamat',
            'jenisPerangkat', 'merek', 'spesifikasi', 'keluhan', 'kelengkapan',
            'editId',
        ]);
    }

    public function render(): View
    {
        $data = Servis::query()
            ->with(['perangkat.pelanggan', 'teknisi'])
            ->when($this->search, fn ($q) =>
                $q->where('nomor_nota', 'like', "%{$this->search}%")
                  ->orWhereHas('perangkat.pelanggan', fn ($q) =>
                      $q->where('nama', 'like', "%{$this->search}%")
                        ->orWhere('telepon', 'like', "%{$this->search}%")
                  )
            )
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.penerimaan-perangkat', ['data' => $data])
            ->layout('components.layouts.admin', ['heading' => 'Penerimaan Perangkat']);
    }
}
