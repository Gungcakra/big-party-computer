<?php

namespace App\Livewire\Admin;

use App\Models\Servis;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class AntarianServis extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    // Assign/edit modal
    public bool $showModal = false;
    public ?int $servisId  = null;
    public ?int $teknisiId = null;
    public string $statusEdit  = '';
    public string $diagnosa    = '';
    public string $catatan     = '';

    // Alert
    public string $message     = '';
    public string $messageType = 'success';

    public function updatingSearch(): void       { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function openEdit(int $id): void
    {
        $servis = Servis::findOrFail($id);

        $this->servisId  = $id;
        $this->teknisiId = $servis->teknisi_id;
        $this->statusEdit= $servis->status;
        $this->diagnosa  = $servis->diagnosa ?? '';
        $this->catatan   = $servis->catatan ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'statusEdit' => 'required|in:antri,dalam_pengerjaan,selesai',
        ], [
            'statusEdit.required' => 'Status wajib dipilih.',
            'statusEdit.in'       => 'Status tidak valid.',
        ]);

        $servis = Servis::findOrFail($this->servisId);
        $servis->update([
            'teknisi_id'      => $this->teknisiId ?: null,
            'status'          => $this->statusEdit,
            'diagnosa'        => $this->diagnosa ?: null,
            'catatan'         => $this->catatan ?: null,
            'tanggal_selesai' => $this->statusEdit === Servis::STATUS_SELESAI
                                    ? ($servis->tanggal_selesai ?? today())
                                    : null,
        ]);

        $this->showModal   = false;
        $this->message     = 'Data servis berhasil diperbarui.';
        $this->messageType = 'success';
        $this->reset(['servisId', 'teknisiId', 'statusEdit', 'diagnosa', 'catatan']);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['servisId', 'teknisiId', 'statusEdit', 'diagnosa', 'catatan']);
    }

    public function render(): View
    {
        $data = Servis::query()
            ->with(['perangkat.pelanggan', 'teknisi'])
            ->when($this->search, fn ($q) =>
                $q->where('nomor_nota', 'like', "%{$this->search}%")
                  ->orWhereHas('perangkat.pelanggan', fn ($q) =>
                      $q->where('nama', 'like', "%{$this->search}%")
                  )
            )
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        $teknisiList = User::where('role', User::ROLE_TEKNISI)->orderBy('name')->get();

        return view('livewire.admin.antrian-servis', compact('data', 'teknisiList'))
            ->layout('components.layouts.admin', ['heading' => 'Antrian & Servis']);
    }
}
