<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengakuan Pelanggan</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; margin: 20px; }
        .header { font-weight: bold; font-size: 16px; text-decoration: underline; margin-bottom: 15px; }
        .content-box { border: 1px solid #000; padding: 10px; margin-bottom: 20px; text-align: justify; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; }
        .signature-box { height: 60px; }
    </style>
</head>
<body>
    <div class="header">PENGAKUAN PELANGGAN</div>
    
    <div class="content-box">
        Saya dengan ini mengakui & setuju bahwa bagian-bagian yang ditandai di halaman ini rusak karena kelalaian pribadi saya. Ini bukan disebabkan oleh kesalahan penanganan mesin oleh Pusat Servis Resmi Lenovo.
    </div>

    <table>
        <tr>
            <td width="50%" style="text-decoration: underline;">nama pelanggan:</td>
            <td width="50%" style="text-decoration: underline;">nama teknisi:</td>
        </tr>
        <tr>
            <td class="signature-box"></td>
            <td class="signature-box"></td>
        </tr>
        <tr>
            <td>Tanggal: {{ date('d F Y') }}</td>
            <td>Tanggal: {{ date('d F Y') }}</td>
        </tr>
    </table>

    <div class="content-box" style="margin-top: 50px;">
        Saya dengan ini mengakui & setuju bahwa bagian-bagian yang ditandai di halaman ini akan dikenakan biaya karena kelalaian / kesalahan pribadi / faktor eksternal saya. Ini bukan disebabkan oleh kesalahan penanganan mesin oleh Pusat Servis Resmi Lenovo.
    </div>

    <table>
        <tr>
            <td width="50%" style="text-decoration: underline;">nama pelanggan:</td>
            <td width="50%" style="text-decoration: underline;">nama teknisi:</td>
        </tr>
        <tr>
            <td class="signature-box"></td>
            <td class="signature-box"></td>
        </tr>
        <tr>
            <td>Tanggal: {{ date('d F Y') }}</td>
            <td>Tanggal: {{ date('d F Y') }}</td>
        </tr>
    </table>
</body>
</html>
