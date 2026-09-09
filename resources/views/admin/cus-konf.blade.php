@extends('layouts.app')

@section('content')
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="package" class="w-6 h-6 inline-block mr-2"></i>Konfirmasi</h1>                
                <p>List data yang butuh konfirmasi</p>
            </div>
            <div class="header-actions">
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
                    <i data-feather="bell"></i>
                    <div class="badge-dot" style="display: none;"></div>
                </div>
                <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
                    <i data-feather="mail"></i>
                    <span class="topbar-mail-badge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 9999px; font-size: 10px; font-weight: bold; min-width: 16px; height: 16px; line-height: 16px; text-align: center; padding: 0 4px;"></span>
                </div>
            </div>
        </header>

<div class="content mt-5">	
    <!-- <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="{{ url('Admin/cus_baru') }}" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="{{ url('Admin/cus_konf') }}" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium">
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Konfirmasi
		            </a>
		            <a href="{{ url('Admin/cus_konf_bank') }}" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Bank Transfer 
		            </a>
		            <a href="{{ url('Admin/cus_proses') }}" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="play-circle"></i> Di proses 
		            </a>
		        </div>
    		</div>
    	</div> -->
    	<div class="col-span-12 lg:col-span-9 xxl:col-span-10">
    		<div class="intro-y datatable-wrapper box p-5 mt-5">
    			<table class="table table-report table-report--bordered display datatable w-full">
    				<thead>
		    			<tr>
		    				<th class="border-b-2 text-center whitespace-no-wrap">NO</th>
		    				<th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
		                    <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
		                    <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    			@foreach ($trans as $row)
			    				<tr>
				    				<td class="text-center border-b">{{ $loop->iteration }}</td>
				    				<td class="text-center border-b font-medium">{{ $row->trans_kode ?: ($row->cos_kode ?? 'N/A') }}</td>
				    				<td class="border-b">{{ $row->cos_nama ?: 'N/A' }}</td>
				    				<td class="border-b">{{ $row->cos_alamat ?: 'N/A' }}</td>
				    				<td class="text-center border-b">{{ $row->cos_hp ?: 'N/A' }}</td>
				    				<td class="text-center">
				    					<div class="flex sm:justify-center items-center">
				    						@if(!empty($row->trans_kode))
				    							<a href="{{ url('Admin/konfirmasi/'.$row->trans_kode) }}" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-9 text-white">
				    								<i data-feather="check-square" class="w-4 h-4 mr-2"></i> Konfirmasi
				    							</a>
				    						@else
				    							<button type="button" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-gray-400 text-white cursor-not-allowed" disabled title="Kode transaksi tidak ditemukan">
				    								<i data-feather="alert-circle" class="w-4 h-4 mr-2"></i> Konfirmasi
				    							</button>
				    						@endif
				    					</div>
				    					
				    				</td>
				    			</tr>
			    			@endforeach
						<tr id="noSearchResultRow" style="display:none;">
							<td colspan="6" class="text-center py-8 text-gray-400">
								<i data-feather="search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
								<p>Tidak ada konfirmasi yang cocok dengan pencarian.</p>
							</td>
						</tr>
		    		</tbody>
    			</table>
    		</div>
    	</div>
    	
</div>
@endsection
