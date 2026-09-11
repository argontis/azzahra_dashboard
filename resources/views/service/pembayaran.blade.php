@extends('layouts.app')

@section('content')
<style>
/* ===== CUSTOM PAGINATION BAR (Pills Style) ===== */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.375rem;
    list-style: none;
    padding: 0;
    margin: 1.5rem 0;
    flex-wrap: wrap;
}

.pagination .page-item {
    display: inline-block;
}

.pagination .page-link,
.pagination .page-item span {
    min-width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    background: #ffffff;
    border: 1.5px solid #d1d5db;
    color: #4b5563;
    cursor: pointer;
}

.pagination .page-item a.pagination-link:hover {
    color: #0041c3;
    border-color: #0041c3;
    background-color: #eff6ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 65, 195, 0.1);
}

.pagination .page-item.active .page-link,
.pagination .page-item.active span {
    color: #ffffff !important;
    background: #0041c3 !important;
    border-color: #0041c3 !important;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 65, 195, 0.3);
}

/* ===== STATISTICS CARDS ===== */
.stat-card {
    background: #0052e6;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}
.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 82, 230, 0.2);
}

/* ===== MODERN TABLE DESIGN ===== */
.intro-y.datatable-wrapper {
    background: white;
    border-radius: 16px;
    box-shadow:
        0 10px 25px rgba(0, 0, 0, 0.05),
        0 4px 10px rgba(0, 0, 0, 0.02);
    border: 1px solid #f3f4f6;
    overflow: hidden;
    position: relative;
}

.intro-y.datatable-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #0041c3 0%, #0052e6 50%, #0063f0 100%);
}

.table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1.25rem 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f5f9;
}

.table tbody tr:hover {
    background: #f8fafc;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}
</style>

<!-- Header Area -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Pembayaran</h1>
        <p>Kelola Data Pembayaran Customer</p>
    </div>
    <div class="header-actions">
        <!-- Topbar Search -->
        <form action="{{ route('service.pembayaran') }}" method="GET" style="margin: 0;">
            <div class="search-input-wrapper">
                <i data-feather="search" class="search-icon"></i>
                <input type="text" id="topbar-search-input" name="search" value="{{ request('search') }}" placeholder="Search..." class="search-input">
            </div>
        </form>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">
    @if(session('sukses'))
        <div class="alert alert-success d-flex align-items-center mb-5 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800" role="alert">
            <i data-feather="check-circle" class="mr-2 text-green-600"></i>
            <span class="font-medium">{{ session('sukses') }}</span>
        </div>
    @endif

    @if(session('gagal'))
        <div class="alert alert-danger d-flex align-items-center mb-5 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800" role="alert">
            <i data-feather="alert-circle" class="mr-2 text-red-600"></i>
            <span class="font-medium">{{ session('gagal') }}</span>
        </div>
    @endif

    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <!-- Title and Dropdown Filter -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
            <div>
                <h2 class="text-xl font-bold text-gray-700">Daftar Pembayaran</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola data transaksi dan nota pembayaran customer</p>
            </div>
            @if(request('search') || $filter)
                <a href="{{ route('service.pembayaran') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                    <i data-feather="rotate-ccw" class="w-3.5 h-3.5"></i> Reset Filter
                </a>
            @endif
        </div>

        <!-- Three Statistics Card Badges -->
        <div class="grid grid-cols-12 gap-5 mb-6">
            <!-- Down Payment / Pelunasan Card -->
            <div class="col-span-12 sm:col-span-4 stat-card rounded-lg p-5 text-white shadow-sm flex flex-col justify-between cursor-pointer" onclick="window.location='{{ route('service.pembayaran', 'pelunasan') }}'">
                <div class="font-bold text-sm">Pelunasan / DP</div>
                <div class="text-xs opacity-90 mt-1 font-medium">{{ $countPelunasan }} Customer</div>
            </div>
            <!-- Lunas Card -->
            <div class="col-span-12 sm:col-span-4 stat-card rounded-lg p-5 text-white shadow-sm flex flex-col justify-between cursor-pointer" onclick="window.location='{{ route('service.pembayaran', 'lunas') }}'">
                <div class="font-bold text-sm">Lunas</div>
                <div class="text-xs opacity-90 mt-1 font-medium">{{ $countLunas }} Customer</div>
            </div>
            <!-- Total Card -->
            <div class="col-span-12 sm:col-span-4 stat-card rounded-lg p-5 text-white shadow-sm flex flex-col justify-between cursor-pointer" onclick="window.location='{{ route('service.pembayaran') }}'">
                <div class="font-bold text-sm">Jumlah Customer</div>
                <div class="text-xs opacity-90 mt-1 font-medium">{{ $countAll }} Customer</div>
            </div>
        </div>

        <div class="border-b border-gray-200 my-6"></div>

        <!-- Section Title inside card -->
        <h2 class="text-lg font-bold text-gray-700 pb-3">Data Transaksi &amp; Pembayaran</h2>

        <!-- Search Input inside card -->
        <div class="mb-4">
            <form action="{{ route('service.pembayaran', $filter) }}" method="GET" class="w-full">
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari berdasarkan Invoice, Nama Customer, No. HP, Alamat..." class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table table-report table-report--bordered w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 text-center whitespace-no-wrap w-12">NO</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                        <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                        <th class="border-b-2 text-right whitespace-no-wrap">TOTAL BAYAR</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $index => $row)
                        @php
                            $subtot = $row->tindakan->sum('tdkn_subtot');
                            if ($subtot == 0 && $row->trans_total > 0) {
                                $subtot = $row->trans_total;
                            }
                            $disc = $row->trans_discount ?? 0;
                            $finalTot = max(0, $subtot - $disc);

                            $statusBadge = 'bg-gray-100 text-gray-700';
                            if ($row->trans_status == 'Lunas') {
                                $statusBadge = 'bg-green-100 text-green-800 border border-green-200';
                            } elseif ($row->trans_status == 'Pelunasan') {
                                $statusBadge = 'bg-blue-100 text-blue-800 border border-blue-200';
                            } elseif ($row->trans_status == 'Diproses') {
                                $statusBadge = 'bg-amber-100 text-amber-800 border border-amber-200';
                            } elseif ($row->trans_status == 'Baru') {
                                $statusBadge = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
                            } elseif (in_array($row->trans_status, ['Cencel', 'Return'])) {
                                $statusBadge = 'bg-red-100 text-red-800 border border-red-200';
                            }

                            $waNumber = $row->customer->cos_hp ?? '';
                            $waNumber = preg_replace('/[^0-9]/', '', $waNumber);
                            if (str_starts_with($waNumber, '0')) {
                                $waNumber = '62' . substr($waNumber, 1);
                            }
                        @endphp
                        <tr class="intro-x cursor-pointer hover:bg-blue-50/50" onclick="if(!event.target.closest('a, button')) window.location='{{ route('service.pembayaran.detail', $row->trans_kode) }}'">
                            <!-- NO -->
                            <td class="text-center border-b font-medium text-gray-600">
                                {{ $transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($transaksis->firstItem() + $index) : ($index + 1) }}
                            </td>
                            <!-- INVOICE -->
                            <td class="text-center border-b font-semibold text-blue-700 whitespace-nowrap">
                                <a href="{{ route('service.pembayaran.detail', $row->trans_kode) }}" class="hover:underline">
                                    {{ $row->trans_kode }}
                                </a>
                                <div class="text-[11px] text-gray-400 font-normal">
                                    {{ $row->cos_tanggal ? date('d-m-Y', strtotime($row->cos_tanggal)) : ($row->created_at ? $row->created_at->format('d-m-Y') : '-') }}
                                </div>
                            </td>
                            <!-- NAMA CUSTOMER -->
                            <td class="border-b">
                                <div class="font-bold text-gray-800 whitespace-nowrap">
                                    {{ $row->customer->cos_nama ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 whitespace-nowrap mt-0.5">
                                    <span class="font-mono text-gray-600">{{ $row->cos_kode }}</span>
                                    @if(!empty($row->customer->cos_hp))
                                        <span class="mx-1 text-gray-300">|</span>
                                        <span>{{ $row->customer->cos_hp }}</span>
                                    @endif
                                    @if(!empty($row->customer->cos_model))
                                        <span class="mx-1 text-gray-300">|</span>
                                        <span class="text-gray-600">{{ $row->customer->cos_model }}</span>
                                    @endif
                                </div>
                            </td>
                            <!-- TOTAL BAYAR -->
                            <td class="border-b text-right whitespace-nowrap font-bold text-gray-800">
                                Rp {{ number_format($finalTot, 0, ',', '.') }},-
                            </td>
                            <!-- STATUS -->
                            <td class="text-center border-b whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusBadge }}">
                                    {{ $row->trans_status }}
                                </span>
                            </td>
                            <!-- AKSI -->
                            <td class="text-center border-b whitespace-nowrap" onclick="event.stopPropagation()">
                                <div class="inline-flex items-center gap-2">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('service.pembayaran.detail', $row->trans_kode) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-all duration-200 hover:shadow-md"
                                       title="Lihat Detail Nota">
                                        <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                        <span>Detail</span>
                                    </a>

                                    <!-- Tombol Cek Nota -->
                                    <a href="{{ url('/Service/cetak/print_1/' . $row->trans_kode) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-white bg-indigo-900 hover:bg-indigo-950 rounded-lg shadow-sm transition-all duration-200 hover:shadow-md"
                                       title="Cek Nota Pembayaran">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"></path><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                        <span>Cek Nota</span>
                                    </a>

                                    <!-- Tombol WA -->
                                    @if(!empty($waNumber))
                                    <a href="https://api.whatsapp.com/send?phone={{ $waNumber }}&text=Halo%20{{ urlencode($row->customer->cos_nama ?? '') }},%20kami%20dari%20Azzahra%20Computer%20menginformasikan%20terkait%20status%20transaksi%20dengan%20nomor%20invoice%20{{ $row->trans_kode }}." 
                                       target="_blank"
                                       class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:text-white bg-green-50 hover:bg-green-600 rounded-lg transition-all duration-200 hover:shadow-md"
                                       title="Kirim WhatsApp">
                                        <i data-feather="message-circle" class="w-4 h-4"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i data-feather="inbox" class="w-10 h-10 mx-auto mb-2 opacity-40"></i>
                                <p class="font-medium">Belum ada data pembayaran untuk saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="noSearchResultRow" style="display:none;">
                        <td colspan="6" class="text-center py-8 text-gray-400">
                            <i data-feather="search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                            <p>Tidak ada data pembayaran yang cocok dengan pencarian.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator && $transaksis->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $transaksis->links() }}
            </div>
        @endif
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