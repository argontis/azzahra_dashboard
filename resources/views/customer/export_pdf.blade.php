<!DOCTYPE html>
<html>
<head>
    <title>Data Customer</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; margin: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 5px 7px; text-align: left; }
        th { background-color: #e2e8f0; font-weight: bold; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { font-size: 10px; color: #555; }
    </style>
</head>
<body>
    <h2>Laporan Data Customer</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>KODE</th>
                <th>NAMA CUSTOMER</th>
                <th>HP</th>
                <th>TYPE</th>
                <th>MODEL</th>
                <th>STATUS PERBAIKAN</th>
                <th>KELUHAN</th>
                <th>ALAMAT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $row)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $row->id_costomer }}</td>
                    <td>{{ $row->cos_nama }}</td>
                    <td>{{ $row->cos_hp }}</td>
                    <td>{{ $row->cos_tipe }}</td>
                    <td>{{ $row->cos_model }}</td>
                    <td>{{ $row->trans_status ?? '-' }}</td>
                    <td>{{ $row->cos_keluhan }}</td>
                    <td>{{ $row->cos_alamat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
