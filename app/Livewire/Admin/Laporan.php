<?php

namespace App\Livewire\Admin;

use App\Exports\LaporanTransaksiExport;
use App\Models\Transaksi;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Laporan extends Component
{
    public string $dari   = '';
    public string $sampai = '';
    public bool $sudahGenerate = false;

    public function mount(): void
    {
        $this->dari   = now()->startOfMonth()->toDateString();
        $this->sampai = now()->toDateString();
    }

    public function generate(): void
    {
        $this->validate([
            'dari'   => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ], [
            'dari.required'          => 'Tanggal awal wajib diisi.',
            'sampai.required'        => 'Tanggal akhir wajib diisi.',
            'sampai.after_or_equal'  => 'Tanggal akhir harus sama atau setelah tanggal awal.',
        ]);

        $this->sudahGenerate = true;
    }

    public function export(): BinaryFileResponse
    {
        $this->validate([
            'dari'   => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ], [
            'dari.required'          => 'Tanggal awal wajib diisi.',
            'sampai.required'        => 'Tanggal akhir wajib diisi.',
            'sampai.after_or_equal'  => 'Tanggal akhir harus sama atau setelah tanggal awal.',
        ]);

        $filename = sprintf(
            'Laporan Transaksi Penjualan %s - %s.xlsx',
            \Carbon\Carbon::parse($this->dari)->format('d-m-Y'),
            \Carbon\Carbon::parse($this->sampai)->format('d-m-Y'),
        );

        return Excel::download(new LaporanTransaksiExport($this->dari, $this->sampai), $filename);
    }

    public function render(): View
    {
        $laporan         = collect();
        $totalPendapatan = 0;
        $totalServis     = 0;
        $totalJasa       = 0;
        $totalSparepart  = 0;

        if ($this->sudahGenerate) {
            $laporan = Transaksi::query()
                ->with(['servis.perangkat.pelanggan', 'admin'])
                ->whereBetween('tanggal_bayar', [$this->dari, $this->sampai])
                ->latest('tanggal_bayar')
                ->get();

            $totalServis     = $laporan->count();
            $totalPendapatan = $laporan->sum('total');
            $totalJasa       = $laporan->sum('biaya_jasa');
            $totalSparepart  = $laporan->sum('biaya_sparepart');
        }

        return view('livewire.admin.laporan', compact(
            'laporan', 'totalPendapatan', 'totalServis', 'totalJasa', 'totalSparepart'
        ))->layout('components.layouts.admin.index', ['heading' => 'Laporan']);
    }
}
