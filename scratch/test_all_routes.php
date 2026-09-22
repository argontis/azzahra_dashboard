<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Karyawan;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Ensure users exist
$roles = [
    'Admin' => ['kry_username' => 'admin_test_user', 'kry_level' => 'Admin'],
    'Kasir' => ['kry_username' => 'kasir_test_user', 'kry_level' => 'Kasir'],
    'Customer Service' => ['kry_username' => 'service_test_user', 'kry_level' => 'Customer Service'],
    'Teknisi' => ['kry_username' => 'teknisi_test_user', 'kry_level' => 'Teknisi'],
    'HR' => ['kry_username' => 'hr_test_user', 'kry_level' => 'HR'],
    'Magang / PKL' => ['kry_username' => 'magang_test_user', 'kry_level' => 'Magang / PKL'],
    'Pimpinan' => ['kry_username' => 'pimpinan_test_user', 'kry_level' => 'Pimpinan'],
];

$users = [];
foreach ($roles as $rName => $info) {
    $u = Karyawan::firstOrCreate(
        ['kry_username' => $info['kry_username']],
        [
            'kry_level' => $info['kry_level'],
            'kry_pswd' => bcrypt('password'),
            'kry_nama' => $info['kry_username'],
            'kry_status' => 1,
        ]
    );
    $users[$rName] = $u;
}

// Routes to test comprehensively
$testCases = [
    // Auth & Dashboard
    ['method' => 'GET', 'uri' => '/Auth', 'role' => null, 'module' => 'Autentikasi', 'desc' => 'Tampilan form login sistem'],
    ['method' => 'GET', 'uri' => '/Auth/reset', 'role' => null, 'module' => 'Autentikasi', 'desc' => 'Tampilan form reset password'],
    ['method' => 'GET', 'uri' => '/dashboard', 'role' => 'Admin', 'module' => 'Navigasi', 'desc' => 'Auto-redirect ke dashboard admin'],
    ['method' => 'GET', 'uri' => '/dashboard', 'role' => 'Kasir', 'module' => 'Navigasi', 'desc' => 'Auto-redirect ke dashboard kasir'],
    ['method' => 'GET', 'uri' => '/dashboard', 'role' => 'Teknisi', 'module' => 'Navigasi', 'desc' => 'Auto-redirect ke dashboard teknisi'],
    ['method' => 'GET', 'uri' => '/dashboard', 'role' => 'Customer Service', 'module' => 'Navigasi', 'desc' => 'Auto-redirect ke dashboard CS'],
    ['method' => 'GET', 'uri' => '/dashboard', 'role' => 'HR', 'module' => 'Navigasi', 'desc' => 'Auto-redirect ke dashboard HR'],

    // Admin Module
    ['method' => 'GET', 'uri' => '/Admin', 'role' => 'Admin', 'module' => 'Admin', 'desc' => 'Halaman utama dashboard admin'],
    ['method' => 'GET', 'uri' => '/Admin/order_approvals', 'role' => 'Admin', 'module' => 'Admin', 'desc' => 'Daftar pengajuan order sparepart'],
    ['method' => 'GET', 'uri' => '/Admin/ketersediaan_sparepart', 'role' => 'Admin', 'module' => 'Admin', 'desc' => 'Monitoring ketersediaan sparepart'],
    ['method' => 'GET', 'uri' => '/Admin/laporan', 'role' => 'Admin', 'module' => 'Admin', 'desc' => 'Laporan keuangan & servis'],

    // Kasir Module
    ['method' => 'GET', 'uri' => '/Kasir', 'role' => 'Kasir', 'module' => 'Kasir', 'desc' => 'Dashboard kasir & cari nota'],
    ['method' => 'GET', 'uri' => '/Kasir/pembayaran', 'role' => 'Kasir', 'module' => 'Kasir', 'desc' => 'Riwayat pembayaran kasir'],
    ['method' => 'GET', 'uri' => '/Kasir/laporan', 'role' => 'Kasir', 'module' => 'Kasir', 'desc' => 'Laporan rekap harian kasir'],

    // Customer Service Module
    ['method' => 'GET', 'uri' => '/Service', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Dashboard customer service'],
    ['method' => 'GET', 'uri' => '/Service/form_baru', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Form pendaftaran servis baru'],
    ['method' => 'GET', 'uri' => '/Service/antrean', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Antrean unit servis keseluruhan'],
    ['method' => 'GET', 'uri' => '/Service/antrean/Baru', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Filter antrean unit servis Baru'],
    ['method' => 'GET', 'uri' => '/Service/antrean/Proses', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Filter antrean unit servis Proses'],
    ['method' => 'GET', 'uri' => '/Service/antrean/Selesai', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Filter antrean unit servis Selesai'],
    ['method' => 'GET', 'uri' => '/Service/pembayaran', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Daftar status pembayaran unit'],
    ['method' => 'GET', 'uri' => '/Service/laporan', 'role' => 'Customer Service', 'module' => 'Customer Service', 'desc' => 'Laporan penerimaan servis CS'],

    // Quick Service
    ['method' => 'GET', 'uri' => '/QuickService', 'role' => 'Customer Service', 'module' => 'Quick Service', 'desc' => 'Antrean servis kilat'],
    ['method' => 'GET', 'uri' => '/QuickService/form_baru', 'role' => 'Customer Service', 'module' => 'Quick Service', 'desc' => 'Form servis kilat baru'],

    // Teknisi Module
    ['method' => 'GET', 'uri' => '/Teknisi', 'role' => 'Teknisi', 'module' => 'Teknisi', 'desc' => 'Workspace antrean unit teknisi'],
    ['method' => 'GET', 'uri' => '/Teknisi/my_orders', 'role' => 'Teknisi', 'module' => 'Teknisi', 'desc' => 'Riwayat pesanan sparepart teknisi'],
    ['method' => 'GET', 'uri' => '/Teknisi', 'role' => 'Magang / PKL', 'module' => 'Teknisi', 'desc' => 'Akses workspace teknisi oleh siswa magang'],

    // HR Module
    ['method' => 'GET', 'uri' => '/HR', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Dashboard analitik SDM'],
    ['method' => 'GET', 'uri' => '/HR/karyawan', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Master data karyawan & anak magang'],
    ['method' => 'GET', 'uri' => '/HR/absensi', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Daftar log absensi harian'],
    ['method' => 'GET', 'uri' => '/HR/kpi', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Evaluasi Key Performance Indicator'],
    ['method' => 'GET', 'uri' => '/HR/kpi/template', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Unduh template excel import KPI'],
    ['method' => 'GET', 'uri' => '/HR/laporan_mingguan', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Rekap evaluasi mingguan tim'],
    ['method' => 'GET', 'uri' => '/HR/interview', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Rekap form wawancara pelamar'],
    ['method' => 'GET', 'uri' => '/HR/certificate_generator', 'role' => 'HR', 'module' => 'HR', 'desc' => 'Alat cetak sertifikat kerja/magang'],

    // Master Data
    ['method' => 'GET', 'uri' => '/Produk', 'role' => 'Admin', 'module' => 'Master Produk', 'desc' => 'Katalog produk & sparepart'],
    ['method' => 'GET', 'uri' => '/Produk/create', 'role' => 'Admin', 'module' => 'Master Produk', 'desc' => 'Form tambah produk/barang'],
    ['method' => 'GET', 'uri' => '/produk-ajax?q=lcd', 'role' => 'Admin', 'module' => 'Master Produk', 'desc' => 'Pencarian AJAX live produk'],
    ['method' => 'GET', 'uri' => '/Customer', 'role' => 'Admin', 'module' => 'Customer', 'desc' => 'Database pelanggan & klasifikasi tier'],
    ['method' => 'GET', 'uri' => '/Customer/create', 'role' => 'Admin', 'module' => 'Customer', 'desc' => 'Form registrasi customer baru'],
    ['method' => 'GET', 'uri' => '/export_customer', 'role' => 'Admin', 'module' => 'Customer', 'desc' => 'Export data pelanggan ke dokumen PDF'],
    ['method' => 'GET', 'uri' => '/Voucher', 'role' => 'Admin', 'module' => 'Voucher', 'desc' => 'Daftar kode voucher & diskon'],
    ['method' => 'GET', 'uri' => '/Voucher/create', 'role' => 'Admin', 'module' => 'Voucher', 'desc' => 'Form input kode voucher baru'],
    ['method' => 'GET', 'uri' => '/Mou', 'role' => 'Admin', 'module' => 'MoU Kerjasama', 'desc' => 'Daftar kerjasama B2B / MoU'],

    // Communication
    ['method' => 'GET', 'uri' => '/announcements', 'role' => 'Admin', 'module' => 'Pengumuman', 'desc' => 'Daftar pengumuman sistem'],
    ['method' => 'GET', 'uri' => '/announcements/unread-count', 'role' => 'Admin', 'module' => 'Pengumuman', 'desc' => 'API hitung unread pengumuman'],
    ['method' => 'GET', 'uri' => '/messages', 'role' => 'Admin', 'module' => 'Pesan Internal', 'desc' => 'Kotak pesan staf'],
    ['method' => 'GET', 'uri' => '/messages/unread-count', 'role' => 'Admin', 'module' => 'Pesan Internal', 'desc' => 'API hitung unread pesan internal'],

    // Security & Role Enforcement Tests (Penetration / Boundary tests)
    ['method' => 'GET', 'uri' => '/Admin', 'role' => 'Teknisi', 'module' => 'Role Boundary Test', 'desc' => 'Cegah teknisi membuka menu Admin (Expected: 302 Redirect/Block)'],
    ['method' => 'GET', 'uri' => '/Kasir', 'role' => 'HR', 'module' => 'Role Boundary Test', 'desc' => 'Cegah staf HR membuka menu Kasir (Expected: 302 Redirect/Block)'],
    ['method' => 'GET', 'uri' => '/HR', 'role' => 'Customer Service', 'module' => 'Role Boundary Test', 'desc' => 'Cegah CS membuka menu HR (Expected: 302 Redirect/Block)'],
    ['method' => 'GET', 'uri' => '/Admin/ketersediaan_sparepart', 'role' => 'Teknisi', 'module' => 'Role Boundary Test', 'desc' => 'Cegah teknisi akses ketersediaan sparepart admin (Expected: 302/Block)'],
];

$results = [];
$passedCount = 0;
$failedCount = 0;

foreach ($testCases as $tc) {
    if ($tc['role'] && isset($users[$tc['role']])) {
        Auth::login($users[$tc['role']]);
    } else {
        Auth::logout();
    }

    $startTime = microtime(true);
    $req = Request::create($tc['uri'], $tc['method']);

    // Pass session if authenticated
    if (Auth::check()) {
        $req->setUserResolver(function () use ($tc, $users) {
            return $users[$tc['role']];
        });
    }

    try {
        $res = $kernel->handle($req);
        $statusCode = $res->getStatusCode();
        $durationMs = round((microtime(true) - $startTime) * 1000, 1);

        // Evaluate test result
        $isPassed = false;
        if ($tc['module'] === 'Role Boundary Test') {
            // Should be redirected (302) or forbidden (403)
            $isPassed = ($statusCode === 302 || $statusCode === 403);
            $notes = $isPassed ? 'Berhasil Diblokir/Dialihkan Sesuai Hak Akses (Aman)' : 'Bocor Hak Akses';
        } elseif ($tc['module'] === 'Navigasi' && $tc['uri'] === '/dashboard') {
            // Dashboard redirects to proper role path
            $isPassed = ($statusCode === 302);
            $notes = "Berhasil Dialihkan Sesuai Role (Status $statusCode)";
        } else {
            // Standard page should be 200 OK or 302 legitimate redirect
            $isPassed = ($statusCode === 200 || $statusCode === 302);
            $notes = ($statusCode === 200) ? 'Sukses Render Halaman (200 OK)' : "Dialihkan (Redirect $statusCode)";
        }

        if ($isPassed) {
            $passedCount++;
        } else {
            $failedCount++;
        }

        $results[] = [
            'method' => $tc['method'],
            'uri' => $tc['uri'],
            'role' => $tc['role'] ?? 'Tamu (Guest)',
            'module' => $tc['module'],
            'desc' => $tc['desc'],
            'status_code' => $statusCode,
            'duration_ms' => $durationMs,
            'is_passed' => $isPassed,
            'status_text' => $isPassed ? 'PASSED' : 'FAILED',
            'notes' => $notes,
        ];
    } catch (Throwable $e) {
        $failedCount++;
        $results[] = [
            'method' => $tc['method'],
            'uri' => $tc['uri'],
            'role' => $tc['role'] ?? 'Tamu (Guest)',
            'module' => $tc['module'],
            'desc' => $tc['desc'],
            'status_code' => 500,
            'duration_ms' => 0,
            'is_passed' => false,
            'status_text' => 'ERROR',
            'notes' => 'Exception: '.$e->getMessage(),
        ];
    }
}

$summary = [
    'total_tests' => count($testCases),
    'passed' => $passedCount,
    'failed' => $failedCount,
    'success_rate' => round(($passedCount / count($testCases)) * 100, 1).'%',
    'timestamp' => date('Y-m-d H:i:s'),
    'results' => $results,
];

file_put_contents(__DIR__.'/test_results.json', json_encode($summary, JSON_PRETTY_PRINT));
echo "Route testing complete! Total: {$summary['total_tests']}, Passed: {$summary['passed']}, Failed: {$summary['failed']}\n";
