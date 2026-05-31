<?php

namespace App\Livewire\Admin;

use App\Models\Pelanggan;
use App\Models\Servis;
use App\Models\Transaksi;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $stats = [
            'total_masuk'       => Servis::count(),
            'antri'             => Servis::where('status', Servis::STATUS_ANTRI)->count(),
            'dalam_pengerjaan'  => Servis::where('status', Servis::STATUS_DALAM_PENGERJAAN)->count(),
            'selesai'           => Servis::where('status', Servis::STATUS_SELESAI)->count(),
            'pendapatan_bulan'  => Transaksi::whereMonth('tanggal_bayar', now()->month)
                                            ->whereYear('tanggal_bayar', now()->year)
                                            ->sum('total'),
            'total_pelanggan'   => Pelanggan::count(),
        ];

        $recentServis = Servis::with(['perangkat.pelanggan', 'teknisi'])
            ->latest()
            ->limit(6)
            ->get();

        return view('livewire.admin.dashboard', compact('stats', 'recentServis'))
            ->layout('components.layouts.admin.index', ['heading' => 'Dashboard']);
    }
}
