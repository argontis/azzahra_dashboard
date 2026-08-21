<!DOCTYPE html>
<html>
<head>
    <title>Data Karyawan</title>
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
    <h2>Laporan Data Karyawan</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y H:i:s') }}</p>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>KODE</th>
                <th>USERNAME</th>
                <th>NAMA KARYAWAN</th>
                <th>JABATAN</th>
                <th>TELP</th>
                <th>ALAMAT</th>
                <th>TGL MASUK</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($karyawan_list as $index => $row)
                @php
                    $status = 'Aktif';
                    if ($row->kry_status == 0) $status = 'Tidak Aktif';
                    elseif ($row->kry_status == 2) $status = 'Cuti';
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $row->kry_kode }}</td>
                    <td>{{ $row->kry_username }}</td>
                    <td>{{ $row->kry_nama }}</td>
                    <td>{{ $row->kry_level }}</td>
                    <td>{{ $row->kry_telp }}</td>
                    <td>{{ $row->kry_alamat }}</td>
                    <td>{{ $row->kry_join_date }}</td>
                    <td>{{ $status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
