<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect('/Auth');
});

Route::get('/Auth', [AuthController::class, 'index'])->name('login');
Route::post('/Auth/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/Auth/logout', [AuthController::class, 'logout'])->name('logout');



// Phase 3A Routes
Route::middleware(['auth'])->group(function () {
    // Produk Routes
    Route::resource('Produk', App\Http\Controllers\ProdukController::class)->parameters([
        'Produk' => 'kode_barang'
    ]);
    Route::get('/produk-ajax', [App\Http\Controllers\ProdukController::class, 'ajax_search'])->name('produk.ajax_search');
    
    // Customer Routes
    Route::resource('Customer', App\Http\Controllers\CustomerController::class)->parameters([
        'Customer' => 'id_costomer'
    ]);
    Route::get('/Customer/histori/{kode_transaksi}', [App\Http\Controllers\CustomerController::class, 'histori'])->name('customer.histori');

    // ==========================================
    // MODULE KASIR (TRANSAKSI)
    // ==========================================
    Route::prefix('Kasir')->middleware('role:Kasir')->group(function () {
        Route::get('/', [App\Http\Controllers\KasirController::class, 'index'])->name('kasir.index');
        Route::get('/cari/{kode}', [App\Http\Controllers\KasirController::class, 'cari'])->name('kasir.cari');
        Route::post('/save_dp', [App\Http\Controllers\KasirController::class, 'save_dp'])->name('kasir.save_dp');
        Route::post('/pelunasan', [App\Http\Controllers\KasirController::class, 'pelunasan'])->name('kasir.pelunasan');
        Route::get('/pembayaran/{filter?}', [App\Http\Controllers\KasirController::class, 'pembayaran'])->name('kasir.pembayaran');
        Route::get('/laporan', [App\Http\Controllers\KasirController::class, 'laporan'])->name('kasir.laporan');
    });

    // ==========================================
    // MODULE TEKNISI
    // ==========================================
    Route::prefix('Teknisi')->middleware('role:Teknisi')->group(function () {
        Route::get('/', [App\Http\Controllers\TeknisiController::class, 'index'])->name('teknisi.index');
        Route::get('/input_tindakan/{kode}', [App\Http\Controllers\TeknisiController::class, 'input_tindakan'])->name('teknisi.input_tindakan');
        Route::post('/save_tindakan', [App\Http\Controllers\TeknisiController::class, 'save_tindakan'])->name('teknisi.save_tindakan');
        Route::post('/order_sparepart', [App\Http\Controllers\TeknisiController::class, 'order_sparepart'])->name('teknisi.order_sparepart');
    });

    // ==========================================
    // MODULE HR & KARYAWAN
    // ==========================================
    Route::prefix('HR')->middleware('role:HR,Admin')->group(function () {
        Route::get('/', [App\Http\Controllers\HrController::class, 'index'])->name('hr.index');
        Route::get('/karyawan', [App\Http\Controllers\HrController::class, 'karyawan'])->name('hr.karyawan');
        
        Route::get('/absensi', [App\Http\Controllers\HrController::class, 'absensi'])->name('hr.absensi');
        Route::post('/absensi', [App\Http\Controllers\HrController::class, 'save_absensi'])->name('hr.save_absensi');
        Route::post('/absensi/delete/{id}', [App\Http\Controllers\HrController::class, 'delete_absensi'])->name('hr.delete_absensi');
        
        Route::get('/kpi', [App\Http\Controllers\HrController::class, 'kpi'])->name('hr.kpi');
        Route::post('/kpi', [App\Http\Controllers\HrController::class, 'save_kpi'])->name('hr.save_kpi');
        Route::post('/kpi/delete/{id}', [App\Http\Controllers\HrController::class, 'delete_kpi'])->name('hr.delete_kpi');

        Route::get('/arsip', [App\Http\Controllers\HrController::class, 'arsip'])->name('hr.arsip');
        Route::post('/arsip', [App\Http\Controllers\HrController::class, 'save_arsip'])->name('hr.save_arsip');
        Route::post('/arsip/delete/{id}', [App\Http\Controllers\HrController::class, 'delete_arsip'])->name('hr.delete_arsip');

        Route::get('/laporan_mingguan', [App\Http\Controllers\HrController::class, 'laporan_mingguan'])->name('hr.laporan_mingguan');
        Route::post('/laporan_mingguan', [App\Http\Controllers\HrController::class, 'save_laporan_mingguan'])->name('hr.save_laporan_mingguan');
        Route::post('/laporan_mingguan/delete/{id}', [App\Http\Controllers\HrController::class, 'delete_laporan_mingguan'])->name('hr.delete_laporan_mingguan');

        Route::get('/pencatatan', [App\Http\Controllers\HrController::class, 'pencatatan'])->name('hr.pencatatan');
        Route::post('/pencatatan', [App\Http\Controllers\HrController::class, 'save_pencatatan'])->name('hr.save_pencatatan');
        Route::post('/pencatatan/delete/{id}', [App\Http\Controllers\HrController::class, 'delete_pencatatan'])->name('hr.delete_pencatatan');
    });

    // ==========================================
    // MODULE CUSTOMER SERVICE (CS) - Phase 6
    // ==========================================
    Route::prefix('Service')->middleware('role:Customer Service,Admin')->group(function () {
        Route::get('/', [App\Http\Controllers\ServiceController::class, 'index'])->name('service.index');
        Route::get('/antrean/{status?}', [App\Http\Controllers\ServiceController::class, 'antrean'])->name('service.antrean');
        Route::get('/form_baru', [App\Http\Controllers\ServiceController::class, 'create'])->name('service.create');
        Route::post('/save_trans', [App\Http\Controllers\ServiceController::class, 'save_trans'])->name('service.save_trans');
        Route::get('/batal_transaksi/{kode}', [App\Http\Controllers\ServiceController::class, 'batal_transaksi'])->name('service.batal_transaksi');
        Route::get('/return_pembayaran/{kode}', [App\Http\Controllers\ServiceController::class, 'return_pembayaran'])->name('service.return_pembayaran');
    });

    // ==========================================
    // MODULE QUICK SERVICE - Phase 6
    // ==========================================
    Route::prefix('QuickService')->middleware('role:Customer Service,Kasir,Admin')->group(function () {
        Route::get('/', [App\Http\Controllers\QuickServiceController::class, 'index'])->name('quickservice.index');
        Route::get('/antrean/{status?}', [App\Http\Controllers\QuickServiceController::class, 'antrean'])->name('quickservice.antrean');
        Route::get('/form_baru', [App\Http\Controllers\QuickServiceController::class, 'create'])->name('quickservice.create');
        Route::post('/save_trans', [App\Http\Controllers\QuickServiceController::class, 'save_trans'])->name('quickservice.save_trans');
    });

    // ==========================================
    // MODULE ADMIN - Phase 7
    // ==========================================
    Route::prefix('Admin')->middleware('role:Admin')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.index');
        Route::get('/customer', [App\Http\Controllers\AdminController::class, 'customer'])->name('admin.customer');
        Route::get('/cus_baru', [App\Http\Controllers\AdminController::class, 'cus_baru'])->name('admin.cus_baru');
        Route::get('/cus_konf', [App\Http\Controllers\AdminController::class, 'cus_konf'])->name('admin.cus_konf');
        Route::get('/cus_proses', [App\Http\Controllers\AdminController::class, 'cus_proses'])->name('admin.cus_proses');
        Route::get('/cus_konf_bank', [App\Http\Controllers\AdminController::class, 'cus_konf_bank'])->name('admin.cus_konf_bank');
        Route::get('/konfirmasi/{kode}', [App\Http\Controllers\AdminController::class, 'konfirmasi'])->name('admin.konfirmasi');
        Route::post('/update_konf', [App\Http\Controllers\AdminController::class, 'update_konf'])->name('admin.update_konf');
        Route::post('/setoran', [App\Http\Controllers\AdminController::class, 'setoran'])->name('admin.setoran');
        Route::get('/lap_perhari', [App\Http\Controllers\AdminController::class, 'lap_perhari'])->name('admin.lap_perhari');
        Route::get('/laporan', [App\Http\Controllers\AdminController::class, 'laporan'])->name('admin.laporan');
        Route::get('/export_pdf_lap_perhari', [App\Http\Controllers\AdminController::class, 'export_pdf_lap_perhari'])->name('admin.export_pdf_lap_perhari');
        Route::get('/export_pdf_laporan', [App\Http\Controllers\AdminController::class, 'export_pdf_laporan'])->name('admin.export_pdf_laporan');
        Route::get('/export_excel_lap_perhari', [App\Http\Controllers\ExportController::class, 'lap_perhari_excel'])->name('admin.export_excel_lap_perhari');
        Route::get('/export_excel_laporan', [App\Http\Controllers\ExportController::class, 'lap_excel'])->name('admin.export_excel_laporan');
        Route::get('/export_dashboard', [App\Http\Controllers\AdminController::class, 'export_dashboard'])->name('admin.export_dashboard');
        // Order Routes
        Route::get('/order/{filter?}', [App\Http\Controllers\OrderController::class, 'index'])->name('admin.order.index');
        Route::post('/order/update_status', [App\Http\Controllers\OrderController::class, 'update_status'])->name('admin.order.update_status');
        Route::post('/order/confirm', [App\Http\Controllers\OrderController::class, 'confirm_order'])->name('admin.order.confirm_order');
        Route::post('/order/approve', [App\Http\Controllers\OrderController::class, 'approve_order'])->name('admin.order.approve_order');
        Route::post('/order/complete', [App\Http\Controllers\OrderController::class, 'service_complete'])->name('admin.order.service_complete');
        Route::post('/order/failed', [App\Http\Controllers\OrderController::class, 'service_failed'])->name('admin.order.service_failed');
        Route::post('/order/inform_unavailable', [App\Http\Controllers\OrderController::class, 'inform_unavailable'])->name('admin.order.inform_unavailable');
        Route::post('/order/update_trans_total', [App\Http\Controllers\OrderController::class, 'update_trans_total'])->name('admin.order.update_trans_total');
        
        // Order Approval Routes
        Route::get('/order_approval/oow', [App\Http\Controllers\OrderApprovalController::class, 'pending_oow'])->name('admin.order_approval.oow');
        Route::get('/order_approval/iw', [App\Http\Controllers\OrderApprovalController::class, 'pending_iw'])->name('admin.order_approval.iw');
        Route::post('/order_approval/{id}/approve', [App\Http\Controllers\OrderApprovalController::class, 'approve'])->name('admin.order_approval.approve');
        Route::post('/order_approval/{id}/reject', [App\Http\Controllers\OrderApprovalController::class, 'reject'])->name('admin.order_approval.reject');
        
        // Ketersediaan Sparepart Routes
        Route::get('/ketersediaan_sparepart', [App\Http\Controllers\KetersediaanSparepartController::class, 'index'])->name('admin.ketersediaan_sparepart');
        Route::get('/ketersediaan_sparepart/sampai/{id}', [App\Http\Controllers\KetersediaanSparepartController::class, 'barang_sampai'])->name('admin.ketersediaan_sparepart.barang_sampai');

        // Voucher Routes
        Route::get('/voucher', [App\Http\Controllers\VoucherController::class, 'index'])->name('admin.voucher.index');
        Route::get('/voucher/search', [App\Http\Controllers\VoucherController::class, 'ajax_search'])->name('admin.voucher.search');
        Route::get('/voucher/add', [App\Http\Controllers\VoucherController::class, 'add'])->name('admin.voucher.add');
        Route::post('/voucher/save', [App\Http\Controllers\VoucherController::class, 'save'])->name('admin.voucher.save');
        Route::get('/voucher/edit/{id}', [App\Http\Controllers\VoucherController::class, 'edit'])->name('admin.voucher.edit');
        Route::post('/voucher/update/{id}', [App\Http\Controllers\VoucherController::class, 'update'])->name('admin.voucher.update');
        Route::post('/voucher/delete/{id}', [App\Http\Controllers\VoucherController::class, 'delete'])->name('admin.voucher.delete');

        // Mou
        Route::get('/mou', [App\Http\Controllers\MouController::class, 'index'])->name('admin.mou.index');
        Route::get('/mou/create', [App\Http\Controllers\MouController::class, 'create_form'])->name('admin.mou.create_form');
        Route::post('/mou/create', [App\Http\Controllers\MouController::class, 'create'])->name('admin.mou.create');
        Route::get('/mou/edit/{id}', [App\Http\Controllers\MouController::class, 'edit_form'])->name('admin.mou.edit_form');
        Route::post('/mou/edit/{id}', [App\Http\Controllers\MouController::class, 'edit'])->name('admin.mou.edit');
        Route::post('/mou/delete/{id}', [App\Http\Controllers\MouController::class, 'delete'])->name('admin.mou.delete');
        Route::get('/mou/download/{id}', [App\Http\Controllers\MouController::class, 'download'])->name('admin.mou.download');

        // Cetak
        Route::get('/cetak/print_1/{param}', [App\Http\Controllers\CetakController::class, 'print_1'])->name('admin.cetak.print_1');
        Route::get('/cetak/download/{trans_kode}/{dtl_status?}', [App\Http\Controllers\CetakController::class, 'download'])->name('admin.cetak.download');
        Route::get('/cetak/print_3/{kode}', [App\Http\Controllers\CetakController::class, 'print_3'])->name('admin.cetak.print_3');
        Route::get('/cetak/print_4/{kode}', [App\Http\Controllers\CetakController::class, 'print_4'])->name('admin.cetak.print_4');
        Route::get('/cetak/print_5/{kode}', [App\Http\Controllers\CetakController::class, 'print_5'])->name('admin.cetak.print_5');
        Route::get('/cetak/print_6/{kode}', [App\Http\Controllers\CetakController::class, 'print_6'])->name('admin.cetak.print_6');
    });
});
