@extends('layouts.app')

@section('content')
@php
    $subtotal = $tindakans->sum('tdkn_subtot');
    if ($subtotal == 0 && $transaksi->trans_total > 0) {
        $subtotal = $transaksi->trans_total;
    }
    $discount = $transaksi->trans_discount ?? 0;
    $total = max(0, $subtotal - $discount);
@endphp

<style>
.invoice-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}
.meta-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px 14px;
}
.meta-label {
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 2px;
}
.meta-value {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    word-break: break-all;
}
.table-dark-head thead th {
    background-color: #2c3b4e !important;
    color: #ffffff !important;
    font-size: 12px;
    font-weight: 600;
    text-transform: capitalize;
    padding: 8px 12px;
    border: none;
}
.table-custom td {
    padding: 10px 12px;
    font-size: 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}
.section-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 8px;
    margin-bottom: 14px;
}
@media print {
    .no-print {
        display: none !important;
    }
    body {
        background: #ffffff !important;
    }
    .page-header, .sidebar, .mobile-menu-btn {
        display: none !important;
    }
    .content-area {
        margin: 0 !important;
        padding: 0 !important;
    }
    .invoice-card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        max-width: 100% !important;
    }
}
</style>

<!-- Header -->
<header class="page-header no-print">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Detail Pembayaran</h1>
        <p>Rincian Pembayaran Transaksi Customer</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('service.pembayaran') }}" class="header-btn" title="Kembali ke Daftar Pembayaran" style="text-decoration: none;">
            <i data-feather="arrow-left"></i>
        </a>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area py-6 px-4">
    <!-- Top Action Buttons -->
    <div class="max-w-4xl mx-auto mb-4 flex items-center justify-between no-print">
        <a href="{{ route('service.pembayaran') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm transition">
                <i data-feather="printer" class="w-4 h-4"></i> Print Lembar Ini
            </button>
            <a href="{{ route('admin.cetak.print_1', $transaksi->trans_kode) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white bg-indigo-900 rounded-lg hover:bg-indigo-950 shadow-sm transition">
                <i data-feather="file-text" class="w-4 h-4"></i> Cetak Invoice
            </a>
        </div>
    </div>

    <!-- Main Receipt / Detail View (Exact match to Image 2) -->
    <div class="max-w-4xl mx-auto invoice-card p-6 sm:p-8">
        <!-- Customer Name Heading -->
        <h1 class="text-xl sm:text-2xl font-bold uppercase tracking-wide text-gray-900 mb-5">
            {{ $customer->cos_nama ?? 'CUSTOMER' }}
        </h1>

        <!-- Top Metadata 4-Box Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
            <div class="meta-box">
                <div class="meta-label">Kode Pelanggan</div>
                <div class="meta-value">{{ $customer->id_costomer ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">No. Telepon</div>
                <div class="meta-value">{{ $customer->cos_hp ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Status</div>
                <div class="meta-value">{{ $customer->cos_status ?? '-' }}</div>
            </div>
            <div class="meta-box">
                <div class="meta-label">Tipe Unit</div>
                <div class="meta-value">{{ $customer->cos_tipe ?? '-' }}</div>
            </div>
        </div>

        <!-- Alamat -->
        <div class="mb-6">
            <div class="meta-label mb-1">Alamat</div>
            <div class="w-full bg-white border border-gray-300 rounded-md p-3 text-xs sm:text-sm text-gray-800 min-h-[42px] leading-relaxed">
                {{ $customer->cos_alamat ?? '-' }}
            </div>
        </div>

        <!-- Detail Unit Section -->
        <div class="mb-6">
            <h2 class="section-title">Detail Unit</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="meta-box">
                    <div class="meta-label">Model</div>
                    <div class="meta-value">{{ $customer->cos_model ?? '-' }}</div>
                </div>
                <div class="meta-box">
                    <div class="meta-label">No. Seri</div>
                    <div class="meta-value">{{ $customer->cos_no_seri ?? '-' }}</div>
                </div>
                <div class="meta-box">
                    <div class="meta-label">Password</div>
                    <div class="meta-value">{{ $customer->cos_pswd ?? '-' }}</div>
                </div>
                <div class="meta-box">
                    <div class="meta-label">Aksesoris</div>
                    <div class="meta-value">{{ $customer->cos_asesoris ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Keluhan dan Keterangan Section -->
        <div class="mb-6">
            <h2 class="section-title">Keluhan dan Keterangan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="meta-label mb-1">Keluhan</div>
                    <div class="w-full bg-white border border-gray-300 rounded-md p-3 text-xs text-gray-800 min-h-[64px] uppercase leading-relaxed font-normal">
                        {{ $customer->cos_keluhan ?? '-' }}
                    </div>
                </div>
                <div>
                    <div class="meta-label mb-1">Keterangan</div>
                    <div class="w-full bg-white border border-gray-300 rounded-md p-3 text-xs text-gray-800 min-h-[64px] uppercase leading-relaxed font-normal">
                        {{ $customer->cos_keterangan ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Tindakan Teknisi Section -->
        <div class="mb-6">
            <h2 class="section-title">Tindakan Teknisi</h2>
            <div class="overflow-x-auto rounded-md border border-gray-200">
                <table class="w-full table-dark-head table-custom text-left">
                    <thead>
                        <tr>
                            <th class="w-10 text-center">#</th>
                            <th>Tindakan</th>
                            <th class="w-16 text-center">Qty</th>
                            <th class="w-36 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($tindakans as $index => $row)
                        <tr>
                            <td class="text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="font-medium text-gray-800 uppercase">{{ $row->tdkn_barang }}</td>
                            <td class="text-center text-gray-600 font-medium">{{ $row->tdkn_qty }}</td>
                            <td class="text-right text-gray-800 font-semibold whitespace-nowrap">Rp. {{ number_format($row->tdkn_subtot, 0, ',', '.') }},-</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-400">Belum ada tindakan teknisi yang tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="border-t border-gray-200 text-xs sm:text-sm">
                        <tr class="bg-gray-50">
                            <td colspan="3" class="text-right font-medium text-gray-600 py-2.5 px-3">Subtotal</td>
                            <td class="text-right font-bold text-gray-800 py-2.5 px-3 whitespace-nowrap">Rp. {{ number_format($subtotal, 0, ',', '.') }},-</td>
                        </tr>
                        <tr style="background-color: #fef2f2; color: #dc2626;">
                            <td colspan="3" class="text-right font-medium py-2.5 px-3">Discount</td>
                            <td class="text-right font-bold py-2.5 px-3 whitespace-nowrap">- Rp. {{ number_format($discount, 0, ',', '.') }},-</td>
                        </tr>
                        <tr style="background-color: #f0fdf4; color: #16a34a;">
                            <td colspan="3" class="text-right font-bold py-3 px-3 text-sm">Total</td>
                            <td class="text-right font-bold py-3 px-3 text-sm sm:text-base whitespace-nowrap">Rp. {{ number_format($total, 0, ',', '.') }},-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Histori Pembayaran Section -->
        <div class="mb-4">
            <h2 class="section-title">Histori Pembayaran</h2>
            <div class="overflow-x-auto rounded-md border border-gray-200">
                <table class="w-full table-dark-head table-custom text-left">
                    <thead>
                        <tr>
                            <th class="w-10 text-center">#</th>
                            <th class="w-36 text-center">Total Bayar</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Tanggal &amp; Jam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pembayarans as $index => $row)
                        <tr class="text-center">
                            <td class="text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="font-bold text-gray-800 whitespace-nowrap">Rp. {{ number_format($row->dtl_jml_bayar, 0, ',', '.') }},-</td>
                            <td class="font-semibold text-gray-700 uppercase">{{ $row->dtl_jenis_bayar }} {{ $row->dtl_bank != '-' ? '('.$row->dtl_bank.')' : '' }}</td>
                            <td class="font-bold {{ $row->dtl_status == 'PELUNASAN' ? 'text-green-600' : 'text-blue-600' }}">{{ $row->dtl_status }}</td>
                            <td class="text-gray-600 text-xs">
                                {{ date('d-m-Y', strtotime($row->dtl_tanggal)) }} 
                                <span class="text-gray-500">Jam: {{ $row->dtl_jam }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-400">Belum ada histori pembayaran untuk transaksi ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection
