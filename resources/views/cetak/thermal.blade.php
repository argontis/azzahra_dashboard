<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 2mm; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            width: 76mm; /* thermal paper width approximation */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .border-bottom { border-bottom: 1px dashed #000; margin-bottom: 5px; padding-bottom: 5px; }
        .border-bottom-solid { border-bottom: 2px solid #000; margin-bottom: 5px; padding-bottom: 5px; }
        .title { font-size: 12px; font-weight: bold; margin-top: 5px; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; font-size: 10px; }
        .no-wrap { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="text-center border-bottom-solid">
        <!-- Optional Logo Here -->
        <div style="font-size: 9px;">AUTHORIZED MULTIBRAND SERVICE CENTER</div>
        <div class="font-bold" style="font-size: 14px;">AZZAHRA COMPUTER</div>
        <div style="font-size: 9px;">Telp: 0823-340909 | WA: 0859-4200-1720</div>
    </div>

    <div class="text-center title border-bottom-solid">
        {{ $title }}
    </div>

    <table>
        <tr><td width="30%">Invoice</td><td width="5%">:</td><td>{{ $customer->id_costomer ?? $customer->cos_kode }}</td></tr>
        <tr><td>Customer</td><td>:</td><td class="font-bold">{{ $customer->cos_nama ?? '' }}</td></tr>
        <tr><td>Tanggal</td><td>:</td><td>{{ date('d-m-Y H:i:s') }}</td></tr>
    </table>

    <div class="text-center font-bold border-bottom" style="margin-top:5px; padding-top:5px;">
        {{ $section_title }}
    </div>

    <table>
        <tr><td width="30%">Tanggal</td><td width="5%">:</td><td>{{ date('d-m-Y', strtotime($bayar->dtl_tanggal ?? date('Y-m-d'))) }}</td></tr>
        <tr><td>Status</td><td>:</td><td>{{ $bayar->dtl_status ?? '' }}</td></tr>
        <tr><td>Jenis</td><td>:</td><td>{{ $bayar->dtl_jenis_bayar ?? '-' }}</td></tr>
        @if(!empty($bayar->dtl_bank))
        <tr><td>Bank</td><td>:</td><td>{{ $bayar->dtl_bank }}</td></tr>
        @endif
        <tr><td>Dibayar</td><td>:</td><td class="font-bold">Rp. {{ number_format($bayar->dtl_jml_bayar ?? 0, 0, ',', '.') }}</td></tr>
    </table>

    <div class="text-center font-bold border-bottom" style="margin-top:5px; padding-top:5px;">
        RINCIAN LAYANAN
    </div>

    <table>
        @php $no = 1; @endphp
        @foreach($barang as $item)
        <tr>
            <td width="10%">{{ $no++ }}.</td>
            <td width="60%">{{ $item->tdkn_barang }}:-</td>
            <td width="30%" class="text-right">Rp. {{ number_format($item->tdkn_subtot, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="border-bottom-solid" style="margin-top:5px;"></div>
    <table>
        <tr>
            <td width="70%" class="font-bold" style="font-size:12px;">TOTAL</td>
            <td width="30%" class="text-right font-bold" style="font-size:12px;">Rp. {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="text-center" style="margin-top:10px;">
        Terima Kasih Atas Kunjungan Anda<br>
        Barang yang sudah dibeli tidak dapat ditukar/dikembalikan<br>
        Authorized Service Center
    </div>
    
    <div class="text-center" style="margin-top:10px;">
        - Brand yang kami layani -<br>
        <span style="font-size:7px;">LENOVO | ASUS | MSI | ADVAN | DAC | SPC | XIAOMI | CANON</span>
    </div>

</body>
</html>
