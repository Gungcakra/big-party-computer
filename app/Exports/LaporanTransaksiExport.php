<?php

namespace App\Exports;

use App\Models\Transaksi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanTransaksiExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents,
    ShouldAutoSize
{
    private int   $rowNumber       = 0;
    private float $totalJasa       = 0;
    private float $totalSparepart  = 0;
    private float $totalKeseluruhan = 0;

    public function __construct(
        private readonly string $dari,
        private readonly string $sampai,
    ) {}

    public function collection(): Collection
    {
        return Transaksi::query()
            ->with(['servis.perangkat.pelanggan'])
            ->whereBetween('tanggal_bayar', [$this->dari, $this->sampai])
            ->latest('tanggal_bayar')
            ->get();
    }

    public function headings(): array
    {
        return ['NO', 'NOMOR NOTA', 'PELANGGAN', 'PERANGKAT', 'BIAYA JASA', 'SPAREPART', 'TOTAL', 'TGL BAYAR'];
    }

    public function map($transaksi): array
    {
        $this->rowNumber++;
        $this->totalJasa        += (float) $transaksi->biaya_jasa;
        $this->totalSparepart   += (float) $transaksi->biaya_sparepart;
        $this->totalKeseluruhan += (float) $transaksi->total;

        return [
            $this->rowNumber,
            $transaksi->servis->nomor_nota ?? '-',
            $transaksi->servis->perangkat->pelanggan->nama,
            $transaksi->servis->perangkat->jenis_perangkat,
            $this->rupiah($transaksi->biaya_jasa),
            $this->rupiah($transaksi->biaya_sparepart),
            $this->rupiah($transaksi->total),
            $transaksi->tanggal_bayar->format('d/m/Y'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $dataEnd = $this->rowNumber + 1; // 1-based + header row
                $totalRow = $dataEnd + 1;

                // Summary row values
                $sheet->setCellValue("A{$totalRow}", "Jumlah: {$this->rowNumber} transaksi");
                $sheet->setCellValue("E{$totalRow}", $this->rupiah($this->totalJasa));
                $sheet->setCellValue("F{$totalRow}", $this->rupiah($this->totalSparepart));
                $sheet->setCellValue("G{$totalRow}", $this->rupiah($this->totalKeseluruhan));

                // Bold + top border on summary row
                $sheet->getStyle("A{$totalRow}:H{$totalRow}")
                    ->getFont()->setBold(true);
                $sheet->getStyle("A{$totalRow}:H{$totalRow}")
                    ->getBorders()->getTop()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setRGB('CBD5E1');

                // Thin bottom border on header row
                $sheet->getStyle('A1:H1')
                    ->getBorders()->getBottom()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setRGB('93C5FD');

                // Align NO and TGL BAYAR columns center
                $sheet->getStyle("A2:A{$totalRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H2:H{$totalRow}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1D4ED8']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    private function rupiah(mixed $amount): string
    {
        return 'Rp ' . number_format((float) $amount, 0, ',', '.');
    }
}
