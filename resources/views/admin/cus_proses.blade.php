@extends('layouts.app')

@section('content')
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="play-circle" class="w-6 h-6 inline-block mr-2"></i>Proses</h1>                
                <p>Transaksi Menunggu Pelunasan</p>
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
	<div class="sukses" data-sukses="<?php echo session('sukses');?>"></div>	
    <!-- <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
    	<div class="col-span-12 lg:col-span-3 xxl:col-span-2">
    		<div class="intro-y box p-5 mt-6">
    			<div class="mt-1">
		            <a href="{{ url('Admin/cus_baru') }}" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
		            </a>
		            <a href="{{ url('Admin/cus_konf') }}" class="flex items-center px-3 py-2 mt-2 rounded-md">
		            	<i class="w-4 h-4 mr-2" data-feather="user-check"></i> Konfirmasi
		            </a>
		            <a href="{{ url('Admin/cus_konf_bank') }}" class="flex items-center px-3 py-2 mt-2 rounded-md"> 
		            	<i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Bank Transfer 
		            </a>
		            <a href="{{ url('Admin/cus_proses') }}" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium"> 
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
		    				<th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">JML BAYAR</th>
		                    <th class="border-b-2 whitespace-no-wrap">STATUS</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">SETORAN</th>
		                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
		    			</tr>
		    		</thead>
		    		<tbody>
						@php $no = 0; @endphp
		    			@foreach ($trans as $row)
			    				<tr>
				    				<td class="text-center border-b">{{ ++$no; }}</td>
				    				<td class="border-b">{{ $row->cos_nama; }}</td>
				    				<td class="text-center border-b">
				    					{{ "Rp. ".number_format($row->dtl_jml_bayar, 0).",-"; }}
				    				</td>
				    				<td class="border-b">
				    					<div class="font-medium whitespace-no-wrap">{{ $row->dtl_status; }}</div>
				    					<div class="text-gray-600 text-xs whitespace-no-wrap">{{ $row->dtl_jenis_bayar }} - {{ $row->dtl_bank }}</div>
				    				</td>
				    				<td class="text-center border-b">{{ $row->dtl_stt_stor; }}</td>
				    				<td class="text-center">
				    					<div class="flex sm:justify-center items-center">
				    						<a role="button" data-theme="light" data-tooltip-content="#custom-content-tooltip" data-event="on-click" class="tooltip button inline-block bg-theme-1 text-white" title="Customer dalam pelunasan">Info</a>
				    					</div>
				    					<div class="tooltip-content">
	                                        <div id="custom-content-tooltip" class="relative flex items-center py-1">
	                                            <div class="w-12 h-12 image-fit">
	                                                <img alt="Midone Tailwind HTML Admin Template" class="rounded-full" src="{{ asset("/") }}assets/template/beck/dist/images/profile-13.jpg">
	                                            </div>
	                                            <div class="ml-4 mr-auto">
	                                                <div class="font-medium leading-relaxed">{{ $row->cos_nama }}</div>
	                                                <div class="text-gray-600">{{ $row->cos_kode }}</div>
	                                            </div>
	                                        </div>
	                                    </div>
				    					
				    				</td>
				    			</tr>
			    			@endforeach
		    		</tbody>
    			</table>
    		</div>
    	</div>
    	
</div>
@endsection
