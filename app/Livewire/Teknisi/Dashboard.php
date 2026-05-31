<?php

namespace App\Livewire\Teknisi;

use App\Models\Servis;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Dashboard extends Component
{
    public function render(): View
    {
        $teknisiId = Auth::id();

        $stats = [
            'total_ditugaskan' => Servis::where('teknisi_id', $teknisiId)->count(),
            'antri'            => Servis::where('teknisi_id', $teknisiId)->where('status', Servis::STATUS_ANTRI)->count(),
            'dalam_pengerjaan' => Servis::where('teknisi_id', $teknisiId)->where('status', Servis::STATUS_DALAM_PENGERJAAN)->count(),
            'selesai'          => Servis::where('teknisi_id', $teknisiId)->where('status', Servis::STATUS_SELESAI)->count(),
        ];

        $activeServis = Servis::with(['perangkat.pelanggan'])
            ->where('teknisi_id', $teknisiId)
            ->whereIn('status', [Servis::STATUS_ANTRI, Servis::STATUS_DALAM_PENGERJAAN])
            ->oldest('tanggal_masuk')
            ->limit(8)
            ->get();

        return view('livewire.teknisi.dashboard', compact('stats', 'activeServis'))
            ->layout('components.layouts.teknisi.index', ['heading' => 'Dashboard']);
    }
}
