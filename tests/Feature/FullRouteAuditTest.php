<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Karyawan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FullRouteAuditTest extends TestCase
{
    protected static array $testAuditLog = [];

    protected array $users = [];

    protected string $sampleTransKode = 'TRX-TEST-001';

    protected string $sampleCustomerKode = 'CUS-TEST-001';

    protected string $sampleProdukKode = 'PRD-TEST-001';

    protected function setUp(): void
    {
        parent::setUp();

        $roles = [
            'Admin' => ['kry_username' => 'admin_audit_user', 'kry_level' => 'Admin'],
            'Kasir' => ['kry_username' => 'kasir_audit_user', 'kry_level' => 'Kasir'],
            'Customer Service' => ['kry_username' => 'service_audit_user', 'kry_level' => 'Customer Service'],
            'Teknisi' => ['kry_username' => 'teknisi_audit_user', 'kry_level' => 'Teknisi'],
            'HR' => ['kry_username' => 'hr_audit_user', 'kry_level' => 'HR'],
            'Magang / PKL' => ['kry_username' => 'magang_audit_user', 'kry_level' => 'Magang / PKL'],
            'Pimpinan' => ['kry_username' => 'pimpinan_audit_user', 'kry_level' => 'Pimpinan'],
        ];

        foreach ($roles as $rName => $info) {
            $this->users[$rName] = Karyawan::firstOrCreate(
                ['kry_username' => $info['kry_username']],
                [
                    'kry_level' => $info['kry_level'],
                    'kry_pswd' => Hash::make('password'),
                    'kry_nama' => $info['kry_username'],
                    'kry_tempat' => 'Tegal',
                    'kry_tgl_lahir' => '2000-01-01',
                    'kry_tlp' => '08123456789',
                    'kry_alamat' => 'Azzahra',
                    'kry_tgl_masuk' => date('Y-m-d'),
                ]
            );
        }

        // Create sample records for parametric routes
        Customer::firstOrCreate(
            ['id_costomer' => $this->sampleCustomerKode],
            [
                'cos_nama' => 'Sample Audit Customer',
                'cos_hp' => '08123456789',
                'cos_alamat' => 'Jl. Audit No. 1',
            ]
        );

        Transaksi::firstOrCreate(
            ['trans_kode' => $this->sampleTransKode],
            [
                'cos_kode' => $this->sampleCustomerKode,
                'kry_kode' => $this->users['Teknisi']->kry_kode,
                'trans_status' => 'Baru',
                'trans_total' => 150000,
            ]
        );

        Produk::firstOrCreate(
            ['kode_barang' => $this->sampleProdukKode],
            [
                'nama_produk' => 'LCD Test iPhone 11',
                'deskripsi' => 'LCD Sparepart iPhone 11',
                'harga' => 250000,
                'gambar' => 'default.png',
            ]
        );
    }

    private function recordTest(string $method, string $uri, ?string $role, string $module, string $desc, int $expectedStatus, $response, float $durationMs): void
    {
        $statusCode = $response->getStatusCode();
        $isPassed = false;

        if ($expectedStatus === 200) {
            $isPassed = ($statusCode === 200);
            $notes = $isPassed ? '200 OK - Halaman Berhasil Dimuat Lengkap' : "Status $statusCode (Tidak sesuai ekspektasi 200)";
        } elseif ($expectedStatus === 302) {
            $isPassed = ($statusCode === 302);
            $target = $response->headers->get('Location') ?? 'Redirect';
            $notes = $isPassed ? "302 Redirect - Dialihkan ke {$target}" : "Status $statusCode (Ekspektasi 302)";
        } else {
            $isPassed = ($statusCode === $expectedStatus);
            $notes = "Status $statusCode (Ekspektasi $expectedStatus)";
        }

        self::$testAuditLog[] = [
            'method' => $method,
            'uri' => $uri,
            'role' => $role ?? 'Tamu (Guest)',
            'module' => $module,
            'desc' => $desc,
            'expected_status' => $expectedStatus,
            'actual_status' => $statusCode,
            'duration_ms' => round($durationMs, 1),
            'is_passed' => $isPassed,
            'status_text' => $isPassed ? 'PASSED' : 'FAILED',
            'notes' => $notes,
        ];
    }

    /**
     * Test Public and Authentication Routes
     */
    public function test_audit_public_and_auth_routes(): void
    {
        // 1. GET / -> redirect to /Auth
        $start = microtime(true);
        $res = $this->get('/');
        $this->recordTest('GET', '/', null, 'Autentikasi', 'Redirect root ke login', 302, $res, (microtime(true) - $start) * 1000);
        $res->assertStatus(302);
        $res->assertRedirect('/Auth');

        // 2. GET /Auth -> login view
        $start = microtime(true);
        $res = $this->get('/Auth');
        $this->recordTest('GET', '/Auth', null, 'Autentikasi', 'Tampilan form login sistem', 200, $res, (microtime(true) - $start) * 1000);
        $res->assertStatus(200);

        // 3. GET /Auth/reset -> reset password view
        $start = microtime(true);
        $res = $this->get('/Auth/reset');
        $this->recordTest('GET', '/Auth/reset', null, 'Autentikasi', 'Tampilan form reset kata sandi', 200, $res, (microtime(true) - $start) * 1000);
        $res->assertStatus(200);

        // 4. POST /Auth/login with invalid data -> redirect back
        $start = microtime(true);
        $res = $this->post('/Auth/login', ['username' => 'invalid_user', 'pswd' => 'wrongpass']);
        $this->recordTest('POST', '/Auth/login', null, 'Autentikasi', 'Validasi gagal login kredensial salah', 302, $res, (microtime(true) - $start) * 1000);
        $res->assertStatus(302);

        // 5. POST /Auth/logout
        $this->actingAs($this->users['Admin']);
        $start = microtime(true);
        $res = $this->post('/Auth/logout');
        $this->recordTest('POST', '/Auth/logout', 'Admin', 'Autentikasi', 'Pemusnahan sesi logout', 302, $res, (microtime(true) - $start) * 1000);
        $res->assertStatus(302);
    }

    /**
     * Test /dashboard Dispatcher for all roles
     */
    public function test_audit_multi_role_dashboard_dispatch(): void
    {
        $roleExpectations = [
            'Admin' => '/Admin',
            'Kasir' => '/Kasir',
            'Customer Service' => '/Service',
            'Teknisi' => '/Teknisi',
            'HR' => '/HR',
            'Magang / PKL' => '/Teknisi',
            'Pimpinan' => '/Admin',
        ];

        foreach ($roleExpectations as $role => $expectedTarget) {
            $this->actingAs($this->users[$role]);
            $start = microtime(true);
            $res = $this->get('/dashboard');
            $this->recordTest('GET', '/dashboard', $role, 'Navigasi Multi-Role', "Redirect dashboard role {$role} ke {$expectedTarget}", 302, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus(302);
            $res->assertRedirect($expectedTarget);
        }
    }

    /**
     * Test Module Produk & Master Data
     */
    public function test_audit_produk_routes(): void
    {
        $this->actingAs($this->users['Admin']);

        $routes = [
            ['GET', '/Produk', 'Katalog master produk & inventaris', 200],
            ['GET', '/Produk/create', 'Form penambahan produk inventaris', 200],
            ['GET', "/Produk/{$this->sampleProdukKode}", 'Detail spesifikasi produk', 200],
            ['GET', "/Produk/{$this->sampleProdukKode}/edit", 'Form edit produk inventaris', 200],
            ['GET', '/produk-ajax?q=lcd', 'Autocomplete live search produk AJAX', 200],
        ];

        foreach ($routes as [$method, $uri, $desc, $expectedStatus]) {
            $start = microtime(true);
            $res = $this->call($method, $uri);
            $this->recordTest($method, $uri, 'Admin', 'Master Produk', $desc, $expectedStatus, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus($expectedStatus);
        }
    }

    /**
     * Test Module Customer
     */
    public function test_audit_customer_routes(): void
    {
        $this->actingAs($this->users['Admin']);

        $routes = [
            ['GET', '/Customer', 'Database pelanggan & segmentasi tier', 200],
            ['GET', '/Customer/create', 'Redirect registrasi pelanggan ke cus_baru', 302],
            ['GET', '/export_customer', 'Cetak rekap data pelanggan PDF', 200],
            ['POST', '/pelanggan/update-skor-semua', 'Kalkulasi massal skor loyalitas RFM', 200],
            ['POST', "/pelanggan/{$this->sampleCustomerKode}/update-skor", 'Kalkulasi skor loyalitas per pelanggan', 200],
        ];

        foreach ($routes as [$method, $uri, $desc, $expectedStatus]) {
            $start = microtime(true);
            $res = $this->call($method, $uri);
            $this->recordTest($method, $uri, 'Admin', 'Master Customer', $desc, $expectedStatus, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus($expectedStatus);
        }
    }

    /**
     * Test Module Kasir
     */
    public function test_audit_kasir_module_routes(): void
    {
        $this->actingAs($this->users['Kasir']);

        $routes = [
            ['GET', '/Kasir', 'Workspace utama dashboard kasir', 200],
            ['GET', "/Kasir/cari/{$this->sampleTransKode}", 'Pencarian tagihan nota kasir', 200],
            ['GET', '/Kasir/pembayaran', 'Histori pembayaran transaksi kasir', 200],
            ['GET', '/Kasir/pembayaran/tunai', 'Filter histori pembayaran metode tunai', 200],
            ['GET', '/Kasir/laporan', 'Laporan pendapatan kasir', 200],
        ];

        foreach ($routes as [$method, $uri, $desc, $expectedStatus]) {
            $start = microtime(true);
            $res = $this->call($method, $uri);
            $this->recordTest($method, $uri, 'Kasir', 'Modul Kasir', $desc, $expectedStatus, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus($expectedStatus);
        }
    }

    /**
     * Test Module Teknisi & Magang
     */
    public function test_audit_teknisi_module_routes(): void
    {
        $this->actingAs($this->users['Teknisi']);

        $routes = [
            ['GET', '/Teknisi', 'Workspace antrean unit kerja teknisi', 200],
            ['GET', "/Teknisi/input_tindakan/{$this->sampleTransKode}", 'Lembar pengisian tindakan servis unit', 200],
            ['GET', '/Teknisi/my_orders', 'Riwayat permintaan sparepart teknisi', 200],
        ];

        foreach ($routes as [$method, $uri, $desc, $expectedStatus]) {
            $start = microtime(true);
            $res = $this->call($method, $uri);
            $this->recordTest($method, $uri, 'Teknisi', 'Modul Teknisi', $desc, $expectedStatus, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus($expectedStatus);
        }

        // Test Magang
        $this->actingAs($this->users['Magang / PKL']);
        $start = microtime(true);
        $res = $this->get('/Teknisi');
        $this->recordTest('GET', '/Teknisi', 'Magang / PKL', 'Modul Teknisi', 'Akses workspace teknisi oleh siswa Magang', 200, $res, (microtime(true) - $start) * 1000);
        $res->assertStatus(200);
    }

    /**
     * Test Module HR & Karyawan
     */
    public function test_audit_hr_module_routes(): void
    {
        $this->actingAs($this->users['HR']);

        $routes = [
            ['GET', '/HR', 'Dashboard analitik SDM & Kepegawaian', 200],
            ['GET', '/HR/karyawan', 'Master data karyawan & anak magang', 200],
            ['GET', '/HR/export_karyawan', 'Export daftar karyawan ke PDF', 200],
            ['GET', '/HR/rekap', 'Rekapitulasi kehadiran & kinerja tim', 200],
            ['GET', '/HR/absensi', 'Log absensi jam kerja harian', 200],
            ['GET', '/HR/kpi', 'Tabel evaluasi Key Performance Indicator', 200],
            ['GET', '/HR/kpi/template', 'Unduh template excel import KPI', 200],
            ['GET', '/HR/input_performance', 'Form input penilaian kinerja staf', 200],
            ['GET', '/HR/certificate_generator', 'Alat pembuat sertifikat magang/kerja', 200],
            ['GET', '/HR/calculate_performance', 'Kalkulasi otomatis skor performa staf', 200],
            ['GET', '/HR/interview', 'Daftar form evaluasi wawancara calon staf', 200],
            ['GET', '/HR/laporan_mingguan', 'Form dan evaluasi laporan mingguan tim', 200],
        ];

        foreach ($routes as [$method, $uri, $desc, $expectedStatus]) {
            $start = microtime(true);
            $res = $this->call($method, $uri);
            $this->recordTest($method, $uri, 'HR', 'Modul HR', $desc, $expectedStatus, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus($expectedStatus);
        }
    }

    /**
     * Test Module Customer Service & Quick Service
     */
    public function test_audit_customer_service_and_quickservice_routes(): void
    {
        $this->actingAs($this->users['Customer Service']);

        $routes = [
            ['GET', '/Service', 'Customer Service', 'Dashboard Customer Service', 200],
            ['GET', '/Service/antrean', 'Customer Service', 'Daftar antrean servis keseluruhan', 200],
            ['GET', '/Service/antrean/Baru', 'Customer Service', 'Filter antrean servis status: Baru', 200],
            ['GET', '/Service/antrean/Proses', 'Customer Service', 'Filter antrean servis status: Proses', 200],
            ['GET', '/Service/antrean/Selesai', 'Customer Service', 'Filter antrean servis status: Selesai', 200],
            ['GET', "/Service/proses/{$this->sampleTransKode}", 'Customer Service', 'Detail progres pengerjaan servis unit', 200],
            ['GET', '/Service/form_baru', 'Customer Service', 'Trigger modal pendaftaran servis baru', 302],
            ['GET', "/Service/batal_transaksi/{$this->sampleTransKode}", 'Customer Service', 'Batalkan transaksi tiket servis (Action redirect)', 302],
            ['GET', "/Service/return_pembayaran/{$this->sampleTransKode}", 'Customer Service', 'Proses retur pembayaran servis (Action redirect)', 302],
            ['GET', "/Service/pembayaran/detail/{$this->sampleTransKode}", 'Customer Service', 'Rincian transaksi pembayaran servis', 200],
            ['GET', '/Service/pembayaran', 'Customer Service', 'Daftar verifikasi status pembayaran unit', 200],
            ['GET', '/Service/laporan', 'Customer Service', 'Laporan transaksi servis CS', 200],
            ['GET', "/Service/cetak/print_1/{$this->sampleTransKode}", 'Customer Service', 'Cetak nota tanda terima servis', 200],

            // Quick Service
            ['GET', '/QuickService', 'Customer Service', 'Daftar antrean servis kilat', 200],
            ['GET', '/QuickService/cos_baru', 'Customer Service', 'Antrean unit servis kilat baru', 200],
            ['GET', '/QuickService/form_baru', 'Customer Service', 'Trigger modal registrasi servis kilat baru', 302],
        ];

        foreach ($routes as [$method, $uri, $role, $desc, $expectedStatus]) {
            $start = microtime(true);
            $res = $this->call($method, $uri);
            $this->recordTest($method, $uri, $role, 'Modul Customer Service', $desc, $expectedStatus, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus($expectedStatus);
        }
    }

    /**
     * Test Module Admin Phase 7 & Shared Modules
     */
    public function test_audit_admin_and_shared_modules(): void
    {
        $this->actingAs($this->users['Admin']);

        $routes = [
            // Admin Group
            ['GET', '/Admin', 'Dashboard utama Admin', 200],
            ['GET', '/Admin/customer', 'Redirect alias menu customer ke modul Customer', 302],
            ['GET', '/Admin/cus_baru', 'Daftar customer servis baru', 200],
            ['GET', '/Admin/cus_konf', 'Konfirmasi customer servis', 200],
            ['GET', '/Admin/cus_proses', 'Customer dalam proses perbaikan', 200],
            ['GET', '/Admin/cus_konf_bank', 'Verifikasi transfer bank customer', 200],
            ['GET', '/Admin/cus_discount', 'Daftar customer berdiskon', 200],
            ['GET', '/Admin/lap_perhari', 'Laporan transaksi harian', 200],
            ['GET', '/Admin/laporan', 'Laporan rekapitulasi keuangan & servis', 200],
            ['GET', '/Admin/export_pdf_lap_perhari', 'Export PDF laporan harian', 200],
            ['GET', '/Admin/export_pdf_laporan', 'Export PDF laporan menyeluruh', 200],
            ['GET', '/Admin/export_excel_lap_perhari', 'Export Excel laporan harian', 200],
            ['GET', '/Admin/export_excel_laporan', 'Export Excel laporan keuangan', 200],
            ['GET', '/Admin/export_dashboard', 'Export statistik dashboard', 200],

            // Shared Admin Group
            ['GET', '/mou', 'Redirect alias lowercase ke MoU Admin', 302],
            ['GET', '/Mou', 'Redirect alias uppercase ke MoU Admin', 302],
            ['GET', '/Admin/mou', 'Daftar MoU & kerjasama instansi B2B', 200],
            ['GET', '/Admin/mou/create', 'Form pembuatan MoU kerjasama baru', 200],
            ['GET', '/Admin/order_approval/oow', 'Persetujuan sparepart out of warranty', 200],
            ['GET', '/Admin/order_approval/iw', 'Persetujuan sparepart in warranty', 200],
            ['GET', '/Admin/order', 'Daftar monitoring order unit servis', 200],
            ['GET', '/Admin/ketersediaan_sparepart', 'Monitoring ketersediaan sparepart', 200],
            ['GET', '/Admin/voucher', 'Daftar kode voucher promosi diskon', 200],
            ['GET', '/Admin/voucher/search', 'Pencarian voucher via AJAX', 200],
            ['GET', '/Admin/voucher/add', 'Form tambah voucher baru', 200],
            ['GET', "/Admin/cetak/print_1/{$this->sampleTransKode}", 'Cetak nota transaksi dari panel admin', 200],

            // Messages & Announcements
            ['GET', '/messages', 'Kotak pesan internal antar staf', 200],
            ['GET', '/messages/unread-count', 'Endpoint hitung pesan belum terbaca', 200],
            ['GET', '/announcements', 'Daftar pengumuman siaran sistem', 200],
            ['GET', '/announcements/unread-count', 'Endpoint hitung pengumuman unread', 200],
        ];

        foreach ($routes as [$method, $uri, $desc, $expectedStatus]) {
            $start = microtime(true);
            $res = $this->call($method, $uri);
            $this->recordTest($method, $uri, 'Admin', 'Modul Admin & Shared', $desc, $expectedStatus, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus($expectedStatus);
        }
    }

    /**
     * Test Public Webhook Callbacks (Xendit)
     */
    public function test_audit_public_webhooks(): void
    {
        $webhooks = [
            ['/xendit/webhook', 'Webhook callback transaksi Xendit (Standard)'],
            ['/api/xendit/webhook', 'Webhook callback API transaksi Xendit'],
        ];

        foreach ($webhooks as [$uri, $desc]) {
            $start = microtime(true);
            $payload = [
                'id' => 'sample_callback_123',
                'external_id' => $this->sampleTransKode,
                'status' => 'PAID',
                'amount' => 150000,
            ];
            $res = $this->postJson($uri, $payload, [
                'x-callback-token' => config('services.xendit.callback_token', 'test_token'),
            ]);
            $this->recordTest('POST', $uri, 'Tamu (Xendit Server)', 'Integrasi Pembayaran', $desc, 200, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus(200);
        }
    }

    /**
     * Test Role Boundary & Penetration Security
     */
    public function test_audit_role_boundary_protection(): void
    {
        $penTests = [
            ['Teknisi', '/Admin', 'Cegah Teknisi membuka Dashboard Admin'],
            ['HR', '/Kasir', 'Cegah HR membuka menu Kasir'],
            ['Customer Service', '/HR', 'Cegah CS membuka menu HR'],
            ['Teknisi', '/Admin/ketersediaan_sparepart', 'Cegah Teknisi mengakses halaman ketersediaan sparepart Admin'],
            ['Kasir', '/HR/karyawan', 'Cegah Kasir membuka Master Karyawan'],
            ['Teknisi', '/Kasir/laporan', 'Cegah Teknisi melihat Laporan Kasir'],
        ];

        foreach ($penTests as [$role, $uri, $desc]) {
            $this->actingAs($this->users[$role]);
            $start = microtime(true);
            $res = $this->get($uri);
            // Must be blocked and redirected
            $this->recordTest('GET', $uri, $role, 'Role Boundary / Security', $desc, 302, $res, (microtime(true) - $start) * 1000);
            $res->assertStatus(302);
        }
    }

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        $passed = 0;
        $failed = 0;
        foreach (self::$testAuditLog as $item) {
            if ($item['is_passed']) {
                $passed++;
            } else {
                $failed++;
            }
        }

        $summary = [
            'total_tested' => count(self::$testAuditLog),
            'passed' => $passed,
            'failed' => $failed,
            'success_rate' => count(self::$testAuditLog) > 0 ? round(($passed / count(self::$testAuditLog)) * 100, 1).'%' : '0%',
            'generated_at' => date('Y-m-d H:i:s'),
            'details' => self::$testAuditLog,
        ];

        $scratchDir = dirname(__DIR__, 2).'/scratch';
        if (! is_dir($scratchDir)) {
            mkdir($scratchDir, 0777, true);
        }
        file_put_contents($scratchDir.'/test_results.json', json_encode($summary, JSON_PRETTY_PRINT));
    }
}
