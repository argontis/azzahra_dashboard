<!DOCTYPE html>
<html>
<head>
    <title>Data Customer</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
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
