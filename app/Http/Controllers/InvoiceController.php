<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    public function download(Request $request): Response
    {
        $nomorNota = $request->query('nota');

        abort_if(!$nomorNota, 404);

        $servis = Servis::with(['perangkat.pelanggan', 'teknisi', 'transaksi.admin'])
            ->where('nomor_nota', $nomorNota)
            ->whereHas('transaksi')
            ->firstOrFail();

        $pdf = Pdf::loadView('invoice', ['servis' => $servis])
            ->setPaper('a4', 'portrait');

        $filename = 'invoice-' . str_replace('/', '-', $nomorNota) . '.pdf';

        return $pdf->download($filename);
    }
}
