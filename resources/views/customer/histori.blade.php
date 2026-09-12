@extends('layouts.app')

@section('content')
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="user" class="w-5 h-5 inline-block mr-2"></i>Data Customer</h1>
        <p>Histori Transaksi Customer</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>
<div class="content-area">
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-3 xxl:col-span-2">
            <div class="intro-y box p-5 mt-6">
                <div class="mt-1">
                    <a href="{{ route('service.antrean', 'baru') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ request()->segment(3) == 'baru' ? 'bg-theme-1 text-white font-medium' : '' }}">
                        <i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
                    </a>
                    <a href="{{ route('service.antrean', 'proses') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ request()->segment(3) == 'proses' ? 'bg-theme-1 text-white font-medium' : '' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi diproses 
                    </a>
                    <a href="{{ route('service.antrean', 'konfirmasi') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ request()->segment(3) == 'konfirmasi' ? 'bg-theme-1 text-white font-medium' : '' }}">
                        <i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfirmasi
                    </a>
                    <a href="{{ route('service.antrean', 'pelunasan') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ request()->segment(3) == 'pelunasan' ? 'bg-theme-1 text-white font-medium' : '' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Pelunasan 
                    </a>
                    <a href="{{ route('service.antrean', 'lunas') }}" class="flex items-center px-3 py-2 rounded-md bg-theme-1 text-white font-medium"> 
                        <i class="w-4 h-4 mr-2" data-feather="users"></i> Customer
                    </a>
                </div>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10">
            <div class="intro-y box px-5 pt-5 mt-5">
                <div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
                    <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                            <img alt="Azzahra" class="rounded-full" src="{{ asset('assets/template/beck/dist/images/profile-14.jpg') }}">
                            <div class="absolute mb-1 mr-1 flex items-center justify-center bottom-0 right-0 bg-theme-1 rounded-full p-2"> <i class="w-4 h-4 text-white" data-feather="camera"></i> </div>
                        </div>
                        <div class="ml-5">
                            <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $proses['cos_nama'] ?? '-' }}</div>
                            <div class="text-gray-600">{{ $proses['cos_kode'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                        <div class="truncate sm:whitespace-normal flex items-center"> 
                            <i data-feather="phone" class="w-4 h-4 mr-2"></i> {{ $proses['cos_hp'] ?? '-' }} 
                        </div>
                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                            <i data-feather="eye" class="w-4 h-4 mr-2"></i> {{ $proses['cos_status'] ?? '-' }} 
                        </div>
                        <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                            <i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> {{ $proses['cos_tipe'] ?? '-' }} 
                        </div>
                    </div>
                    <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                        <div class="font-medium text-center lg:text-left lg:mt-5">Alamat</div>
                        <div class="flex items-center justify-center lg:justify-start mt-2">
                            <div class="mr-2 w-80 flex">
                                <textarea class="ml-3 font-medium" style="width: 300px;" readonly>{{ $proses['cos_alamat'] ?? '-' }}</textarea>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start">
                    <a data-toggle="tab" data-target="#unit" role="button" class="py-4 sm:mr-8 active">Unit</a>
                    <a data-toggle="tab" data-target="#kelket" role="button" class="py-4 sm:mr-8">Keluhan & Keterangan</a>
                    <a data-toggle="tab" data-target="#tindakan" role="button" class="py-4 sm:mr-8">Tindakan</a>
                    <a data-toggle="tab" data-target="#histori" role="button" class="py-4 sm:mr-8">Histori Pembayaran</a>
                </div>
            </div>
            
            <div class="intro-y tab-content mt-5">
                <!-- Tab Unit -->
                <div class="tab-content__pane active" id="unit">
                    <div class="intro-y box col-span-12 lg:col-span-6">
                        <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Data Unit
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                                <div class="intro-y col-span-12">
                                    <div class="mb-2">Status </div>
                                    <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_status'] ?? '-' }}" readonly>
                                </div>
                                <div class="intro-y col-span-12 sm:col-span-6">
                                    <div class="mb-2">Type </div>
                                    <input type="text" class="input w-full border flex-1"  value="{{ $proses['cos_tipe'] ?? '-' }}" readonly>
                                </div>
                                <div class="intro-y col-span-12 sm:col-span-6">
                                    <div class="mb-2">Model </div>
                                    <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_model'] ?? '-' }}" readonly>
                                </div>
                                <div class="intro-y col-span-12 sm:col-span-6">
                                    <div class="mb-2">No seri </div>
                                    <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_no_seri'] ?? '-' }}" readonly>
                                </div>
                                <div class="intro-y col-span-12 sm:col-span-6">
                                    <div class="mb-2">Password </div>
                                    <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_pswd'] ?? '-' }}" readonly>
                                </div>
                                <div class="intro-y col-span-12">
                                    <div class="mb-2">Asesoris</div>
                                    <textarea class="input w-full border mt-2 flex-1" readonly>{{ $proses['cos_asesoris'] ?? '-' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Keluhan -->
                <div class="tab-content__pane" id="kelket">
                    <div class="intro-y box col-span-12 lg:col-span-6">
                        <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Keluhan dan Keterangan
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                                <div class="intro-y col-span-12">
                                    <div class="mb-2">Keluhan</div>
                                    <textarea class="input w-full border mt-2 flex-1" readonly>{{ $proses['cos_keluhan'] ?? '-' }}</textarea>
                                </div>
                                <div class="intro-y col-span-12">
                                    <div class="mb-2">Keterangan</div>
                                    <textarea class="input w-full border mt-2 flex-1" readonly>{{ $proses['cos_keterangan'] ?? '-' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Tindakan -->
                <div class="tab-content__pane" id="tindakan">
                    <div class="intro-y box col-span-12 lg:col-span-6">
                        <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                            <h2 class="font-medium text-base mr-auto"><br>
                                Tindakan Teknisi
                            </h2>
                        </div>
                        <div class="p-5">
                            <div class="overflow-x-auto">
                                <table class="table">
                                    <thead>
                                        <tr class="bg-gray-700 text-white">
                                            <th class="whitespace-no-wrap">#</th>
                                            <th class="whitespace-no-wrap">Tindakan</th>
                                            <th class="whitespace-no-wrap">Qty</th>
                                            <th class="whitespace-no-wrap">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tindakan as $index => $row)
                                        <tr>
                                            <td class="border-b">{{ $index + 1 }}</td>
                                            <td class="border-b">{{ $row->tdkn_barang }}</td>
                                            <td class="border-b">{{ $row->tdkn_qty }}</td>
                                            <td class="border-b">Rp. {{ number_format($row->tdkn_subtot, 0, ',', '.') }},-</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="border-b">Discount</td>
                                            <td class="border-b text-theme-9">
                                                Rp. {{ number_format($transaksi->trans_discount, 0, ',', '.') }},-
                                            </td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td class="border-b">Total</td>
                                            <td class="border-b text-theme-9 font-medium">
                                                Rp. {{ number_format($transaksi->trans_total - $transaksi->trans_discount, 0, ',', '.') }},-
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Histori -->
                <div class="tab-content__pane" id="histori">
                    <div class="intro-y datatable-wrapper box p-5 mt-5">
                        <h2 class="text-lg font-medium mr-auto">
                            Histori Pembayaran
                        </h2>
                        <table class="table table-report table-report--bordered display datatable w-full">
                            <thead>
                                <tr>
                                    <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">TOTAL BAYAR</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">JENIS</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">TANGGAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pembayaran as $index => $row)
                                <tr class="cursor-pointer zoom-in">
                                    <td class="text-center border-b">{{ $index + 1 }}</td>
                                    <td class="text-center border-b">
                                        @if($row->dtl_stt_stor == 'Menunggu')
                                        <div class="text-theme-6">
                                            Rp. {{ number_format($row->dtl_jml_bayar, 0, ',', '.') }},-
                                        </div>
                                        @else
                                        Rp. {{ number_format($row->dtl_jml_bayar, 0, ',', '.') }},-
                                        @endif
                                    </td>
                                    <td class="text-center border-b">{{ $row->dtl_jenis_bayar }}</td>
                                    <td class="text-center border-b">{{ $row->dtl_status }}</td>
                                    <td class="text-center border-b">
                                        <div class="font-medium whitespace-no-wrap">
                                            {{ date('d-m-Y', strtotime($row->dtl_tanggal)) }}
                                        </div>
                                        <div class="text-gray-600 text-xs whitespace-no-wrap">
                                            JAM : {{ $row->dtl_jam }}
                                        </div>                              
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>
@endsection
