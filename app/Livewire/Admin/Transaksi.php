<?php

namespace App\Livewire\Admin;

use App\Models\Servis;
use App\Models\Transaksi as TransaksiModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Transaksi extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    // Modal
    public bool $showModal       = false;
    public bool $showDeleteModal = false;
    public ?int $editId          = null;
    public ?int $deleteId        = null;

    // Alert
    public string $message     = '';
    public string $messageType = 'success';

    // Form fields
    public ?int $servisId        = null;
    public string $biayaJasa      = '0';
    public string $biayaSparepart = '0';
    public string $total          = '0';
    public string $catatan        = '';
    public string $tanggalBayar   = '';
    public string $metodeBayar    = 'cash';

    public function mount(): void
    {
        $this->tanggalBayar = today()->toDateString();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function updatedBiayaJasa(): void      { $this->hitungTotal(); }
    public function updatedBiayaSparepart(): void { $this->hitungTotal(); }

    private function hitungTotal(): void
    {
        $this->total = (string) ((float) $this->biayaJasa + (float) $this->biayaSparepart);
    }

    public function openCreate(?int $servisId = null): void
    {
        $this->resetFormFields();
        $this->servisId     = $servisId;
        $this->tanggalBayar = today()->toDateString();
        $this->showModal    = true;
    }

    public function openEdit(int $id): void
    {
        $t = TransaksiModel::findOrFail($id);

        $this->editId         = $id;
        $this->servisId       = $t->servis_id;
        $this->biayaJasa      = (string) $t->biaya_jasa;
        $this->biayaSparepart = (string) $t->biaya_sparepart;
        $this->total          = (string) $t->total;
        $this->catatan        = $t->catatan ?? '';
        $this->tanggalBayar   = $t->tanggal_bayar->toDateString();
        $this->metodeBayar    = $t->metode_bayar ?? 'cash';
        $this->showModal      = true;
    }

    public function save(): void
    {
        $this->validate([
            'servisId'       => $this->editId ? 'nullable' : 'required|exists:servis,id',
            'biayaJasa'      => 'required|numeric|min:0',
            'biayaSparepart' => 'required|numeric|min:0',
            'tanggalBayar'   => 'required|date',
            'metodeBayar'    => 'required|in:cash,transfer',
        ], [
            'servisId.required'    => 'Servis wajib dipilih.',
            'biayaJasa.required'   => 'Biaya jasa wajib diisi.',
            'tanggalBayar.required' => 'Tanggal bayar wajib diisi.',
            'metodeBayar.required' => 'Metode bayar wajib dipilih.',
            'metodeBayar.in'       => 'Metode bayar tidak valid.',
        ]);

        $this->hitungTotal();

        $data = [
            'admin_id'        => Auth::id(),
            'biaya_jasa'      => (float) $this->biayaJasa,
            'biaya_sparepart' => (float) $this->biayaSparepart,
            'total'           => (float) $this->total,
            'catatan'         => $this->catatan ?: null,
            'tanggal_bayar'   => $this->tanggalBayar,
            'metode_bayar'    => $this->metodeBayar,
        ];

        if ($this->editId) {
            TransaksiModel::findOrFail($this->editId)->update($data);
            $this->message = 'Transaksi berhasil diperbarui.';
        } else {
            $data['servis_id'] = $this->servisId;
            TransaksiModel::create($data);

            // Auto-mark servis as selesai
            $servis = Servis::find($this->servisId);
            if ($servis && $servis->status !== Servis::STATUS_SELESAI) {
                $servis->update([
                    'status'          => Servis::STATUS_SELESAI,
                    'tanggal_selesai' => today(),
                ]);
            }

            $this->message = 'Transaksi berhasil disimpan.';
        }

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
        TransaksiModel::findOrFail($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId        = null;
        $this->message         = 'Transaksi berhasil dihapus.';
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
        $this->reset(['servisId', 'catatan', 'editId']);
        $this->biayaJasa      = '0';
        $this->biayaSparepart = '0';
        $this->total          = '0';
        $this->tanggalBayar   = today()->toDateString();
        $this->metodeBayar    = 'cash';
    }

    public function render(): View
    {
        $data = TransaksiModel::query()
            ->with(['servis.perangkat.pelanggan', 'admin'])
            ->when($this->search, fn ($q) =>
                $q->whereHas('servis', fn ($q) =>
                    $q->where('nomor_nota', 'like', "%{$this->search}%")
                      ->orWhereHas('perangkat.pelanggan', fn ($q) =>
                          $q->where('nama', 'like', "%{$this->search}%")
                      )
                )
            )
            ->latest()
            ->paginate(10);

        // Servis selesai tanpa transaksi — for create dropdown
        $servisTersedia = Servis::whereDoesntHave('transaksi')
            ->where('status', Servis::STATUS_SELESAI)
            ->with('perangkat.pelanggan')
            ->latest()
            ->get();

        return view('livewire.admin.transaksi', compact('data', 'servisTersedia'))
            ->layout('components.layouts.admin', ['heading' => 'Transaksi']);
    }
}
