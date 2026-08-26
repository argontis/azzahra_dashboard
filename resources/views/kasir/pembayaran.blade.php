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
    border-radius: 20px; /* Capsule pill shape like the screenshot */
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease-in-out;
    background: #ffffff;
    border: 1.5px solid #d1d5db;
    color: #4b5563;
    cursor: pointer;
}

/* Inactive number link hover */
.pagination .page-item a.pagination-link:hover {
    color: #0041c3;
    border-color: #0041c3;
    background-color: #eff6ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(0, 65, 195, 0.1);
}

/* Active Page Pill */
.pagination .page-item.active .page-link,
.pagination .page-item.active span {
    color: #ffffff !important;
    background: #0041c3 !important;
    border-color: #0041c3 !important;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 65, 195, 0.3);
}

/* Navigation buttons: First, Previous, Next, Last */
.pagination .page-item a.pagination-btn,
.pagination .page-item span.pagination-btn {
    background: #0041c3 !important;
    color: #ffffff !important;
    border-color: #0041c3 !important;
    box-shadow: 0 4px 10px rgba(0, 65, 195, 0.2);
}

.pagination .page-item a.pagination-btn:hover {
    background: #003399 !important;
    border-color: #003399 !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0, 65, 195, 0.35);
}

/* Disabled styling */
.pagination .page-item.disabled span {
    opacity: 0.6;
    background: #f3f4f6;
    color: #9ca3af;
    border-color: #e5e7eb;
    cursor: not-allowed;
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
    transform: translateX(4px);
}

.table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}
</style>

<!-- Header Area matching the screenshot -->
<div class="page-header">
    <div class="flex items-center gap-2 text-white">
        <i data-feather="credit-card" class="w-5 h-5"></i>
        <h1 class="text-xl font-bold tracking-wide">Pembayaran</h1>
        <span class="hidden md:inline-block text-white text-xs opacity-90 ml-4 border-l border-white/20 pl-4">Manage payment data</span>
    </div>
    
    <div class="flex items-center gap-4">
        <!-- Search Form -->
        <form action="{{ route('kasir.pembayaran') }}" method="GET" class="hidden sm:block m-0">
            <div class="relative">
                <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." class="pl-9 pr-4 py-2 rounded-md border-0 focus:ring-2 focus:ring-blue-400 text-gray-700 text-sm w-48 lg:w-80 shadow-sm">
            </div>
        </form>
        <!-- Bell Icon -->
        <a href="#" class="text-white hover:text-gray-200 transition-colors relative flex items-center justify-center" title="Notifikasi">
            <i data-feather="bell" class="w-5 h-5"></i>
            <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </a>
        <!-- Mail Icon -->
        <a href="#" class="text-white hover:text-gray-200 transition-colors flex items-center justify-center" title="Pesan">
            <i data-feather="mail" class="w-5 h-5"></i>
        </a>
    </div>
</div>

<div class="content-area">
    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <!-- Title and Dropdown Filter -->
        <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
            <h2 class="text-xl font-bold text-gray-700">Data Pembayaran</h2>
            <div>
                <a href="{{ route('kasir.laporan') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white bg-indigo-900 hover:bg-indigo-950 rounded-md shadow-sm transition-colors duration-200">
                    Pembayaran hari ini <i data-feather="chevron-down" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>

        <!-- Three Statistics Card Badges -->
        <div class="grid grid-cols-12 gap-5 mb-6">
            <!-- Down Payment Card -->
            <div class="col-span-12 sm:col-span-4 stat-card rounded-lg p-5 text-white shadow-sm flex flex-col justify-between cursor-pointer" onclick="window.location='{{ route('kasir.pembayaran', 'dp') }}'">
                <div class="font-bold text-sm">Down Payment (DP)</div>
                <div class="text-xs opacity-90 mt-1 font-medium">{{ $dpCount }} Customer</div>
            </div>
            <!-- Lunas Card -->
            <div class="col-span-12 sm:col-span-4 stat-card rounded-lg p-5 text-white shadow-sm flex flex-col justify-between cursor-pointer" onclick="window.location='{{ route('kasir.pembayaran', 'lunas') }}'">
                <div class="font-bold text-sm">Lunas</div>
                <div class="text-xs opacity-90 mt-1 font-medium">{{ $lunasCount }} Customer</div>
            </div>
            <!-- Total Card -->
            <div class="col-span-12 sm:col-span-4 stat-card rounded-lg p-5 text-white shadow-sm flex flex-col justify-between cursor-pointer" onclick="window.location='{{ route('kasir.pembayaran') }}'">
                <div class="font-bold text-sm">Jumlah Customer</div>
                <div class="text-xs opacity-90 mt-1 font-medium">{{ $totalCount }} Customer</div>
            </div>
        </div>

        <div class="border-b border-gray-200 my-6"></div>

        <!-- Section Title inside card -->
        <h2 class="text-lg font-bold text-gray-700 pb-3">Data Customer</h2>

        <!-- Search Input inside card -->
        <div class="mb-4">
            <form action="{{ route('kasir.pembayaran') }}" method="GET" class="w-full">
                <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search customers..." class="form-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </form>
        </div>

        <!-- Pagination on Top (styled as pills) -->
        <div class="flex justify-center mb-6 mt-2">
            {{ $pembayarans->links() }}
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="table table-report table-report--bordered w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 text-center whitespace-no-wrap w-12">NO</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                        <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                        <th class="border-b-2 whitespace-no-wrap">MODEL UNIT</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                        <th class="border-b-2 whitespace-no-wrap">TANGGAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans as $index => $row)
                        <tr class="intro-x cursor-pointer hover:bg-gray-100" onclick="window.location='{{ route('kasir.cari', $row->trans_kode) }}'">
                            <!-- NO -->
                            <td class="text-center border-b font-medium">
                                {{ $pembayarans instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($pembayarans->firstItem() + $index) : ($index + 1) }}
                            </td>
                            <!-- INVOICE -->
                            <td class="text-center border-b font-medium text-gray-700">
                                {{ $row->trans_kode }}
                            </td>
                            <!-- NAMA CUSTOMER -->
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap text-blue-800">
                                    {{ $row->transaksi->customer->cos_nama ?? '-' }}
                                </div>
                                <div class="text-xs text-gray-500 whitespace-no-wrap mt-0.5">
                                    STATUS : <span class="font-semibold text-theme-1">{{ $row->transaksi->trans_status ?? '-' }}</span>
                                </div>
                            </td>
                            <!-- MODEL UNIT -->
                            <td class="border-b text-gray-700 font-medium">
                                {{ $row->transaksi->customer->cos_model ?? '-' }} @if(isset($row->transaksi->customer->cos_tipe)) - {{ $row->transaksi->customer->cos_tipe }} @endif
                            </td>
                            <!-- STATUS (GARANSI) -->
                            <td class="text-center border-b font-semibold text-gray-600">
                                {{ $row->transaksi->customer->cos_status ?? '-' }}
                            </td>
                            <!-- TANGGAL -->
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap text-gray-700">
                                    {{ $row->dtl_tanggal }}
                                </div>
                                <div class="text-xs text-gray-400 whitespace-no-wrap mt-0.5">
                                    JAM : {{ $row->dtl_jam }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-5 text-gray-500">
                                Tidak ada data customer/transaksi aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination below the table if total is high -->
        @if($pembayarans instanceof \Illuminate\Pagination\LengthAwarePaginator && $pembayarans->total() > 25)
            <div class="mt-5 flex justify-center">
                {{ $pembayarans->links() }}
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic post-processing of pagination links to add custom styling classes
    function stylePagination() {
        const pageLinks = document.querySelectorAll('.pagination .page-link, .pagination .page-item span');
        pageLinks.forEach(link => {
            const text = link.textContent.trim();
            if (
                text === 'First' || text === 'Previous' || text === 'Next' || text === 'Last' || 
                text === '«' || text === '»' || text === '‹' || text === '›' || 
                text === 'Sebelumnya' || text === 'Berikutnya' ||
                link.querySelector('[aria-hidden="true"]') ||
                link.closest('[aria-label*="Next"]') || link.closest('[aria-label*="Prev"]') ||
                link.closest('[aria-label*="next"]') || link.closest('[aria-label*="prev"]')
            ) {
                link.classList.add('pagination-btn');
            } else {
                link.classList.add('pagination-link');
            }
        });
    }

    stylePagination();

    const observer = new MutationObserver(stylePagination);
    const paginationContainer = document.querySelector('.pagination');
    if (paginationContainer) {
        observer.observe(paginationContainer, { childList: true, subtree: true });
    }
});
</script>
@endsection
