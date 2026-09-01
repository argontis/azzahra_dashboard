
@extends('layouts.app')
@section('content')
<style>
/* ===== MODERN TABLE DESIGN & OVERFLOW FIX ===== */
.intro-y.datatable-wrapper {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 
        0 10px 25px rgba(0, 0, 0, 0.05),
        0 4px 10px rgba(0, 0, 0, 0.03);
    border: 1px solid #e2e8f0;
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
    z-index: 10;
}

.table-responsive-container {
    width: 100% !important;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch;
    display: block !important;
    margin-top: 0 !important;
    padding-bottom: 8px !important;
}

/* Custom Horizontal Scrollbar Styling */
.table-responsive-container::-webkit-scrollbar {
    height: 8px !important;
}
.table-responsive-container::-webkit-scrollbar-track {
    background: #f1f5f9 !important;
    border-radius: 4px !important;
}
.table-responsive-container::-webkit-scrollbar-thumb {
    background: #0041c3 !important;
    border-radius: 4px !important;
}
.table-responsive-container::-webkit-scrollbar-thumb:hover {
    background: #002b80 !important;
}

/* ===== TABLE HEADER ===== */
.table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
    color: #334155 !important;
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    padding: 0.75rem 0.75rem !important;
    border-bottom: 2px solid #e2e8f0 !important;
    white-space: nowrap !important;
}

/* ===== TABLE BODY ===== */
.table tbody tr {
    transition: background-color 0.2s ease !important;
    border-bottom: 1px solid #f1f5f9 !important;
}

.table tbody tr:hover {
    background-color: #f8fafc !important;
}

.table tbody td {
    padding: 0.75rem 0.75rem !important;
    color: #475569 !important;
    font-size: 0.85rem !important;
    vertical-align: middle !important;
}

/* ===== TEXT & BADGE UTILITIES ===== */
.txt-slate-800 { color: #1e293b !important; }
.txt-slate-700 { color: #334155 !important; }
.txt-slate-600 { color: #475569 !important; }
.txt-slate-500 { color: #64748b !important; }
.txt-slate-400 { color: #94a3b8 !important; }
.txt-indigo { color: #0041c3 !important; font-weight: 600 !important; }

.bg-badge-invoice {
    background-color: #f1f5f9 !important;
    color: #334155 !important;
    border: 1px solid #cbd5e1 !important;
    padding: 3px 8px !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
    font-size: 11px !important;
    display: inline-block !important;
}

/* ===== ACTION BUTTONS EXPLICIT STYLES ===== */
.btn-act {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 6px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    color: #ffffff !important;
    border: none !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12) !important;
    cursor: pointer !important;
    line-height: 1 !important;
}

.btn-act-proses {
    background-color: #10b981 !important;
    color: #ffffff !important;
    padding: 6px 12px !important;
    gap: 4px !important;
}
.btn-act-proses:hover {
    background-color: #059669 !important;
    color: #ffffff !important;
}

.btn-act-print {
    background-color: #ef4444 !important;
    color: #ffffff !important;
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
}
.btn-act-print:hover {
    background-color: #dc2626 !important;
    color: #ffffff !important;
}

.btn-act-wa {
    background-color: #25d366 !important;
    color: #ffffff !important;
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
}
.btn-act-wa:hover {
    background-color: #128c7e !important;
    color: #ffffff !important;
}

.btn-act-hapus {
    background-color: #64748b !important;
    color: #ffffff !important;
    width: 32px !important;
    height: 32px !important;
    padding: 0 !important;
}
.btn-act-hapus:hover {
    background-color: #e11d48 !important;
    color: #ffffff !important;
}

/* ===== TIER FILTER & SEARCH BAR ===== */
.tier-filter-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 12px !important;
    padding-bottom: 12px !important;
    border-bottom: 1px solid #f1f5f9;
}

.tier-tabs-group {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
}

.tier-tab-link {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    text-decoration: none !important;
    line-height: 1.4 !important;
    white-space: nowrap !important;
    transition: all 0.2s ease !important;
    box-sizing: border-box !important;
}

.tier-tab-link .badge-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 2px 7px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    line-height: 1;
}

/* Tab Colors */
.tier-tab-link.tab-all { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }
.tier-tab-link.tab-all:hover { background: #e2e8f0; color: #1e293b; }
.tier-tab-link.tab-all.active { background: #0041c3 !important; color: #ffffff !important; border-color: #0041c3 !important; box-shadow: 0 4px 12px rgba(0,65,195,0.25); }
.tier-tab-link.tab-all.active .badge-count { background: rgba(255,255,255,0.25); color: #ffffff; }

.tier-tab-link.tab-prioritas { background: #fffbe6; color: #92400e; border: 1px solid #fde68a; }
.tier-tab-link.tab-prioritas:hover { background: #fef3c7; }
.tier-tab-link.tab-prioritas.active { background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important; color: #ffffff !important; border-color: #b45309 !important; box-shadow: 0 4px 12px rgba(217,119,6,0.3); }
.tier-tab-link.tab-prioritas.active .badge-count { background: rgba(255,255,255,0.25); color: #ffffff; }

.tier-tab-link.tab-loyal { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
.tier-tab-link.tab-loyal:hover { background: #e0f2fe; }
.tier-tab-link.tab-loyal.active { background: #0284c7 !important; color: #ffffff !important; border-color: #0284c7 !important; box-shadow: 0 4px 12px rgba(2,132,199,0.25); }
.tier-tab-link.tab-loyal.active .badge-count { background: rgba(255,255,255,0.25); color: #ffffff; }

.tier-tab-link.tab-reguler { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
.tier-tab-link.tab-reguler:hover { background: #f1f5f9; }
.tier-tab-link.tab-reguler.active { background: #334155 !important; color: #ffffff !important; border-color: #334155 !important; box-shadow: 0 4px 12px rgba(51,65,85,0.25); }
.tier-tab-link.tab-reguler.active .badge-count { background: rgba(255,255,255,0.25); color: #ffffff; }

.search-box-container {
    position: relative;
    min-width: 240px;
    max-width: 320px;
    flex: 1 1 240px;
}

.search-box-container input {
    width: 100%;
    padding: 7px 12px 7px 34px !important;
    border-radius: 8px !important;
    border: 1px solid #cbd5e1 !important;
    font-size: 13px !important;
    outline: none !important;
    background: #ffffff !important;
    transition: all 0.2s ease !important;
}

.search-box-container input:focus {
    border-color: #0041c3 !important;
    box-shadow: 0 0 0 3px rgba(0,65,195,0.15) !important;
}

.search-box-container .search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

/* ===== PAGINATION ===== */
.pagination {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin: 1rem 0 0.5rem 0;
    gap: 0.25rem;
    flex-wrap: wrap;
}
</style>

<!-- Header -->
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1>
            <i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Customer
        </h1>                
        <p>List Data Customer</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('customer.export_pdf') }}" target="_blank" class="button box flex items-center text-gray-700 bg-white hover:bg-slate-50 transition-colors shadow-xs px-3 py-2 rounded-lg text-xs font-semibold">
            <i data-feather="file-text" class="hidden sm:block w-4 h-4 mr-2 text-indigo-600"></i> Export to PDF
        </a>
    </div>
</header>

<div class="content-area">
    <div class="sukses" data-sukses="{{ session('sukses') }}"></div>
    
    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <!-- Filter Tabs & Search Header -->
        <div class="tier-filter-bar">
            <!-- Tier Filter Tabs -->
            <div class="tier-tabs-group">
                <a href="{{ route('customer.index', array_merge(request()->except('tier', 'page'), ['tier' => 'all'])) }}" 
                   class="tier-tab-link tab-all {{ ($current_tier ?? 'all') == 'all' ? 'active' : '' }}">
                    <i data-feather="users" class="w-4 h-4"></i>
                    <span>Semua</span>
                    <span class="badge-count">{{ $tier_counts['all'] ?? 0 }}</span>
                </a>
                <a href="{{ route('customer.index', array_merge(request()->except('tier', 'page'), ['tier' => 'prioritas'])) }}" 
                   class="tier-tab-link tab-prioritas {{ ($current_tier ?? '') == 'prioritas' ? 'active' : '' }}">
                    <i data-feather="award" class="w-4 h-4"></i>
                    <span>👑 Prioritas (VIP)</span>
                    <span class="badge-count">{{ $tier_counts['prioritas'] ?? 0 }}</span>
                </a>
                <a href="{{ route('customer.index', array_merge(request()->except('tier', 'page'), ['tier' => 'loyal'])) }}" 
                   class="tier-tab-link tab-loyal {{ ($current_tier ?? '') == 'loyal' ? 'active' : '' }}">
                    <i data-feather="star" class="w-4 h-4"></i>
                    <span>Loyal</span>
                    <span class="badge-count">{{ $tier_counts['loyal'] ?? 0 }}</span>
                </a>
                <a href="{{ route('customer.index', array_merge(request()->except('tier', 'page'), ['tier' => 'reguler'])) }}" 
                   class="tier-tab-link tab-reguler {{ ($current_tier ?? '') == 'reguler' ? 'active' : '' }}">
                    <i data-feather="user" class="w-4 h-4"></i>
                    <span>Reguler</span>
                    <span class="badge-count">{{ $tier_counts['reguler'] ?? 0 }}</span>
                </a>
            </div>

            <!-- Search Input -->
            <div class="search-box-container">
                <form action="{{ url()->current() }}" method="GET" style="margin:0;">
                    @if(request('tier'))
                        <input type="hidden" name="tier" value="{{ request('tier') }}">
                    @endif
                    <div style="position: relative;">
                        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Cari nama, alamat, no hp...">
                        <i data-feather="search" class="search-icon w-4 h-4"></i>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Container with Horizontal Scroll Support -->
        <div class="table-responsive-container">
            <table class="table table-report table-report--bordered" style="width: 100%; min-width: 1200px;">
                <thead>
                    <tr>
                        <th class="border-b-2 text-center whitespace-nowrap" style="width: 50px;">NO</th>
                        <th class="border-b-2 text-center whitespace-nowrap" style="width: 130px;">INVOICE</th>
                        <th class="border-b-2 whitespace-nowrap" style="min-width: 180px;">NAMA CUSTOMER</th>
                        <th class="border-b-2 text-center whitespace-nowrap" style="min-width: 160px;">SKOR & TIER</th>
                        <th class="border-b-2 whitespace-nowrap" style="min-width: 160px;">ALAMAT</th>
                        <th class="border-b-2 text-center whitespace-nowrap" style="width: 130px;">NO HP</th>
                        <th class="border-b-2 text-center whitespace-nowrap" style="width: 120px;">TANGGAL</th>
                        <th class="border-b-2 text-center whitespace-nowrap" style="width: 200px; min-width: 200px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @forelse ($custom as $index => $row)
                        @php
                            $isPriority = ($row->cos_tier === 'prioritas') || (($row->cos_score ?? 1) >= 5) || (($row->total_transaksi ?? 0) >= 5);
                            $isLoyal = ($row->cos_tier === 'loyal') || (($row->cos_score ?? 1) >= 3 && !$isPriority);
                            $score = $row->cos_score ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
                            $totalTx = $row->total_transaksi ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
                        @endphp
                        <tr class="{{ $isPriority ? 'bg-amber-50/50 hover:bg-amber-50/80 border-l-4 border-l-amber-500' : '' }}">
                            <td class="text-center border-b font-medium txt-slate-500">{{ $custom->firstItem() + $index }}</td>
                            <td class="text-center border-b font-semibold txt-slate-700 whitespace-nowrap">
                                <span class="bg-badge-invoice">
                                    {{ $row->trans_kode ?? $row->id_costomer }}
                                </span>
                            </td>
                            <td class="border-b">
                                <div class="font-semibold txt-slate-800 whitespace-nowrap flex items-center gap-1.5">
                                    <span>{{ $row->cos_nama }}</span>
                                    @if($isPriority)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300" title="Pelanggan Prioritas (VIP)">
                                            👑 VIP
                                        </span>
                                    @endif
                                </div>
                                <div class="txt-slate-500 text-xs whitespace-nowrap mt-0.5">
                                    STATUS : <span class="txt-indigo">{{ $row->trans_status ?? 'Baru' }}</span>
                                </div>
                            </td>
                            <td class="text-center border-b whitespace-nowrap">
                                @if($isPriority)
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-amber-500 to-yellow-500 text-white shadow-xs inline-flex items-center gap-1">
                                            <i data-feather="award" class="w-3 h-3"></i> Skor {{ $score }} • Prioritas
                                        </span>
                                        <span class="text-[10px] text-amber-700 font-semibold mt-0.5">
                                            {{ $totalTx }}x Tx • Diskon s/d 50%
                                        </span>
                                    </div>
                                @elseif($isLoyal)
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-800 border border-sky-200 inline-flex items-center gap-1">
                                            <i data-feather="star" class="w-3 h-3 text-sky-600"></i> Skor {{ $score }} • Loyal
                                        </span>
                                        <span class="text-[10px] txt-slate-500 mt-0.5">
                                            {{ $totalTx }}x Transaksi
                                        </span>
                                    </div>
                                @else
                                    <div class="inline-flex flex-col items-center">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                            Skor {{ $score }} • Reguler
                                        </span>
                                        <span class="text-[10px] txt-slate-400 mt-0.5">
                                            {{ $totalTx }}x Transaksi
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="border-b text-xs txt-slate-600 max-w-[220px] truncate" title="{{ $row->cos_alamat ?? '-' }}">
                                {{ $row->cos_alamat ?? '-' }}
                            </td>
                            <td class="text-center border-b text-xs font-medium txt-slate-700 whitespace-nowrap">
                                {{ $row->cos_hp ?? '-' }}
                            </td>
                            <td class="text-center border-b whitespace-nowrap">
                                <div class="font-medium text-xs txt-slate-700">
                                    {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') : '-' }}
                                </div>
                                <div class="txt-slate-400 text-[11px]">
                                    JAM : {{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('H:i:s') : '-' }}
                                </div>
                            </td>
                            <td class="border-b whitespace-nowrap text-center" style="width: 200px; min-width: 200px;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 6px;">
                                    {{-- Tombol Proses --}}
                                    <a href="{{ route('service.proses', $row->trans_kode ?? $row->id_costomer) }}"
                                       class="btn-act btn-act-proses"
                                       title="Proses Transaksi">
                                        <i data-feather="check-square" class="w-3.5 h-3.5"></i>
                                        <span>Proses</span>
                                    </a>
                                    
                                    @if(!empty($row->trans_kode))
                                    {{-- Tombol Print PDF --}}
                                    <a href="{{ route('admin.cetak.print_1', $row->trans_kode) }}" target="_blank"
                                       class="btn-act btn-act-print tooltip" title="Print PDF">
                                        <i data-feather="printer" class="w-3.5 h-3.5"></i>
                                    </a>
                                    @endif

                                    @if(!empty($row->cos_hp))
                                    {{-- Tombol WhatsApp --}}
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $row->cos_hp) }}" target="_blank"
                                       class="btn-act btn-act-wa tooltip" title="Kirim WhatsApp">
                                        <i data-feather="message-circle" class="w-3.5 h-3.5"></i>
                                    </a>
                                    @endif

                                    {{-- Tombol Hapus --}}
                                    <a href="{{ url('Customer/delete/'.$row->id_costomer) }}" data-nama="{{ $row->cos_nama }}"
                                       class="tombol-hapus btn-act btn-act-hapus tooltip" title="Hapus">
                                        <i data-feather="trash-2" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 txt-slate-400 border-b">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-feather="inbox" class="w-10 h-10 mb-2 text-slate-300"></i>
                                    <p class="font-medium txt-slate-600 text-sm">Tidak ada data customer yang ditemukan</p>
                                    <p class="text-xs txt-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau filter tier Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4" id="pagination-container">
            {{ $custom->links() }}
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection