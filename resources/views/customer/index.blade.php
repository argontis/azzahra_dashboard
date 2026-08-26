
@extends('layouts.app')
@section('content')
<style>
/* ===== MODERN TABLE DESIGN ===== */
.intro-y.datatable-wrapper {
    background: white;
    border-radius: 16px;
    box-shadow:
        0 10px 25px rgba(0, 0, 0, 0.08),
        0 4px 10px rgba(0, 0, 0, 0.04);
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

/* ===== TABLE HEADER ===== */
.table thead th {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    color: #374151;
    font-weight: 700;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1.25rem 1rem;
    border-bottom: 2px solid #e5e7eb;
    position: relative;
}

.table thead th::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, #0041c3 0%, #0052e6 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.table thead th:hover::after {
    opacity: 1;
}

/* ===== TABLE BODY ===== */
.table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f9fafb;
}

.table tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #f0f9ff 100%);
    transform: translateX(4px);
    box-shadow: 0 4px 12px rgba(0, 65, 195, 0.08);
}

.table tbody td {
    padding: 1.25rem 1rem;
    color: #4b5563;
    font-size: 0.875rem;
    vertical-align: middle;
}

/* ===== STATUS BADGES ===== */
.table tbody td .text-gray-600 {
    background: #f3f4f6;
    color: #6b7280;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    display: inline-block;
    margin-top: 0.25rem;
}

/* ===== ACTION BUTTONS ===== */
.table tbody td .flex.items-center a {
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
}

.table tbody td .flex.items-center a:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* ===== PAGINATION ===== */
.pagination {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    margin: 2.5rem 0;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 0.5rem 1rem;
    gap: 0.25rem;
    flex-wrap: wrap;
    box-shadow:
        0 8px 25px rgba(0, 0, 0, 0.06),
        0 4px 10px rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
}

.pagination::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg,
        rgba(0, 65, 195, 0.02) 0%,
        transparent 50%,
        rgba(0, 65, 195, 0.02) 100%);
    pointer-events: none;
}

/* ===== PAGINATION LINKS ===== */
.pagination-link,
.pagination-btn {
    min-width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 0.75rem;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
    background: white;
    border: 1.5px solid #d1d5db;
    color: #4b5563;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

/* ===== PAGINATION NUMBER LINKS ===== */
.pagination-link:hover {
    color: #0041c3;
    background: linear-gradient(135deg, rgba(0, 65, 195, 0.08) 0%, rgba(0, 65, 195, 0.12) 100%);
    border-color: rgba(0, 65, 195, 0.3);
    transform: translateY(-3px) scale(1.05);
    box-shadow:
        0 8px 25px rgba(0, 65, 195, 0.15),
        0 4px 12px rgba(0, 65, 195, 0.1);
}

.pagination-link.active {
    color: white;
    background: linear-gradient(135deg, #0041c3 0%, #0052e6 100%);
    background-size: 200% 100%;
    border-color: #0041c3;
    font-weight: 700;
    box-shadow:
        0 8px 25px rgba(0, 65, 195, 0.3),
        0 4px 12px rgba(0, 65, 195, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
    animation: activePulse 2s ease-in-out infinite;
    cursor: default;
}

.pagination-link.active:hover {
    transform: none;
}

/* ===== PAGINATION BUTTONS (First/Prev/Next/Last) ===== */
.pagination-btn {
    background: linear-gradient(135deg, #0041c3 0%, #0052e6 100%);
    color: white;
    border-color: #0041c3;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0, 65, 195, 0.2);
}

.pagination-btn:hover {
    background: linear-gradient(135deg, #0052e6 0%, #0063f0 100%);
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0, 65, 195, 0.25);
}

.pagination-first,
.pagination-last {
    padding: 0 1.5rem;
    min-width: 80px;
}

/* ===== ANIMATIONS ===== */
@keyframes activePulse {
    0%, 100% {
        background-position: 0% 50%;
        box-shadow:
            0 8px 25px rgba(0, 65, 195, 0.3),
            0 4px 12px rgba(0, 65, 195, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }
    50% {
        background-position: 100% 50%;
        box-shadow:
            0 10px 35px rgba(0, 65, 195, 0.4),
            0 6px 16px rgba(0, 65, 195, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .intro-y.datatable-wrapper {
        border-radius: 12px;
    }

    .table thead th {
        padding: 1rem 0.75rem;
        font-size: 0.75rem;
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        font-size: 0.8rem;
    }

    .pagination {
        padding: 0.5rem 0.75rem;
        gap: 0.25rem;
        margin: 2rem 0;
    }

    .pagination-link,
    .pagination-btn {
        min-width: 32px;
        height: 32px;
        font-size: 0.7rem;
        padding: 0 0.5rem;
        border-radius: 6px;
    }

    .pagination-first,
    .pagination-last {
        padding: 0 1rem;
        min-width: 70px;
    }
}

@media (max-width: 480px) {
    .intro-y.datatable-wrapper {
        border-radius: 8px;
    }

    .table thead th {
        padding: 0.75rem 0.5rem;
        font-size: 0.7rem;
    }

    .table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.75rem;
    }

    .pagination {
        padding: 0.375rem 0.5rem;
        gap: 0.125rem;
    }

    .pagination-link,
    .pagination-btn {
        min-width: 28px;
        height: 28px;
        font-size: 0.65rem;
        padding: 0 0.375rem;
        border-radius: 4px;
    }

    .pagination-first,
    .pagination-last {
        display: none; /* Hide First/Last on very small screens */
    }
}
</style>
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1>
                    <i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Customer</h1>                
                <p>List Data Customer</p>
            </div>
            <div class="header-actions">
             <a href="{{ route('customer.export_pdf') }}" target="_blank" class="button box flex items-center text-gray-700">
        		<i data-feather="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Export to PDF
        	</a>
            </div>
        </header>
<style>
/* ===== TIER FILTER & SEARCH BAR FIX ===== */
.tier-filter-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 16px;
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
    padding: 6px 14px !important;
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
    min-width: 260px;
    max-width: 340px;
    width: 100%;
}

.search-box-container input {
    width: 100%;
    padding: 8px 12px 8px 34px !important;
    border-radius: 8px !important;
    border: 1px solid #d1d5db !important;
    font-size: 13px !important;
    outline: none !important;
    background: #ffffff !important;
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
    color: #9ca3af;
    pointer-events: none;
}
</style>

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

    	<table class="table table-report table-report--bordered w-full">
    		<thead>
    			<tr>
    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
    				<th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
    	               <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
    	               <th class="border-b-2 text-center whitespace-no-wrap">SKOR & TIER</th>
    	               <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
    	               <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
    	               <th class="border-b-2 whitespace-no-wrap">TANGGAL</th>
    	               <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
    			</tr>
    		</thead>
    		<tbody id="table-body">
    			@foreach ($custom as $index => $row)
    					@php
    						$isPriority = ($row->cos_tier === 'prioritas') || (($row->cos_score ?? 1) >= 5) || (($row->total_transaksi ?? 0) >= 5);
    						$isLoyal = ($row->cos_tier === 'loyal') || (($row->cos_score ?? 1) >= 3 && !$isPriority);
    						$score = $row->cos_score ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
    						$totalTx = $row->total_transaksi ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
    					@endphp
    					<tr class="{{ $isPriority ? 'bg-amber-50/40 hover:bg-amber-50/70 border-l-4 border-l-amber-500' : '' }}">
    						<td class="text-center border-b">{{ $custom->firstItem() + $index }}</td>
    						<td class="text-center border-b font-medium text-gray-700">{{ $row->id_costomer }}</td>
    		                <td class="border-b">
    		                	<div class="font-medium whitespace-no-wrap flex items-center gap-1.5">
    		                		<span>{{ $row->cos_nama }}</span>
    		                		@if($isPriority)
    		                			<span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-xs" title="Pelanggan Prioritas (VIP)">
    		                				👑 VIP
    		                			</span>
    		                		@endif
    		                	</div>
    		                    <div class="text-gray-600 text-xs whitespace-no-wrap mt-0.5">
    		                    	STATUS : <span class="font-medium">{{ $row->trans_status ?? 'Baru' }}</span>
    		                    </div>
    		                </td>
    		                <td class="text-center border-b whitespace-no-wrap">
    		                	@if($isPriority)
    		                		<div class="inline-flex flex-col items-center">
    		                			<span class="px-2.5 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-amber-500 to-yellow-500 text-white shadow-xs inline-flex items-center gap-1">
    		                				<i data-feather="award" class="w-3 h-3"></i> Skor {{ $score }} • Prioritas
    		                			</span>
    		                			<span class="text-[10px] text-amber-700 font-semibold mt-0.5">
    		                				{{ $totalTx }}x Tx • Diskon s/d 50%
    		                			</span>
    		                		</div>
    		                	@elseif($isLoyal)
    		                		<div class="inline-flex flex-col items-center">
    		                			<span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200 inline-flex items-center gap-1">
    		                				<i data-feather="star" class="w-3 h-3 text-blue-600"></i> Skor {{ $score }} • Loyal
    		                			</span>
    		                			<span class="text-[10px] text-gray-500 mt-0.5">
    		                				{{ $totalTx }}x Transaksi
    		                			</span>
    		                		</div>
    		                	@else
    		                		<div class="inline-flex flex-col items-center">
    		                			<span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
    		                				Skor {{ $score }} • Reguler
    		                			</span>
    		                			<span class="text-[10px] text-gray-400 mt-0.5">
    		                				{{ $totalTx }}x Transaksi
    		                			</span>
    		                		</div>
    		                	@endif
    		                </td>
    		                <td class="border-b text-xs">{{ $row->cos_alamat ?? '-' }}</td>
    		                <td class="text-center border-b text-xs font-medium">{{ $row->cos_hp ?? '-' }}</td>
    		                <td class="border-b">
    		                	<div class="font-medium whitespace-no-wrap text-xs">
    		                		{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}
    		                	</div>
    		                    <div class="text-gray-600 text-[11px] whitespace-no-wrap">
    		                    	JAM : {{ \Carbon\Carbon::parse($row->created_at)->format('H:i:s') }}
    		                    </div>
    		                </td>
    		                <td class="border-b whitespace-no-wrap">
    		                    <div class="flex items-center gap-1">
    		                        {{-- Tombol Proses --}}
    		                        <a href="{{ route('service.proses', $row->trans_kode ?? $row->id_costomer) }}"
    		                           class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-semibold text-white bg-green-500 hover:bg-green-600 transition-colors shadow-sm">
    		                            <i data-feather="check-square" class="w-3 h-3"></i> Proses
    		                        </a>
    		                        {{-- Tombol Print PDF --}}
    		                        <a href="{{ route('admin.cetak.print_1', $row->trans_kode) }}" target="_blank"
    		                           class="inline-flex items-center justify-center w-7 h-7 rounded-md text-white bg-red-500 hover:bg-red-600 transition-colors shadow-sm tooltip" title="Print PDF">
    		                            <i data-feather="printer" class="w-3 h-3"></i>
    		                        </a>
    		                        {{-- Tombol WhatsApp --}}
    		                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $row->cos_hp) }}" target="_blank"
    		                           class="inline-flex items-center justify-center w-7 h-7 rounded-md text-white bg-green-500 hover:bg-green-600 transition-colors shadow-sm tooltip" title="Kirim WhatsApp">
    		                            <i data-feather="message-circle" class="w-3 h-3"></i>
    		                        </a>
    		                        {{-- Tombol Hapus --}}
    		                        <a href="{{ url('Customer/delete/'.$row->id_costomer) }}" data-nama="{{ $row->cos_nama }}"
    		                           class="tombol-hapus inline-flex items-center justify-center w-7 h-7 rounded-md text-white bg-gray-400 hover:bg-gray-500 transition-colors shadow-sm tooltip" title="Hapus">
    		                            <i data-feather="trash-2" class="w-3 h-3"></i>
    		                        </a>
    		                    </div>
    		                </td>
    		            </tr>
    			@endforeach
    		</tbody>
    	</table>
    	<div class="mt-5" id="pagination-container">
    		{{ $custom->links() }}
    	</div>
    </div>
</div>



@endsection