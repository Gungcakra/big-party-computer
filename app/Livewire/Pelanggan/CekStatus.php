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

        $servis = Servis::with(['perangkat.pelanggan', 'teknisi', 'transaksi'])
            ->where('nomor_nota', trim($this->nomorNota))
            ->first();

        $this->sudahCari = true;

        if (!$servis) {
            $this->result = null;
            return;
        }

        $transaksi = $servis->transaksi;

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
            'transaksi'       => $transaksi ? [
                'biaya_jasa'      => $transaksi->biaya_jasa,
                'biaya_sparepart' => $transaksi->biaya_sparepart,
                'total'           => $transaksi->total,
                'metode_bayar'    => $transaksi->metode_bayar,
                'tanggal_bayar'   => $transaksi->tanggal_bayar?->toDateString(),
                'catatan'         => $transaksi->catatan,
            ] : null,
        ];
    }

    public function render(): View
    {
        return view('livewire.pelanggan.cek-status')
            ->layout('components.layouts.public.index', ['heading' => 'Cek Status Perbaikan']);
    }
}
