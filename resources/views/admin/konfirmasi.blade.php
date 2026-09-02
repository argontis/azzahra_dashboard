@extends('layouts.app')

@section('content')
<div class="content">
    @if(session('sukses'))
        <div class="rounded-md flex items-center px-5 py-4 mb-2 mt-5 bg-theme-9 text-white">
            <i data-feather="check" class="w-6 h-6 mr-2"></i> {{ session('sukses') }}
        </div>
    @endif
    @if(session('gagal'))
        <div class="rounded-md flex items-center px-5 py-4 mb-2 mt-5 bg-theme-6 text-white">
            <i data-feather="x" class="w-6 h-6 mr-2"></i> {{ session('gagal') }}
        </div>
    @endif

    <header class="page-header mb-5">
        <div class="header-title">
            <h1><i data-feather="layout" class="w-6 h-6 inline-block mr-2"></i>{{ $title ?? 'Admin Dashboard' }}</h1>
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

    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="flex flex-col lg:flex-row border-b border-gray-200 pb-5 -mx-5">
            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Azzahra" class="rounded-full" src="{{ asset('assets/template/beck/dist/images/profile-14.jpg') }}">
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">{{ $proses['cos_nama'] }}</div>
                    <div class="text-gray-600">{{ $proses['cos_kode'] }}</div>
                </div>
            </div>
            <div class="flex mt-6 lg:mt-0 items-center lg:items-start flex-1 flex-col justify-center text-gray-600 px-5 border-l border-r border-gray-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="truncate sm:whitespace-normal flex items-center"> 
                    <i data-feather="phone" class="w-4 h-4 mr-2"></i> {{ $proses['cos_hp'] }} 
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                    <i data-feather="eye" class="w-4 h-4 mr-2"></i> {{ $proses['cos_status'] }} 
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> 
                    <i data-feather="hard-drive" class="w-4 h-4 mr-2"></i> {{ $proses['cos_tipe'] }} 
                </div>
            </div>
            <div class="mt-6 lg:mt-0 flex-1 px-5 border-t lg:border-0 border-gray-200 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-5">Alamat</div>
                <div class="flex items-center justify-center lg:justify-start mt-2">
                    <div class="mr-2 w-80 flex">
                        <textarea class="ml-3 font-medium" style="width: 300px;" readonly>{{ $proses['cos_alamat'] }}</textarea>
                    </div>
                </div>
            </div>                    
        </div>
        <div class="nav-tabs flex flex-col sm:flex-row justify-center lg:justify-start"> 
            <a data-toggle="tab" data-target="#konfirmasi" role="button" class="py-4 sm:mr-8 active">Konfirmasi Harga</a> 
            <a data-toggle="tab" data-target="#unit" role="button" class="py-4 sm:mr-8">Detail Unit</a>
            <a data-toggle="tab" data-target="#kelket" role="button" class="py-4 sm:mr-8">Keluhan & Keterangan</a> 
        </div>
    </div>
    <div class="intro-y tab-content mt-5">
        <div class="tab-content__pane active" id="konfirmasi">
            <div class="intro-y box col-span-12">
                <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto"><br>Konfirmasi Persetujuan Harga</h2>
                </div>
                <div class="p-5">
                    <form method="post" action="{{ route('admin.update_konf') }}">
                        @csrf
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr class="bg-gray-700 text-white">
                                        <th class="whitespace-no-wrap">#</th>
                                        <th class="whitespace-no-wrap">Tindakan/Sparepart</th>
                                        <th class="whitespace-no-wrap">Keterangan</th>
                                        <th class="whitespace-no-wrap">Qty</th>
                                        <th class="whitespace-no-wrap">Subtotal (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $row)
                                        <tr>
                                            <td class="border-b">
                                                {{ $index + 1 }}
                                                <input type="hidden" name="tdkn[]" value="{{ $row->tdkn_kode }}">
                                            </td>
                                            <td class="border-b">
                                                <input type="hidden" name="tindakan[]" value="{{ $row->tdkn_barang }}">
                                                {{ $row->tdkn_barang }}
                                            </td>
                                            <td class="border-b">
                                                -
                                            </td>
                                            <td class="border-b">
                                                <input type="number" class="input border w-20" name="qty[]" value="{{ $row->tdkn_qty }}">
                                            </td>
                                            <td class="border-b">
                                                <input type="text" class="input border w-40" name="subtot[]" value="{{ number_format($row->tdkn_subtot, 0, '', '.') }}" onkeyup="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');">
                                            </td>
                                        </tr>	
                                    @endforeach	
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="tras_kode" value="{{ $proses['trans_kode'] }}">
                        <div class="flex justify-center mt-5">
                            <button type="submit" class="button w-40 bg-theme-1 text-white">Simpan Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Tab Unit -->
        <div class="tab-content__pane" id="unit">
            <div class="intro-y box col-span-12 lg:col-span-6">
                <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto"><br>Data Unit</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                        <div class="intro-y col-span-12">
                            <div class="mb-2">Status </div>
                            <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_status'] }}" readonly>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <div class="mb-2">Type </div>
                            <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_tipe'] }}" readonly>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <div class="mb-2">Model </div>
                            <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_model'] }}" readonly>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <div class="mb-2">No seri </div>
                            <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_no_seri'] }}" readonly>
                        </div>
                        <div class="intro-y col-span-12 sm:col-span-6">
                            <div class="mb-2">Password </div>
                            <input type="text" class="input w-full border flex-1" value="{{ $proses['cos_pswd'] }}" readonly>
                        </div>
                        <div class="intro-y col-span-12">
                            <div class="mb-2">Asesoris</div>
                            <textarea class="input w-full border mt-2 flex-1" readonly>{{ $proses['cos_asesoris'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab Keluhan -->
        <div class="tab-content__pane" id="kelket">
            <div class="intro-y box col-span-12 lg:col-span-6">
                <div class="flex items-center px-5 py-5 sm:py-0 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto"><br>Keluhan dan Keterangan</h2>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                        <div class="intro-y col-span-12">
                            <div class="mb-2">Keluhan</div>
                            <textarea class="input w-full border mt-2 flex-1" readonly>{{ $proses['cos_keluhan'] }}</textarea>
                        </div>
                        <div class="intro-y col-span-12">
                            <div class="mb-2">Keterangan</div>
                            <textarea class="input w-full border mt-2 flex-1" readonly>{{ $proses['cos_keterangan'] }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
