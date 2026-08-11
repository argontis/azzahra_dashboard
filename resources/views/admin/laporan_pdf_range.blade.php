<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Periode</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; padding: 0; font-size: 18px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Pendapatan - Azzahra Computer</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tgl_awal)->format('d F Y') }} - {{ \Carbon\Carbon::parse($tgl_akhir)->format('d F Y') }}</p>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">NO</th>
                <th class="text-center">INVOICE</th>
                <th>NAMA CUSTOMER</th>
                <th class="text-center">TANGGAL</th>
                <th class="text-center">STATUS</th>
                <th class="text-center">JENIS BAYAR</th>
                <th class="text-center">BANK</th>
                <th class="text-right">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($payments as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $row->trans_kode }}</td>
                    <td>{{ $row->transaksi->customer->cos_nama ?? '-' }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($row->dtl_tanggal)->format('d-m-Y') }}</td>
                    <td class="text-center">{{ $row->dtl_status }}</td>
                    <td class="text-center">{{ $row->dtl_jenis_bayar }}</td>
                    <td class="text-center">{{ $row->dtl_bank ?? '-' }}</td>
                    <td class="text-right">{{ number_format($row->dtl_jml_bayar, 0, ',', '.') }}</td>
                </tr>
                @php $total += $row->dtl_jml_bayar; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><strong>TOTAL PENDAPATAN PERIODE INI</strong></td>
                <td class="text-right"><strong>{{ number_format($total, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
