<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CetakController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerScoringController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\KetersediaanSparepartController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MouController;
use App\Http\Controllers\OrderApprovalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\QuickServiceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/Auth');
});

Route::get('/Auth', [AuthController::class, 'index'])->name('login');
Route::post('/Auth/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['get', 'post'], '/Auth/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/Auth/reset', [AuthController::class, 'reset'])->name('password.reset');
Route::post('/Auth/reset', [AuthController::class, 'postReset'])->name('password.reset.post');

// Phase 3A Routes
Route::middleware(['auth'])->group(function () {
    // Produk Routes
    Route::resource('Produk', ProdukController::class)->parameters([
        'Produk' => 'kode_barang',
    ]);
    Route::get('/produk-ajax', [ProdukController::class, 'ajax_search'])->name('produk.ajax_search');

    // Customer Routes
    Route::resource('Customer', CustomerController::class)->names('customer')->parameters([
        'Customer' => 'id_costomer',
    ]);
    Route::get('/Customer/histori/{kode_transaksi}', [CustomerController::class, 'histori'])->name('customer.histori');
    Route::get('/export_customer', [CustomerController::class, 'export_pdf'])->name('customer.export_pdf');
    Route::post('/pelanggan/{id_costomer}/update-skor', [CustomerScoringController::class, 'updateSkorPelanggan'])->name('customer.update_skor');
    Route::post('/pelanggan/update-skor-semua', [CustomerScoringController::class, 'updateSkorSemuaPelanggan'])->name('customer.update_skor_semua');

    // ==========================================
    // MODULE KASIR (TRANSAKSI)
    // ==========================================
    Route::prefix('Kasir')->middleware('role:Kasir')->group(function () {
        Route::get('/', [KasirController::class, 'index'])->name('kasir.index');
        Route::get('/cari/{kode}', [KasirController::class, 'cari'])->name('kasir.cari');
        Route::post('/save_dp', [KasirController::class, 'save_dp'])->name('kasir.save_dp');
        Route::post('/pelunasan', [KasirController::class, 'pelunasan'])->name('kasir.pelunasan');
        Route::get('/pembayaran/{filter?}', [KasirController::class, 'pembayaran'])->name('kasir.pembayaran');
        Route::get('/laporan', [KasirController::class, 'laporan'])->name('kasir.laporan');
    });

    // ==========================================
    // MODULE TEKNISI
    // ==========================================
    Route::prefix('Teknisi')->middleware('role:Teknisi,Admin')->group(function () {
        Route::get('/', [TeknisiController::class, 'index'])->name('teknisi.index');
        Route::get('/input_tindakan/{kode}', [TeknisiController::class, 'input_tindakan'])->name('teknisi.input_tindakan');
        Route::post('/save_tindakan', [TeknisiController::class, 'save_tindakan'])->name('teknisi.save_tindakan');
        Route::post('/order_sparepart', [TeknisiController::class, 'order_sparepart'])->name('teknisi.order_sparepart');
    });

    // ==========================================
    // MODULE HR & KARYAWAN
    // ==========================================
    Route::prefix('HR')->middleware('role:HR,Admin')->group(function () {
        Route::get('/', [HrController::class, 'index'])->name('hr.index');
        Route::get('/karyawan', [HrController::class, 'karyawan'])->name('hr.karyawan');
        Route::get('/export_karyawan', [HrController::class, 'export_pdf'])->name('hr.export_karyawan');
        Route::post('/save_karyawan', [HrController::class, 'save_karyawan'])->name('hr.save_karyawan');
        Route::post('/save_magang', [HrController::class, 'save_magang'])->name('hr.save_magang');
        Route::post('/update_karyawan', [HrController::class, 'update_karyawan'])->name('hr.update_karyawan');
        Route::get('/delete_karyawan/{kode}', [HrController::class, 'delete_karyawan'])->name('hr.delete_karyawan');

        Route::get('/rekap', [HrController::class, 'rekap'])->name('hr.rekap');
        Route::get('/absensi', [HrController::class, 'absensi'])->name('hr.absensi');
        Route::post('/absensi', [HrController::class, 'save_absensi'])->name('hr.save_absensi');
        Route::post('/absensi/delete/{id}', [HrController::class, 'delete_absensi'])->name('hr.delete_absensi');

        Route::get('/kpi', [HrController::class, 'kpi'])->name('hr.kpi');
        Route::post('/kpi', [HrController::class, 'save_kpi'])->name('hr.save_kpi');
        Route::post('/kpi/import', [HrController::class, 'import_kpi'])->name('hr.import_kpi');
        Route::get('/kpi/template', [HrController::class, 'template_kpi'])->name('hr.template_kpi');
        Route::post('/kpi/delete/{id}', [HrController::class, 'delete_kpi'])->name('hr.delete_kpi');

        Route::get('/input_performance', [HrController::class, 'input_performance'])->name('hr.input_performance');
        Route::post('/input_performance/save', [HrController::class, 'save_performance'])->name('hr.save_performance');
        Route::get('/certificate_generator', [HrController::class, 'certificate_generator'])->name('hr.certificate_generator');
        Route::get('/calculate_performance', [HrController::class, 'calculate_performance'])->name('hr.calculate_performance');

        Route::get('/interview', [HrController::class, 'interview'])->name('hr.interview');
        Route::post('/interview/save', [HrController::class, 'save_interview'])->name('hr.interview.save');

        Route::get('/laporan_mingguan', [HrController::class, 'laporan_mingguan'])->name('hr.laporan_mingguan');
        Route::post('/laporan_mingguan', [HrController::class, 'save_laporan_mingguan'])->name('hr.save_laporan_mingguan');
        Route::post('/laporan_mingguan/delete/{id}', [HrController::class, 'delete_laporan_mingguan'])->name('hr.delete_laporan_mingguan');

        // Route::get('/pencatatan', [HrController::class, 'pencatatan'])->name('hr.pencatatan');
        // Route::post('/pencatatan', [HrController::class, 'save_pencatatan'])->name('hr.save_pencatatan');
        // Route::post('/pencatatan/delete/{id}', [HrController::class, 'delete_pencatatan'])->name('hr.delete_pencatatan');
    });

    // ==========================================
    // MODULE CUSTOMER SERVICE (CS) - Phase 6
    // ==========================================
    Route::prefix('Service')->middleware('role:Customer Service,Admin')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('service.index');
        Route::get('/antrean/{status?}', [ServiceController::class, 'antrean'])->name('service.antrean');
        Route::get('/proses/{id}', [ServiceController::class, 'proses'])->name('service.proses');
        Route::post('/proses/{id}/tindakan', [ServiceController::class, 'save_tindakan'])->name('service.save_tindakan');
        Route::delete('/proses/tindakan/{id}', [ServiceController::class, 'delete_tindakan'])->name('service.delete_tindakan');
        Route::post('/proses/{id}/simpan', [ServiceController::class, 'simpan_proses'])->name('service.simpan_proses');
        Route::post('/update_customer/{id}', [ServiceController::class, 'update_customer'])->name('service.update_customer');
        Route::get('/form_baru', [ServiceController::class, 'create'])->name('service.create');
        Route::post('/save_trans', [ServiceController::class, 'save_trans'])->name('service.save_trans');
        Route::get('/batal_transaksi/{kode}', [ServiceController::class, 'batal_transaksi'])->name('service.batal_transaksi');
        Route::get('/return_pembayaran/{kode}', [ServiceController::class, 'return_pembayaran'])->name('service.return_pembayaran');
        Route::get('/pembayaran/detail/{kode}', [ServiceController::class, 'detail_pembayaran'])->name('service.pembayaran.detail');
        Route::get('/pembayaran/{filter?}', [ServiceController::class, 'pembayaran'])->name('service.pembayaran');
        Route::get('/laporan', [ServiceController::class, 'laporan'])->name('service.laporan');

        // Cetak (Service)
        Route::get('/cetak/print_1/{param}', [CetakController::class, 'print_1'])->name('service.cetak.print_1');
    });

    // ==========================================
    // MODULE QUICK SERVICE - Phase 6
    // ==========================================
    Route::prefix('QuickService')->middleware('role:Customer Service,Kasir,Admin')->group(function () {
        Route::get('/', [QuickServiceController::class, 'antrean'])->name('quickservice.index');
        Route::get('/cos_baru', [QuickServiceController::class, 'antrean'])->name('quickservice.cos_baru');
        Route::get('/antrean/{status?}', [QuickServiceController::class, 'antrean'])->name('quickservice.antrean');
        Route::get('/form_baru', [QuickServiceController::class, 'create'])->name('quickservice.create');
        Route::post('/save_trans', [QuickServiceController::class, 'save_trans'])->name('quickservice.save_trans');
    });

    // ==========================================
    // MODULE ADMIN - Phase 7
    // ==========================================
    Route::prefix('Admin')->middleware('role:Admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/customer', [AdminController::class, 'customer'])->name('admin.customer');
        Route::get('/cus_baru', [AdminController::class, 'cus_baru'])->name('admin.cus_baru');
        Route::get('/cus_konf', [AdminController::class, 'cus_konf'])->name('admin.cus_konf');
        Route::get('/cus_proses', [AdminController::class, 'cus_proses'])->name('admin.cus_proses');
        Route::get('/cus_konf_bank', [AdminController::class, 'cus_konf_bank'])->name('admin.cus_konf_bank');
        Route::get('/cus_discount', [AdminController::class, 'cus_discount'])->name('admin.cus_discount');
        Route::get('/konfirmasi/{kode}', [AdminController::class, 'konfirmasi'])->name('admin.konfirmasi');
        Route::redirect('/konfirmasi', '/Admin/cus_baru');
        Route::redirect('/cus_detail/{kode}', '/Admin/konfirmasi/{kode}');
        Route::redirect('/cus_detail', '/Admin/cus_baru');
        Route::post('/update_konf', [AdminController::class, 'update_konf'])->name('admin.update_konf');
        Route::post('/setoran', [AdminController::class, 'setoran'])->name('admin.setoran');
        Route::get('/lap_perhari', [AdminController::class, 'lap_perhari'])->name('admin.lap_perhari');
        Route::get('/laporan', [AdminController::class, 'laporan'])->name('admin.laporan');
        Route::get('/export_pdf_lap_perhari', [AdminController::class, 'export_pdf_lap_perhari'])->name('admin.export_pdf_lap_perhari');
        Route::get('/export_pdf_laporan', [AdminController::class, 'export_pdf_laporan'])->name('admin.export_pdf_laporan');
        Route::get('/export_excel_lap_perhari', [ExportController::class, 'lap_perhari_excel'])->name('admin.export_excel_lap_perhari');
        Route::get('/export_excel_laporan', [ExportController::class, 'lap_excel'])->name('admin.export_excel_laporan');
        Route::get('/export_dashboard', [AdminController::class, 'export_dashboard'])->name('admin.export_dashboard');
        // Order Approval Routes
        Route::get('/order_approval/oow', [OrderApprovalController::class, 'pending_oow'])->name('admin.order_approval.oow');
        Route::get('/order_approval/iw', [OrderApprovalController::class, 'pending_iw'])->name('admin.order_approval.iw');
        Route::post('/order_approval/{id}/approve', [OrderApprovalController::class, 'approve'])->name('admin.order_approval.approve');
        Route::post('/order_approval/{id}/reject', [OrderApprovalController::class, 'reject'])->name('admin.order_approval.reject');

    }); // <-- Batas akhir Group Admin khusus Admin

    // ==========================================
    // MODULE MOU (Diletakkan di luar group Admin khusus,
    // agar bisa diakses Admin, HR, Kasir, dan CS tanpa error 403/404)
    // ==========================================
    Route::get('/mou', function () {
        return redirect()->route('admin.mou.index');
    });
    Route::get('/Mou', function () {
        return redirect()->route('admin.mou.index');
    });

    Route::prefix('Admin')->middleware('role:Admin,HR,Kasir,Customer Service')->group(function () {
        Route::get('/mou', [MouController::class, 'index'])->name('admin.mou.index');
        Route::get('/mou/create', [MouController::class, 'create_form'])->name('admin.mou.create_form');
        Route::post('/mou/create', [MouController::class, 'create'])->name('admin.mou.create');
        Route::get('/mou/edit/{id}', [MouController::class, 'edit_form'])->name('admin.mou.edit_form');
        Route::post('/mou/edit/{id}', [MouController::class, 'edit'])->name('admin.mou.edit');
        Route::post('/mou/delete/{id}', [MouController::class, 'delete'])->name('admin.mou.delete');
        Route::get('/mou/download/{id}', [MouController::class, 'download'])->name('admin.mou.download');

        // Order Routes
        Route::get('/order/{filter?}', [OrderController::class, 'index'])->name('admin.order.index');
        Route::post('/order/update_status', [OrderController::class, 'update_status'])->name('admin.order.update_status');
        Route::post('/order/confirm', [OrderController::class, 'confirm_order'])->name('admin.order.confirm_order');
        Route::post('/order/approve', [OrderController::class, 'approve_order'])->name('admin.order.approve_order');
        Route::post('/order/complete', [OrderController::class, 'service_complete'])->name('admin.order.service_complete');
        Route::post('/order/failed', [OrderController::class, 'service_failed'])->name('admin.order.service_failed');
        Route::post('/order/inform_unavailable', [OrderController::class, 'inform_unavailable'])->name('admin.order.inform_unavailable');
        Route::post('/order/update_trans_total', [OrderController::class, 'update_trans_total'])->name('admin.order.update_trans_total');

        // Ketersediaan Sparepart Routes
        Route::get('/ketersediaan_sparepart', [KetersediaanSparepartController::class, 'index'])->name('admin.ketersediaan_sparepart');
        Route::get('/ketersediaan_sparepart/sampai/{id}', [KetersediaanSparepartController::class, 'barang_sampai'])->name('admin.ketersediaan_sparepart.barang_sampai');

        // Voucher Routes
        Route::get('/voucher', [VoucherController::class, 'index'])->name('admin.voucher.index');
        Route::get('/voucher/search', [VoucherController::class, 'ajax_search'])->name('admin.voucher.search');
        Route::get('/voucher/add', [VoucherController::class, 'add'])->name('admin.voucher.add');
        Route::post('/voucher/save', [VoucherController::class, 'save'])->name('admin.voucher.save');
        Route::get('/voucher/edit/{id}', [VoucherController::class, 'edit'])->name('admin.voucher.edit');
        Route::post('/voucher/update/{id}', [VoucherController::class, 'update'])->name('admin.voucher.update');
        Route::post('/voucher/delete/{id}', [VoucherController::class, 'delete'])->name('admin.voucher.delete');

        // Cetak
        Route::get('/cetak/print_1/{param}', [CetakController::class, 'print_1'])->name('admin.cetak.print_1');
        Route::get('/cetak/download/{trans_kode}/{dtl_status?}', [CetakController::class, 'download'])->name('admin.cetak.download');
        Route::get('/cetak/print_3/{kode}', [CetakController::class, 'print_3'])->name('admin.cetak.print_3');
        Route::get('/cetak/print_4/{kode}', [CetakController::class, 'print_4'])->name('admin.cetak.print_4');
        Route::get('/cetak/print_5/{kode}', [CetakController::class, 'print_5'])->name('admin.cetak.print_5');
        Route::get('/cetak/print_6/{kode}', [CetakController::class, 'print_6'])->name('admin.cetak.print_6');
    });

    // ==========================================
    // MESSAGES (Pesan Internal Antar Role)
    // ==========================================
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/unread-count', function () {
        return response()->json(['count' => MessageController::unreadCount()]);
    })->name('messages.unread_count');

    // ==========================================
    // ANNOUNCEMENTS (Pemberitahuan Sistem / Maintenance)
    // ==========================================
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/mark-all-read', [AnnouncementController::class, 'markAllRead'])->name('announcements.mark_all_read');
    Route::get('/announcements/unread-count', [AnnouncementController::class, 'unreadCount'])->name('announcements.unread_count');

}); // <-- Batas akhir Route::middleware(['auth'])
