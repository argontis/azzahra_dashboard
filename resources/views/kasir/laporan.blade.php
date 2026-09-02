@extends('layouts.app')

@section('content')
@php
    // Helper function to get count and subtotal of payments by status and method/bank
    $getSummary = function($status, $method, $bank = null) use ($payments) {
        $filtered = $payments->where('dtl_status', $status);
        if ($method === 'TUNAI') {
            $filtered = $filtered->where('dtl_jenis_bayar', 'TUNAI');
        } else {
            $filtered = $filtered->where('dtl_jenis_bayar', '!=', 'TUNAI');
            if ($bank) {
                $filtered = $filtered->where('dtl_bank', $bank);
            }
        }
        return [
            'count' => $filtered->count(),
            'sum' => $filtered->sum('dtl_jml_bayar')
        ];
    };

    // DP Summaries
    $dpBca = $getSummary('DP', 'TRANSFER', 'BCA');
    $dpBri = $getSummary('DP', 'TRANSFER', 'BRI');
    $dpMandiri = $getSummary('DP', 'TRANSFER', 'MANDIRI');
    $dpTunai = $getSummary('DP', 'TUNAI');

    // Pelunasan Summaries
    $lunasBca = $getSummary('PELUNASAN', 'TRANSFER', 'BCA');
    $lunasBri = $getSummary('PELUNASAN', 'TRANSFER', 'BRI');
    $lunasMandiri = $getSummary('PELUNASAN', 'TRANSFER', 'MANDIRI');
    $lunasTunai = $getSummary('PELUNASAN', 'TUNAI');

    // Grouping for table listings
    $dp_payments = $payments->where('dtl_status', 'DP');
    $lunas_payments = $payments->where('dtl_status', 'PELUNASAN');
    $menunggu_payments = $payments->where('dtl_stt_stor', 'Menunggu');
@endphp

<style>
/* ===== REPORT SHEET DESIGN ===== */
.report-sheet {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}

.report-table th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.75rem 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.report-table td {
    padding: 0.85rem 1rem;
    font-size: 0.825rem;
    border-bottom: 1px solid #f1f5f9;
}

.detail-table th {
    background: #f1f5f9;
    color: #475569;
    font-weight: 600;
    font-size: 0.8rem;
    padding: 0.75rem 1rem;
    border-bottom: 1.5px solid #cbd5e1;
}

.detail-table td {
    padding: 0.75rem 1rem;
    font-size: 0.8rem;
    border-bottom: 1px solid #e2e8f0;
}
</style>

<!-- Header Area -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="bar-chart-2" class="w-6 h-6 inline-block mr-2"></i>Laporan</h1>
        <p>Daily Report</p>
    </div>
    <div class="header-actions">
        <!-- Search Form -->
        <form class="hidden sm:block" style="margin: 0;">
            <div class="search-input-wrapper">
                <i data-feather="search" class="search-icon"></i>
                <input type="text" placeholder="Search..." class="search-input">
            </div>
        </form>
        <!-- Bell Icon -->
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <!-- Mail Icon -->
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">
    <div class="intro-y box p-5 mt-5 bg-white shadow-sm rounded-lg border border-gray-100">
        <!-- Section Header inside card -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
            <h2 class="text-lg font-bold text-gray-800">Laporan Hari Ini</h2>
            <div>
                <button onclick="window.print();" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-indigo-900 hover:bg-indigo-950 rounded shadow transition-colors duration-200">
                    <i data-feather="printer" class="w-3.5 h-3.5"></i> Print
                </button>
            </div>
        </div>

        <!-- The Printable Report Sheet -->
        <div class="report-sheet p-6 sm:p-10 mb-10 max-w-4xl mx-auto">
            <!-- Sheet Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold tracking-wider text-indigo-900">LAPORAN</h1>
                </div>
                <div class="text-right text-xs sm:text-sm text-gray-600 leading-relaxed">
                    <div class="font-bold text-gray-800">{{ auth()->user()->karyawan->kry_nama ?? auth()->user()->username ?? 'Juanda' }}</div>
                    <div>Tegal, {{ \Carbon\Carbon::parse($date)->format('d-F-Y') }}</div>
                </div>
            </div>

            <div class="border-b border-gray-200 mb-6"></div>

            <!-- Breakdown Table -->
            <div class="overflow-x-auto">
                <table class="report-table w-full text-left">
                    <thead>
                        <tr>
                            <th class="w-3/5">Description</th>
                            <th class="text-center w-1/5">Jumlah</th>
                            <th class="text-right w-1/5">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DOWN PAYMENT BANK BCA -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">DOWN PAYMENT BANK BCA</div>
                                <div class="text-[10px] text-gray-400">NO Rek. 0470727705</div>
                            </td>
                            <td class="text-center font-medium">{{ $dpBca['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($dpBca['sum'], 0, ',', '.') }},-</td>
                        </tr>
                        <!-- DOWN PAYMENT BANK BRI -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">DOWN PAYMENT BANK BRI</div>
                                <div class="text-[10px] text-gray-400">NO Rek. 1390023150083</div>
                            </td>
                            <td class="text-center font-medium">{{ $dpBri['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($dpBri['sum'], 0, ',', '.') }},-</td>
                        </tr>
                        <!-- DOWN PAYMENT BANK MANDIRI -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">DOWN PAYMENT BANK MANDIRI</div>
                                <div class="text-[10px] text-gray-400">NO Rek. [Mandiri Account]</div>
                            </td>
                            <td class="text-center font-medium">{{ $dpMandiri['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($dpMandiri['sum'], 0, ',', '.') }},-</td>
                        </tr>
                        <!-- DOWN PAYMENT TUNAI -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">DOWN PAYMENT TUNAI</div>
                            </td>
                            <td class="text-center font-medium">{{ $dpTunai['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($dpTunai['sum'], 0, ',', '.') }},-</td>
                        </tr>

                        <!-- PELUNASAN BANK BCA -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">PELUNASAN BANK BCA</div>
                                <div class="text-[10px] text-gray-400">NO Rek. 0470727705</div>
                            </td>
                            <td class="text-center font-medium">{{ $lunasBca['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($lunasBca['sum'], 0, ',', '.') }},-</td>
                        </tr>
                        <!-- PELUNASAN BANK BRI -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">PELUNASAN BANK BRI</div>
                                <div class="text-[10px] text-gray-400">NO Rek. 1390023150083</div>
                            </td>
                            <td class="text-center font-medium">{{ $lunasBri['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($lunasBri['sum'], 0, ',', '.') }},-</td>
                        </tr>
                        <!-- PELUNASAN BANK MANDIRI -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">PELUNASAN BANK MANDIRI</div>
                                <div class="text-[10px] text-gray-400">NO Rek. [Mandiri Account]</div>
                            </td>
                            <td class="text-center font-medium">{{ $lunasMandiri['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($lunasMandiri['sum'], 0, ',', '.') }},-</td>
                        </tr>
                        <!-- PELUNASAN TUNAI -->
                        <tr>
                            <td>
                                <div class="font-semibold text-gray-700">PELUNASAN TUNAI</div>
                            </td>
                            <td class="text-center font-medium">{{ $lunasTunai['count'] }}</td>
                            <td class="text-right font-semibold text-gray-800">Rp {{ number_format($lunasTunai['sum'], 0, ',', '.') }},-</td>
                        </tr>

                        <!-- TOTAL SUMMARY ROW -->
                        <tr class="bg-indigo-50/50 font-bold border-t-2 border-indigo-900">
                            <td class="py-4 font-bold text-indigo-900 text-sm">TOTAL PENDAPATAN</td>
                            <td class="text-center py-4 font-bold text-indigo-900 text-sm">
                                {{ $payments->count() }}
                            </td>
                            <td class="text-right py-4 font-bold text-indigo-900 text-sm">
                                Rp {{ number_format($total_all, 0, ',', '.') }},-
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="border-b border-gray-200 my-8"></div>

        <!-- 1. Detail Pembayaran DP Hari Ini -->
        <div class="mb-10">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Detail Pembayaran DP Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="detail-table w-full text-left">
                    <thead>
                        <tr>
                            <th class="w-24">TTS</th>
                            <th>Nama Customer</th>
                            <th>Domisili</th>
                            <th class="text-right">Jumlah Bayar</th>
                            <th>Jenis</th>
                            <th>Status Setor</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dp_payments as $payment)
                            <tr>
                                <td>{{ $payment->transaksi->customer->id_costomer ?? '-' }}</td>
                                <td class="font-semibold text-indigo-900">{{ $payment->transaksi->customer->cos_nama ?? '-' }}</td>
                                <td>{{ $payment->transaksi->customer->cos_alamat ?? '-' }}</td>
                                <td class="text-right font-bold text-emerald-600">Rp {{ number_format($payment->dtl_jml_bayar, 0, ',', '.') }},-</td>
                                <td>{{ $payment->dtl_jenis_bayar }} {{ $payment->dtl_bank != '-' ? '('.$payment->dtl_bank.')' : '' }}</td>
                                <td>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $payment->dtl_stt_stor == 'Disetorkan' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $payment->dtl_stt_stor }}
                                    </span>
                                </td>
                                <td class="text-gray-500 text-[11px]">{{ $payment->dtl_tanggal }} | {{ $payment->dtl_jam }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500 italic">Tidak ada pembayaran DP hari ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Detail Pembayaran Pelunasan Hari Ini -->
        <div class="mb-10">
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Detail Pembayaran Pelunasan Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="detail-table w-full text-left">
                    <thead>
                        <tr>
                            <th class="w-24">TTS</th>
                            <th>Nama Customer</th>
                            <th>Domisili</th>
                            <th class="text-right">Jumlah Bayar</th>
                            <th>Jenis</th>
                            <th>Status Setor</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lunas_payments as $payment)
                            <tr>
                                <td>{{ $payment->transaksi->customer->id_costomer ?? '-' }}</td>
                                <td class="font-semibold text-indigo-900">{{ $payment->transaksi->customer->cos_nama ?? '-' }}</td>
                                <td>{{ $payment->transaksi->customer->cos_alamat ?? '-' }}</td>
                                <td class="text-right font-bold text-emerald-600">Rp {{ number_format($payment->dtl_jml_bayar, 0, ',', '.') }},-</td>
                                <td>{{ $payment->dtl_jenis_bayar }} {{ $payment->dtl_bank != '-' ? '('.$payment->dtl_bank.')' : '' }}</td>
                                <td>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $payment->dtl_stt_stor == 'Disetorkan' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $payment->dtl_stt_stor }}
                                    </span>
                                </td>
                                <td class="text-gray-500 text-[11px]">{{ $payment->dtl_tanggal }} | {{ $payment->dtl_jam }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500 italic">Tidak ada pembayaran pelunasan hari ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. Detail Pembayaran Status Menunggu Hari Ini -->
        <div>
            <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wider mb-4">Detail Pembayaran Status Menunggu Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="detail-table w-full text-left">
                    <thead>
                        <tr>
                            <th class="w-24">TTS</th>
                            <th>Nama Customer</th>
                            <th class="text-right">Jumlah Bayar</th>
                            <th>Tipe</th>
                            <th>Jenis</th>
                            <th>Status Setor</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menunggu_payments as $payment)
                            <tr>
                                <td>{{ $payment->transaksi->customer->id_costomer ?? '-' }}</td>
                                <td class="font-semibold text-indigo-900">{{ $payment->transaksi->customer->cos_nama ?? '-' }}</td>
                                <td class="text-right font-bold text-emerald-600">Rp {{ number_format($payment->dtl_jml_bayar, 0, ',', '.') }},-</td>
                                <td class="font-semibold text-gray-600">{{ $payment->dtl_status }}</td>
                                <td>{{ $payment->dtl_jenis_bayar }} {{ $payment->dtl_bank != '-' ? '('.$payment->dtl_bank.')' : '' }}</td>
                                <td>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-yellow-100 text-yellow-800 animate-pulse">
                                        {{ $payment->dtl_stt_stor }}
                                    </span>
                                </td>
                                <td class="text-gray-500 text-[11px]">{{ $payment->dtl_tanggal }} | {{ $payment->dtl_jam }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500 italic">Tidak ada pembayaran status menunggu hari ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
