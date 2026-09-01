@extends('layouts.app')

@section('content')
@php
    $totalDP = $bayar->where('dtl_status', 'DP')->sum('dtl_jml_bayar');
    $totalLunas = $bayar->where('dtl_status', 'PELUNASAN')->sum('dtl_jml_bayar');
    $totalTerbayar = $totalDP + $totalLunas;
    $netTotal = $trans->trans_total;
    $discount = $trans->trans_discount ?? 0;
    $sisaPelunasan = max(0, $netTotal - $totalTerbayar);
@endphp

<style>
/* ===== CUSTOM STYLE FOR DETAIL TABS ===== */
.tab-trigger {
    transition: all 0.2s ease-in-out;
}
.tab-trigger.active {
    background-color: #1e1b4b !important; /* dark indigo/blue */
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(30, 27, 75, 0.25);
}

/* ===== DATATABLE OVERRIDES ===== */
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
    color: #4a5568;
    font-size: 0.875rem;
}
.dataTables_wrapper .dataTables_length select {
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.25rem 1.5rem 0.25rem 0.5rem;
    outline: none;
    background-color: white;
}
.dataTables_wrapper .dataTables_filter input {
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    margin-left: 0.5rem;
    outline: none;
    width: 180px;
}
.dataTables_wrapper .dataTables_info {
    margin-top: 1rem;
    color: #4a5568;
    font-size: 0.875rem;
    float: left;
}
.dataTables_wrapper .dataTables_paginate {
    margin-top: 1rem;
    float: right;
}
.dataTables_wrapper .paginate_button {
    padding: 0.375rem 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.375rem;
    margin-left: 0.25rem;
    cursor: pointer;
    color: #4a5568 !important;
    background: white;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-block;
}
.dataTables_wrapper .paginate_button:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
    color: #0f172a !important;
}
.dataTables_wrapper .paginate_button.current {
    background: #0041c3 !important;
    color: white !important;
    border-color: #0041c3 !important;
    font-weight: 600;
}
.dataTables_wrapper .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>

<!-- Header Area -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Data Pembayaran</h1>
        <p>Manage transaction payment details</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('kasir.index') }}" class="header-btn" title="Kembali ke Daftar Customer" style="text-decoration: none;">
            <i data-feather="arrow-left"></i>
        </a>
        <div class="header-btn" title="Notifikasi">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn" id="topbar-mail-btn" title="Kotak Pesan">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">
    @if(session('sukses'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('sukses') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981'
            });
        });
    </script>
    @endif
    
    @if(session('gagal'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('gagal') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ef4444'
            });
        });
    </script>
    @endif

    <!-- Instruction Bar matching the screenshot -->
    <div class="intro-y box p-4 mb-5 bg-white shadow-sm rounded-lg border border-gray-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="text-xs sm:text-sm text-gray-600 font-medium leading-relaxed">
            <i data-feather="info" class="w-4 h-4 inline-block text-blue-500 mr-1.5 align-middle"></i>
            Klik icon <span class="inline-flex w-4 h-4 bg-red-500 rounded text-white text-[9px] items-center justify-center align-middle mx-1"><i data-feather="file-text" class="w-2.5 h-2.5"></i></span> untuk cetak nota pembayaran,
            icon <span class="inline-flex w-4 h-4 bg-blue-500 rounded text-white text-[9px] items-center justify-center align-middle mx-1"><i data-feather="printer" class="w-2.5 h-2.5"></i></span> untuk cetak thermal print nota pembayaran,
            dan icon <span class="inline-flex w-4 h-4 bg-green-500 rounded text-white text-[9px] items-center justify-center align-middle mx-1"><i data-feather="message-circle" class="w-2.5 h-2.5"></i></span> untuk mengirim pesan WhatsApp kepada customer terkait pembayaran ini.
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('kasir.pembayaran') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-indigo-900 hover:bg-indigo-950 rounded-md shadow-sm transition-colors duration-200">
                Pembayaran hari ini <i data-feather="chevron-down" class="w-3.5 h-3.5"></i>
            </a>
        </div>
    </div>

    <!-- Main Payment Grid -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Left Column: Histori Pembayaran -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5">
                <h2 class="font-bold text-lg border-b pb-2 mb-4">Informasi Customer</h2>
                <table class="table w-full mb-6">
                    <tr><td class="w-1/4 font-semibold">Nama</td><td>: {{ $trans->customer->cos_nama ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">No. HP</td><td>: {{ $trans->customer->cos_hp ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">Alamat</td><td>: {{ $trans->customer->cos_alamat ?? '-' }}</td></tr>
                </table>

                <h2 class="font-bold text-lg border-b pb-2 mb-4">Rincian Tindakan / Servis</h2>
                <table class="table table-report w-full mb-6">
                    <thead>
                        <tr>
                            <th>BARANG / JASA</th>
                            <th class="text-right">HARGA</th>
                            <th class="text-center">QTY</th>
                            <th class="text-right">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tindakan as $item)
                        <tr>
                            <td>{{ $item->tdkn_barang }}</td>
                            <td class="text-right">Rp {{ number_format($item->tdkn_harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->tdkn_qty }}</td>
                            <td class="text-right font-medium">Rp {{ number_format($item->tdkn_subtot, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Belum ada rincian.</td></tr>
                        @endforelse
                        <tr>
                            <td colspan="3" class="text-right font-bold">TOTAL KESELURUHAN</td>
                            <td class="text-right font-bold text-theme-6">Rp {{ number_format($trans->trans_total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                <h2 class="font-bold text-lg border-b pb-2 mb-4">Riwayat Pembayaran</h2>
                <table class="table table-report w-full">
                    <thead>
                        <tr>
                            <th>TANGGAL</th>
                            <th>STATUS</th>
                            <th>METODE</th>
                            <th class="text-right">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalBayar = 0; @endphp
                        @forelse($bayar as $b)
                        @php $totalBayar += $b->dtl_jml_bayar; @endphp
                        <tr>
                            <td>{{ $b->dtl_tanggal }} {{ $b->dtl_jam }}</td>
                            <td><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $b->dtl_status }}</span></td>
                            <td>{{ $b->dtl_jenis_bayar }} {{ $b->dtl_bank != '-' ? '('.$b->dtl_bank.')' : '' }}</td>
                            <td class="text-right">Rp {{ number_format($b->dtl_jml_bayar, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Belum ada pembayaran.</td></tr>
                        @endforelse
                        <tr>
                            <td colspan="3" class="text-right font-bold">TOTAL TERBAYAR</td>
                            <td class="text-right font-bold text-theme-9">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right font-bold">SISA TAGIHAN</td>
                            <td class="text-right font-bold text-theme-6">Rp {{ number_format(max(0, $trans->trans_total - $totalBayar), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Column: Detail & Payment Action Panel -->
        <div class="col-span-12 lg:col-span-4">
            <div class="intro-y box p-5 bg-white shadow-sm rounded-lg border border-gray-100">
                <!-- Tab Triggers -->
                <div class="flex gap-1.5 mb-5 p-1 bg-gray-100 rounded-lg">
                    <button class="tab-trigger flex-1 py-2 text-center text-xs font-semibold rounded-md bg-indigo-900 text-white active" data-target="#detailPanel">
                        Detail
                    </button>
                    <button class="tab-trigger flex-1 py-2 text-center text-xs font-semibold rounded-md text-gray-600 hover:bg-gray-200" data-target="#pelunasanPanel">
                        Pelunasan
                    </button>
                    <button class="tab-trigger flex-1 py-2 text-center text-xs font-semibold rounded-md text-gray-600 hover:bg-gray-200" data-target="#dpPanel">
                        DP
                    </button>
                </div>

                <!-- Tab Panel 1: Detail -->
                <div class="tab-panel" id="detailPanel">
                    <!-- Invoice -->
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Invoice</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $trans->trans_kode }}</span>
                        </div>
                        <i data-feather="file-text" class="w-4 h-4 text-gray-400"></i>
                    </div>

                    <!-- Nama Customer -->
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Nama Customer</span>
                            <span class="text-sm font-semibold text-gray-700">{{ $trans->customer->cos_nama ?? '-' }}</span>
                        </div>
                        <i data-feather="user" class="w-4 h-4 text-gray-400"></i>
                    </div>

                    <!-- Model Unit -->
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Model Unit</span>
                            <span class="text-sm font-semibold text-gray-700">
                                {{ $trans->customer->cos_model ?? '-' }} @if(isset($trans->customer->cos_tipe)) - {{ $trans->customer->cos_tipe }} @endif
                            </span>
                        </div>
                        <i data-feather="monitor" class="w-4 h-4 text-gray-400"></i>
                    </div>

                    <!-- Alamat -->
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Alamat</span>
                            <span class="text-sm font-semibold text-gray-700 leading-normal">
                                {{ $trans->customer->cos_alamat ?? '-' }}
                            </span>
                        </div>
                        <i data-feather="map-pin" class="w-4 h-4 text-gray-400"></i>
                    </div>

                    <!-- Tanggal dan waktu -->
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <span class="text-xs text-gray-400 font-medium block">Tanggal dan waktu</span>
                            <span class="text-sm font-semibold text-gray-700">
                                {{ $trans->cos_tanggal ?? ($trans->created_at ? $trans->created_at->format('d-m-Y') : '-') }} 
                                {{ $trans->cos_jam ?? ($trans->created_at ? $trans->created_at->format('H:i:s') : '') }}
                            </span>
                        </div>
                        <i data-feather="clock" class="w-4 h-4 text-gray-400"></i>
                    </div>

                    <!-- Action Button -->
                    <div class="mt-6">
                        <a href="{{ route('admin.cetak.print_6', $trans->trans_kode) }}" 
                           target="_blank" 
                           class="inline-flex items-center justify-center gap-1.5 w-full py-2.5 rounded-md text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm duration-200">
                            <i data-feather="file-text" class="w-3.5 h-3.5"></i> Print Surat Pernyataan
                        </a>
                    </div>
                </div>

                <!-- Tab Panel 2: Pelunasan -->
                <div class="tab-panel" id="pelunasanPanel" style="display: none;">
                    @if($trans->trans_status == 'Lunas')
                        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-center font-medium">
                            Transaksi ini sudah <strong>LUNAS</strong>.
                        </div>
                    @else
                        <form action="{{ route('kasir.pelunasan') }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <input type="hidden" name="kode" value="{{ $trans->trans_kode }}">
                            
                            <!-- Total Row -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 text-sm">
                                <span class="text-gray-500">Total</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($netTotal, 0, ',', '.') }},-</span>
                            </div>

                            <!-- Discount Row -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 text-sm">
                                <span class="text-gray-500">Discount</span>
                                <span class="font-semibold text-red-500">Rp {{ number_format($discount, 0, ',', '.') }},-</span>
                            </div>

                            <!-- Down Payment Row -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 text-sm">
                                <span class="text-gray-500">Down Payment</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($totalDP, 0, ',', '.') }},-</span>
                            </div>

                            <!-- Pelunasan Row -->
                            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                <span class="text-sm font-bold text-gray-700">Pelunasan</span>
                                <span class="text-lg font-bold text-blue-900">Rp {{ number_format($sisaPelunasan, 0, ',', '.') }},-</span>
                                <input type="hidden" name="lunas" value="{{ $sisaPelunasan }}">
                            </div>

                            <!-- Jenis Pembayaran -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Jenis Pembayaran</span>
                                <select name="jenis_bayar" class="form-input text-xs border border-gray-300 rounded bg-white px-2 py-1 w-28 focus:outline-none focus:ring-1 focus:ring-blue-500 font-semibold" required>
                                    <option value="TUNAI">Tunai</option>
                                    <option value="DEBIT">Debit</option>
                                    <option value="TRANSFER">Transfer</option>
                                </select>
                            </div>

                            <!-- Bank selection (Conditional: Debit / Transfer) -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 bank-select-div" style="display: none;">
                                <span class="text-sm font-semibold text-gray-600 bank-label">Pilih Bank</span>
                                <select name="bank" class="form-input text-xs border border-gray-300 rounded bg-white px-2 py-1 w-28 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="-">Pilih Bank</option>
                                    <optgroup label="Bank">
                                        <option value="BCA">BCA</option>
                                        <option value="MANDIRI">Mandiri</option>
                                        <option value="BRI">BRI</option>
                                        <option value="BNI">BNI</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </optgroup>
                                    <optgroup label="E-Wallet">
                                        <option value="QRIS">QRIS</option>
                                        <option value="GOPAY">GoPay</option>
                                        <option value="OVO">OVO</option>
                                        <option value="DANA">DANA</option>
                                        <option value="SHOPEEPAY">ShopeePay</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <button type="submit" 
                                    class="button bg-blue-600 hover:bg-blue-700 text-white w-full py-2.5 rounded font-semibold text-center mt-4 text-xs shadow-sm transition-colors duration-200" 
                                    onclick="return confirm('Yakin ingin melunasi transaksi ini?');">
                                Bayar Pelunasan
                            </button>
                            <a href="{{ route('admin.cetak.print_4', $trans->trans_kode) }}" 
                               target="_blank" 
                               class="button bg-blue-400 hover:bg-blue-500 text-white w-full py-2.5 rounded font-semibold text-center text-xs shadow-sm transition-colors duration-200 flex items-center justify-center gap-1.5">
                                <i data-feather="printer" class="w-3.5 h-3.5"></i> Print Thermal
                            </a>
                        </form>
                    @endif
                </div>

                <!-- Tab Panel 3: DP -->
                <div class="tab-panel" id="dpPanel" style="display: none;">
                    @if($trans->trans_status == 'Lunas')
                        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-md text-center font-medium">
                            Transaksi ini sudah <strong>LUNAS</strong>.
                        </div>
                    @else
                        <form action="{{ route('kasir.save_dp') }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <input type="hidden" name="kode" value="{{ $trans->trans_kode }}">
                            
                            <!-- Total Row -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 text-sm">
                                <span class="text-gray-500">Total</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($netTotal, 0, ',', '.') }},-</span>
                            </div>

                            <!-- Discount Row -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 text-sm">
                                <span class="text-gray-500">Discount</span>
                                <span class="font-semibold text-red-500">Rp {{ number_format($discount, 0, ',', '.') }},-</span>
                            </div>

                            <!-- Down Payment Row -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 text-sm">
                                <span class="text-gray-500">Current DP Paid</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($totalDP, 0, ',', '.') }},-</span>
                            </div>

                            <!-- Input Jumlah DP -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Jumlah DP (Rp)</span>
                                <input type="number" 
                                       name="dp" 
                                       class="form-input text-xs border border-gray-300 rounded bg-white px-2 py-1 w-32 focus:outline-none focus:ring-1 focus:ring-blue-500 font-semibold text-right" 
                                       required 
                                       placeholder="Jumlah DP">
                            </div>

                            <!-- Jenis Pembayaran -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-600">Jenis Pembayaran</span>
                                <select name="jenis_bayar" class="form-input text-xs border border-gray-300 rounded bg-white px-2 py-1 w-28 focus:outline-none focus:ring-1 focus:ring-blue-500 font-semibold" required>
                                    <option value="TUNAI">Tunai</option>
                                    <option value="DEBIT">Debit</option>
                                    <option value="TRANSFER">Transfer</option>
                                </select>
                            </div>

                            <!-- Bank selection (Conditional: Debit / Transfer) -->
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 bank-select-div" style="display: none;">
                                <span class="text-sm font-semibold text-gray-600 bank-label">Pilih Bank</span>
                                <select name="bank" class="form-input text-xs border border-gray-300 rounded bg-white px-2 py-1 w-28 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="-">Pilih Bank</option>
                                    <optgroup label="Bank">
                                        <option value="BCA">BCA</option>
                                        <option value="MANDIRI">Mandiri</option>
                                        <option value="BRI">BRI</option>
                                        <option value="BNI">BNI</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </optgroup>
                                    <optgroup label="E-Wallet">
                                        <option value="QRIS">QRIS</option>
                                        <option value="GOPAY">GoPay</option>
                                        <option value="OVO">OVO</option>
                                        <option value="DANA">DANA</option>
                                        <option value="SHOPEEPAY">ShopeePay</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <button type="submit" 
                                    class="button bg-blue-600 hover:bg-blue-700 text-white w-full py-2.5 rounded font-semibold text-center mt-4 text-xs shadow-sm transition-colors duration-200">
                                Simpan DP
                            </button>
                            <a href="{{ route('admin.cetak.print_5', $trans->trans_kode) }}" 
                               target="_blank" 
                               class="button bg-blue-400 hover:bg-blue-500 text-white w-full py-2.5 rounded font-semibold text-center text-xs shadow-sm transition-colors duration-200 flex items-center justify-center gap-1.5">
                                <i data-feather="printer" class="w-3.5 h-3.5"></i> Print Thermal
                            </a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Tab Switching Functionality
    $('.tab-trigger').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        
        // Update active class on tab buttons
        $('.tab-trigger').removeClass('active bg-indigo-900 text-white').addClass('text-gray-600 hover:bg-gray-200');
        $(this).addClass('active bg-indigo-900 text-white').removeClass('text-gray-600 hover:bg-gray-200');
        
        // Switch visibility of panel contents
        $('.tab-panel').hide();
        $(target).fadeIn(200);
    });

    // 2. Conditional Bank Dropdown Visibility
    $('select[name="jenis_bayar"]').on('change', function() {
        var form = $(this).closest('form');
        var val = $(this).val();
        var label = form.find('.bank-label');
        var bankSelect = form.find('select[name="bank"]');
        var ewalletGroup = bankSelect.find('optgroup[label="E-Wallet"]');
        
        if (val === 'TUNAI') {
            form.find('.bank-select-div').hide();
            bankSelect.val('-');
        } else {
            form.find('.bank-select-div').show();
            if (val === 'DEBIT') {
                label.text('Pilih Bank');
                ewalletGroup.attr('disabled', 'disabled').hide();
                // Reset value if an E-Wallet option was selected previously
                var selectedVal = bankSelect.val();
                if (['QRIS', 'GOPAY', 'OVO', 'DANA', 'SHOPEEPAY'].includes(selectedVal)) {
                    bankSelect.val('-');
                }
            } else {
                // TRANSFER
                label.text('Pilih Bank / E-Wallet');
                ewalletGroup.removeAttr('disabled').show();
            }
        }
    });

    // Trigger change on load to set initial bank dropdown state
    $('select[name="jenis_bayar"]').trigger('change');

    // 3. Initialize jQuery DataTable on #historiTable
    if (typeof $.fn.DataTable !== 'undefined') {
        if (!$.fn.DataTable.isDataTable('#historiTable')) {
            $('#historiTable').DataTable({
                responsive: true,
                autoWidth: false,
                paging: true,
                info: true,
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                }
            });
        }
    }
});
</script>
@endsection
