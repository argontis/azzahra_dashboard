
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
<div class="content-area">
	<div class="sukses" data-sukses="{{ session('sukses') }}"></div>
	
    <div class="intro-y datatable-wrapper box p-5 mt-5">
    	<!-- Search Input -->
    	<div class="mb-4">
    		<div class="flex items-center space-x-2">
    			<form action="{{ url()->current() }}" method="GET" class="w-full"><input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search customers..." class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
    		</div>
    	</div>
    	<table class="table table-report table-report--bordered w-full">
    		<thead>
    			<tr>
    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
    				<th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
    	               <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
    	               <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
    	               <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
    	               <th class="border-b-2 whitespace-no-wrap">TANGGAL</th>
    	               <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
    			</tr>
    		</thead>
    		<tbody id="table-body">
    			@foreach ($custom as $index => $row)
    					<tr>
    						<td class="text-center border-b">{{ $custom->firstItem() + $index }}</td>
    						<td class="text-center border-b">{{ $row->id_costomer }}</td>
    		                <td class="border-b">
    		                	<div class="font-medium whitespace-no-wrap">
    		                		{{ $row->cos_nama }}
    		                	</div>
    		                    <div class="text-gray-600 text-xs whitespace-no-wrap">
    		                    	STATUS : {{ $row->trans_status }}
    		                    </div>
    		                </td>
    		                <td class="border-b">{{ $row->cos_alamat }}</td>
    		                <td class="text-center border-b">{{ $row->cos_hp }}</td>
    		                <td class="border-b">
    		                	<div class="font-medium whitespace-no-wrap">
    		                		{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}
    		                	</div>
    		                    <div class="text-gray-600 text-xs whitespace-no-wrap">
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