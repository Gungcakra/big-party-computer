<?php

namespace App\Livewire\Teknisi;

use App\Models\Servis;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class AntarianServis extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $filterStatus = '';

    // Update modal
    public bool $showModal = false;
    public ?int $servisId  = null;
    public string $statusEdit = '';
    public string $diagnosa   = '';
    public string $catatan    = '';

    // Alert
    public string $message     = '';
    public string $messageType = 'success';

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function openEdit(int $id): void
    {
        $servis = Servis::where('teknisi_id', Auth::id())->findOrFail($id);

        $this->servisId  = $id;
        $this->statusEdit = $servis->status;
        $this->diagnosa   = $servis->diagnosa ?? '';
        $this->catatan    = $servis->catatan ?? '';
        $this->showModal  = true;
    }

    public function save(): void
    {
        $this->validate([
            'statusEdit' => 'required|in:antri,dalam_pengerjaan,selesai',
        ], [
            'statusEdit.required' => 'Status wajib dipilih.',
            'statusEdit.in'       => 'Status tidak valid.',
        ]);

        $servis = Servis::where('teknisi_id', Auth::id())->findOrFail($this->servisId);

        $servis->update([
            'status'          => $this->statusEdit,
            'diagnosa'        => $this->diagnosa ?: null,
            'catatan'         => $this->catatan ?: null,
            'tanggal_selesai' => $this->statusEdit === Servis::STATUS_SELESAI
                                    ? ($servis->tanggal_selesai ?? today())
                                    : null,
        ]);

        $this->showModal   = false;
        $this->message     = 'Status servis berhasil diperbarui.';
        $this->messageType = 'success';
        $this->reset(['servisId', 'statusEdit', 'diagnosa', 'catatan']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['servisId', 'statusEdit', 'diagnosa', 'catatan']);
    }

    public function render(): View
    {
        $data = Servis::query()
            ->with(['perangkat.pelanggan'])
            ->where('teknisi_id', Auth::id())
            ->when($this->search, fn ($q) =>
                $q->where('nomor_nota', 'like', "%{$this->search}%")
                  ->orWhereHas('perangkat.pelanggan', fn ($q) =>
                      $q->where('nama', 'like', "%{$this->search}%")
                  )
            )
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->oldest('tanggal_masuk')
            ->paginate(10);

        return view('livewire.teknisi.antrian-servis', compact('data'))
            ->layout('components.layouts.teknisi', ['heading' => 'Antrian & Servis']);
    }
}
