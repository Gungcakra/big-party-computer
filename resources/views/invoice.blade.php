<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $servis->nomor_nota }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            padding: 40px 48px;
        }

        /* ── Header ── */
        .header {
            border-bottom: 2px solid #1d4ed8;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .header-inner {
            width: 100%;
        }
        .header-left {
            display: inline-block;
            vertical-align: top;
            width: 55%;
        }
        .header-right {
            display: inline-block;
            vertical-align: top;
            width: 44%;
            text-align: right;
        }
        .company-name {
            font-size: 20px;
            font-weight: 700;
            color: #1d4ed8;
            letter-spacing: -0.3px;
        }
        .company-sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .invoice-label {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .nomor-nota {
            font-size: 12px;
            color: #1d4ed8;
            font-weight: 600;
            margin-top: 3px;
        }

        /* ── Meta row ── */
        .meta-row {
            margin-bottom: 24px;
        }
        .meta-box {
            display: inline-block;
            vertical-align: top;
            width: 48%;
        }
        .meta-box-right {
            text-align: right;
        }
        .meta-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .meta-value {
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
        }
        .meta-sub {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 18px 0;
        }

        /* ── Section heading ── */
        .section-heading {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        /* ── Device info ── */
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .info-key {
            width: 120px;
            color: #64748b;
            font-size: 10.5px;
        }
        .info-val {
            font-weight: 600;
            color: #1e293b;
            font-size: 10.5px;
        }

        /* ── Items table ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .items-table thead tr {
            background-color: #1d4ed8;
            color: #ffffff;
        }
        .items-table thead th {
            padding: 8px 12px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }
        .items-table thead th.text-right {
            text-align: right;
        }
        .items-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
        }
        .items-table tbody tr:last-child {
            border-bottom: none;
        }
        .items-table tbody td {
            padding: 9px 12px;
            font-size: 11px;
            color: #334155;
        }
        .items-table tbody td.text-right {
            text-align: right;
        }
        .items-table tfoot tr.subtotal td {
            padding: 6px 12px;
            font-size: 10.5px;
            color: #475569;
            border-top: 1px solid #e2e8f0;
        }
        .items-table tfoot tr.subtotal td.text-right {
            text-align: right;
        }
        .items-table tfoot tr.total-row {
            background-color: #eff6ff;
        }
        .items-table tfoot tr.total-row td {
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 700;
            color: #1d4ed8;
            border-top: 2px solid #bfdbfe;
        }
        .items-table tfoot tr.total-row td.text-right {
            text-align: right;
        }

        /* ── Payment info ── */
        .payment-row {
            margin-top: 20px;
        }
        .payment-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }
        .badge-cash {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .badge-transfer {
            background-color: #dbeafe;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        /* ── Notes ── */
        .notes-box {
            background: #f8fafc;
            border-left: 3px solid #bfdbfe;
            padding: 10px 14px;
            margin-top: 20px;
            font-size: 10.5px;
            color: #475569;
            border-radius: 0 4px 4px 0;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 36px;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
        }
        .footer-left {
            display: inline-block;
            vertical-align: top;
            width: 55%;
            font-size: 9.5px;
            color: #94a3b8;
        }
        .footer-right {
            display: inline-block;
            vertical-align: top;
            width: 44%;
            text-align: right;
        }
        .stamp-area {
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 16px 12px 8px;
            text-align: center;
            display: inline-block;
            width: 140px;
        }
        .stamp-label {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stamp-name {
            font-size: 10px;
            font-weight: 700;
            color: #334155;
            margin-top: 24px;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
    </style>
</head>
<body>

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-left">
                <div class="company-name">Big Party Computer</div>
                <div class="company-sub">Layanan Perbaikan & Servis Komputer</div>
            </div>
            <div class="header-right">
                <div class="invoice-label">INVOICE</div>
                <div class="nomor-nota">{{ $servis->nomor_nota }}</div>
            </div>
        </div>
    </div>

    {{-- ── META ROW ── --}}
    <div class="meta-row">
        <div class="meta-box">
            <div class="meta-label">Tagihan Kepada</div>
            <div class="meta-value">{{ $servis->perangkat->pelanggan->nama }}</div>
            @if ($servis->perangkat->pelanggan->telepon ?? null)
            <div class="meta-sub">{{ $servis->perangkat->pelanggan->telepon }}</div>
            @endif
        </div>
        <div class="meta-box meta-box-right">
            <div class="meta-label">Tanggal Bayar</div>
            <div class="meta-value">{{ \Carbon\Carbon::parse($servis->transaksi->tanggal_bayar)->translatedFormat('d F Y') }}</div>
            <div class="meta-sub" style="margin-top:10px;">
                <span class="status-badge">LUNAS</span>
            </div>
        </div>
    </div>

    <hr class="divider">

    {{-- ── DEVICE INFO ── --}}
    <div class="section-heading">Detail Perangkat</div>
    <table class="info-table">
        <tr>
            <td class="info-key">Jenis Perangkat</td>
            <td class="info-val">{{ $servis->perangkat->jenis_perangkat }} — {{ $servis->perangkat->merek }}</td>
        </tr>
        <tr>
            <td class="info-key">Keluhan</td>
            <td class="info-val">{{ $servis->perangkat->keluhan }}</td>
        </tr>
        @if ($servis->diagnosa)
        <tr>
            <td class="info-key">Diagnosa</td>
            <td class="info-val">{{ $servis->diagnosa }}</td>
        </tr>
        @endif
        @if ($servis->teknisi)
        <tr>
            <td class="info-key">Teknisi</td>
            <td class="info-val">{{ $servis->teknisi->name }}</td>
        </tr>
        @endif
        <tr>
            <td class="info-key">Tanggal Masuk</td>
            <td class="info-val">{{ \Carbon\Carbon::parse($servis->tanggal_masuk)->translatedFormat('d F Y') }}</td>
        </tr>
        @if ($servis->tanggal_selesai)
        <tr>
            <td class="info-key">Tanggal Selesai</td>
            <td class="info-val">{{ \Carbon\Carbon::parse($servis->tanggal_selesai)->translatedFormat('d F Y') }}</td>
        </tr>
        @endif
    </table>

    {{-- ── ITEMS TABLE ── --}}
    <div class="section-heading">Rincian Biaya</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="text-align:left; width:60%;">Deskripsi</th>
                <th class="text-right" style="width:40%;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Biaya Jasa Servis</td>
                <td class="text-right">Rp {{ number_format((float)$servis->transaksi->biaya_jasa, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Sparepart / Onderdil</td>
                <td class="text-right">Rp {{ number_format((float)$servis->transaksi->biaya_sparepart, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td><strong>Total Pembayaran</strong></td>
                <td class="text-right"><strong>Rp {{ number_format((float)$servis->transaksi->total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    {{-- ── PAYMENT METHOD ── --}}
    <div class="payment-row">
        <span style="font-size:10px; color:#64748b; vertical-align:middle;">Metode Pembayaran:&nbsp;</span>
        @if ($servis->transaksi->metode_bayar === 'transfer')
        <span class="payment-badge badge-transfer">Transfer Bank</span>
        @else
        <span class="payment-badge badge-cash">Cash / Tunai</span>
        @endif
    </div>

    @if ($servis->transaksi->catatan)
    {{-- ── NOTES ── --}}
    <div class="notes-box">
        <strong style="color:#1e293b;">Catatan:</strong> {{ $servis->transaksi->catatan }}
    </div>
    @endif

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <div class="footer-left">
            <strong style="color:#334155;">Big Party Computer</strong><br>
            Terima kasih telah mempercayakan perangkat Anda kepada kami.<br>
            Dokumen ini dicetak secara otomatis oleh sistem.
        </div>
        <div class="footer-right">
            <div class="stamp-area">
                <div class="stamp-label">Diproses oleh</div>
                <div class="stamp-name">{{ $servis->transaksi->admin->name }}</div>
            </div>
        </div>
    </div>

</body>
</html>
