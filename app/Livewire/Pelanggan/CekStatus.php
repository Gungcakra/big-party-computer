<?php

namespace App\Livewire\Pelanggan;

use App\Models\Servis;
use Illuminate\View\View;
use Livewire\Component;

class CekStatus extends Component
{
    public string $nomorNota = '';
    public bool $sudahCari   = false;
    public ?array $result    = null;

    public function cek(): void
    {
        $this->validate([
            'nomorNota' => 'required|string|max:50',
        ], [
            'nomorNota.required' => 'Nomor nota servis wajib diisi.',
        ]);

        $servis = Servis::with(['perangkat.pelanggan', 'teknisi'])
            ->where('nomor_nota', trim($this->nomorNota))
            ->first();

        $this->sudahCari = true;

        if (!$servis) {
            $this->result = null;
            return;
        }

        $this->result = [
            'nomor_nota'      => $servis->nomor_nota,
            'status'          => $servis->status,
            'diagnosa'        => $servis->diagnosa,
            'catatan'         => $servis->catatan,
            'tanggal_masuk'   => $servis->tanggal_masuk,
            'tanggal_selesai' => $servis->tanggal_selesai,
            'nama_pelanggan'  => $servis->perangkat->pelanggan->nama,
            'jenis_perangkat' => $servis->perangkat->jenis_perangkat,
            'merek'           => $servis->perangkat->merek,
            'keluhan'         => $servis->perangkat->keluhan,
            'teknisi'         => $servis->teknisi?->name,
        ];
    }

    public function render(): View
    {
        return view('livewire.pelanggan.cek-status')
            ->layout('components.layouts.public', ['heading' => 'Cek Status Perbaikan']);
    }
}
