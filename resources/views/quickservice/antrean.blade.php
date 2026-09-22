@extends('layouts.app')

@section('content')
<style>
/* ===== WIZARD STEPPER STYLES ===== */
.wizard-step-btn {
    transition: all 0.2s ease-in-out;
    border: none;
    outline: none;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 9999px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    z-index: 10;
    cursor: pointer;
}
.wizard-step-btn.active-step {
    background-color: #1e1b4b !important; /* Indigo-900 */
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(30, 27, 75, 0.25);
}
.wizard-step-btn.inactive-step {
    background-color: #e2e8f0 !important;
    color: #64748b !important;
}
</style>
<!-- Header Area -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="zap" class="w-6 h-6 inline-block mr-2"></i>Quick Service</h1>
        <p>Manage quick service data</p>
    </div>
    
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
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
    <div class="sukses" data-sukses="{{ session('sukses') }}"></div>
    <div class="gagal" data-gagal="{{ session('gagal') }}"></div>
    
    <div class="intro-y box p-5 mt-5 bg-white shadow-sm rounded-lg border border-gray-100">
        <!-- Card Header (Inside Card) -->
        <div class="flex flex-col sm:flex-row items-center pb-4 border-b border-gray-100 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mr-auto">Data Pelunasan Quick Service</h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <a role="button" class="button text-white bg-theme-1 shadow-md px-4 py-2 rounded-md font-medium flex items-center gap-2" data-toggle="modal" data-target="#add-new-costom">
                    Buat Transaksi
                </a>
            </div>
        </div>

        <!-- Card Content (2-Column Grid) -->
        <div class="grid grid-cols-12 gap-6">
            <!-- Left Sidebar Column inside Card -->
            <div class="col-span-12 lg:col-span-3">
                <div class="flex flex-col gap-1">
                    <a href="{{ route('quickservice.cos_baru') }}" class="flex items-center px-4 py-3 rounded-md transition-colors {{ request()->routeIs('quickservice.cos_baru') || request()->routeIs('quickservice.index') || $current_status === 'baru' ? 'bg-theme-1 text-white font-medium shadow-md' : 'text-gray-600 hover:bg-gray-100 font-medium' }}"> 
                        <i class="w-4 h-4 mr-3" data-feather="user-plus"></i> Transaksi baru 
                    </a>
                </div>
            </div>

            <!-- Right Table Column inside Card -->
            <div class="col-span-12 lg:col-span-9 border-l border-gray-100 pl-0 lg:pl-6">
                <!-- Custom styles to style the DataTables controls nicely -->
                <style>
                    .dataTables_wrapper .dataTables_length,
                    .dataTables_wrapper .dataTables_filter {
                        margin-bottom: 1.25rem;
                        color: #4a5568;
                        font-size: 0.875rem;
                    }
                    .dataTables_wrapper .dataTables_length select {
                        border: 1px solid #cbd5e1;
                        border-radius: 0.375rem;
                        padding: 0.25rem 1.5rem 0.25rem 0.5rem;
                        outline: none;
                        background-color: white;
                    }
                    .dataTables_wrapper .dataTables_filter input {
                        border: 1px solid #cbd5e1;
                        border-radius: 0.375rem;
                        padding: 0.375rem 0.75rem;
                        margin-left: 0.5rem;
                        outline: none;
                        width: 200px;
                    }
                    .dataTables_wrapper .dataTables_info {
                        margin-top: 1.25rem;
                        color: #4a5568;
                        font-size: 0.875rem;
                        float: left;
                    }
                    .dataTables_wrapper .dataTables_paginate {
                        margin-top: 1.25rem;
                        float: right;
                    }
                    .dataTables_wrapper .paginate_button {
                        padding: 0.375rem 0.75rem;
                        border: 1px solid #cbd5e1;
                        border-radius: 0.375rem;
                        margin-left: 0.25rem;
                        cursor: pointer;
                        color: #4a5568 !important;
                        background: white;
                        font-weight: 500;
                        font-size: 0.875rem;
                        transition: all 0.2s ease;
                        text-decoration: none;
                        display: inline-block;
                    }
                    .dataTables_wrapper .paginate_button:hover {
                        background: #f1f5f9;
                        border-color: #cbd5e1;
                        color: #0f172a !important;
                    }
                    .dataTables_wrapper .paginate_button.current {
                        background: #0041c3 !important;
                        color: white !important;
                        border-color: #0041c3 !important;
                        font-weight: 600;
                    }
                    .dataTables_wrapper .paginate_button.disabled {
                        opacity: 0.5;
                        cursor: not-allowed;
                    }
                    /* table collapse expand icon */
                    .dtr-control-btn {
                        color: #059669;
                        font-weight: bold;
                        font-size: 1.1rem;
                    }
                </style>

                <div class="overflow-x-auto">
                    <table id="quickServiceTable" class="table table-report table-report--bordered w-full">
                        <thead>
                            <tr>
                                <th class="w-8 text-center border-b-2"></th>
                                <th class="border-b-2 text-center whitespace-no-wrap w-12">NO</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                                <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                                <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksis as $index => $row)
                                <tr>
                                    <td class="text-center border-b dtr-control-btn cursor-pointer font-bold select-none">+</td>
                                    <td class="text-center border-b whitespace-no-wrap">{{ $transaksis instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($transaksis->firstItem() + $index) : ($index + 1) }}</td>
                                    <td class="text-center border-b whitespace-no-wrap font-medium text-gray-700">{{ $row->trans_kode }}</td>
                                    <td class="border-b whitespace-no-wrap font-medium text-blue-800">{{ $row->customer->cos_nama ?? '-' }}</td>
                                    <td class="border-b whitespace-no-wrap text-gray-600">{{ $row->customer->cos_alamat ?? '-' }}</td>
                                    <td class="text-center border-b whitespace-no-wrap text-gray-600">
                                        @php
                                            $hp = $row->customer->cos_hp ?? '';
                                            $masked_hp = (strlen($hp) > 4 && $hp !== 'XXXX') ? substr($hp, 0, -4) . 'XXXX' : $hp;
                                        @endphp
                                        {{ $masked_hp }}
                                    </td>
                                    <td class="text-center border-b whitespace-no-wrap">
                                        <div class="flex sm:justify-center items-center gap-1.5">
                                            <a href="{{ route('service.proses', $row->trans_kode) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md text-xs font-semibold text-white bg-green-500 hover:bg-green-600 transition-colors shadow-sm duration-200" title="Proses">
                                                <i data-feather="check-square" class="w-3.5 h-3.5"></i> Proses
                                            </a>
                                            <a href="{{ route('admin.cetak.print_1', $row->trans_kode) }}" target="_blank" class="inline-flex items-center justify-center w-7 h-7 rounded-md text-white bg-red-500 hover:bg-red-600 transition-colors shadow-sm duration-200" title="Print TTS (PDF)">
                                                <i data-feather="printer" class="w-3.5 h-3.5"></i>
                                            </a>
                                            <a role="button" onclick="sendToWA('{{ url('/') }}', '{{ $row->customer->cos_hp ?? '' }}', '{{ $row->customer->cos_nama ?? '' }}', '{{ $row->cos_kode }}', '{{ $row->trans_kode }}')" class="inline-flex items-center justify-center w-7 h-7 rounded-md text-white bg-green-500 hover:bg-green-600 transition-colors shadow-sm duration-200" title="Kirim WA">
                                                <i data-feather="message-circle" class="w-3.5 h-3.5"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center p-6 text-gray-500 italic">Belum ada transaksi Quick Service</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal tambah customer QS -->
<div class="modal" id="add-new-costom">
    <div class="modal__content modal__content--xl p-6 sm:p-8 bg-white rounded-lg shadow-lg">
        <!-- Wizard Stepper matching the screenshots -->
        <div class="flex items-center justify-center mb-8 relative pt-4">
            <!-- Connecting Line -->
            <div class="absolute left-0 right-0 top-9 h-0.5 bg-gray-200" style="width: 50%; margin: 0 auto; z-index: 1;"></div>
            
            <div class="flex justify-between w-full max-w-md relative" style="z-index: 2;">
                <!-- Step 1 -->
                <div class="flex flex-col items-center flex-1 text-center wizard-step-trigger cursor-pointer" data-target="#custom">
                    <div class="wizard-step-btn active-step pointer-events-none">1</div>
                    <span class="step-label text-[10px] sm:text-xs font-bold mt-2 text-gray-700">Data Customer</span>
                </div>
                <!-- Step 2 -->
                <div class="flex flex-col items-center flex-1 text-center wizard-step-trigger cursor-pointer" data-target="#unit">
                    <div class="wizard-step-btn inactive-step pointer-events-none">2</div>
                    <span class="step-label text-[10px] sm:text-xs font-semibold mt-2 text-gray-500">Data Unit</span>
                </div>
                <!-- Step 3 -->
                <div class="flex flex-col items-center flex-1 text-center wizard-step-trigger cursor-pointer" data-target="#kelket">
                    <div class="wizard-step-btn inactive-step pointer-events-none">3</div>
                    <span class="step-label text-[10px] sm:text-xs font-semibold mt-2 text-gray-500">Keluhan dan Keterangan</span>
                </div>
            </div>
        </div>

        <form id="transForm" novalidate method="post" action="{{ route('quickservice.save_trans') }}" onsubmit="return submitTransForm(this);">
            @csrf
            <div class="tab-content mt-6">
                <!-- STEP 1: Data Customer -->
                <div class="tab-content__pane active" id="custom">
                    <div class="font-bold text-sm text-gray-700 border-b border-gray-100 pb-2 mb-4">Data Customer</div>
                    <div class="grid grid-cols-12 gap-5">
                        <!-- Left Side -->
                        <div class="col-span-12 md:col-span-6 flex flex-col gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Nama <span class="text-red-500">*</span></label>
                                <input type="text" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="nama" required placeholder="Masukan nama customer">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Alamat</label>
                                <textarea class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="alamat" rows="4" placeholder="Masukan alamat"></textarea>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Tanggal Lahir</label>
                                <input type="date" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="cos_tgl_lahir">
                            </div>
                        </div>
                        <!-- Right Side -->
                        <div class="col-span-12 md:col-span-6 flex flex-col gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">No Tlep <span class="text-red-500">*</span></label>
                                <input type="number" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="tlp" required placeholder="Masukan no tlep customer">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Cabang</label>
                                <select class="input w-full border border-gray-300 rounded bg-white px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="cabang">
                                    <option value="Tegal" selected>Tegal</option>
                                    <option value="Cibubur">Cibubur</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-2 mt-8 pt-4 border-t border-gray-100">
                        <button type="button" data-dismiss="modal" class="button border text-gray-700 text-xs px-4 py-2 rounded">Cancel</button>
                        <button type="button" class="button bg-indigo-900 text-white text-xs px-4 py-2 rounded next-step shadow" data-next="#unit">Next</button>
                    </div>
                </div>

                <!-- STEP 2: Data Unit -->
                <div class="tab-content__pane" id="unit" style="display: none;">
                    <div class="font-bold text-sm text-gray-700 border-b border-gray-100 pb-2 mb-4">Data Unit</div>
                    <div class="grid grid-cols-12 gap-5">
                        <!-- Left Side -->
                        <div class="col-span-12 md:col-span-6 flex flex-col gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Status</label>
                                <select class="input w-full border border-gray-300 rounded bg-white px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="status">
                                     <option value="">-</option>
                                     <option value="CID">CID</option>
                                     <option value="IW">IW</option>
                                     <option value="OOW">OOW</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Merk <span class="text-red-500">*</span></label>
                                <input type="text" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="type" required placeholder="Masukan type unit">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">No seri</label>
                                <input type="text" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="seri" placeholder="Masukan model unit">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Tipe Password</label>
                                <div class="flex items-center gap-4 mt-2">
                                    <label class="flex items-center text-xs font-semibold text-gray-700 cursor-pointer">
                                        <input type="radio" name="pswd_type" value="text" checked class="mr-1.5 focus:ring-blue-500" onchange="togglePswd('text')"> Text
                                    </label>
                                    <label class="flex items-center text-xs font-semibold text-gray-700 cursor-pointer">
                                        <input type="radio" name="pswd_type" value="pin" class="mr-1.5 focus:ring-blue-500" onchange="togglePswd('pin')"> PIN
                                    </label>
                                    <label class="flex items-center text-xs font-semibold text-gray-700 cursor-pointer">
                                        <input type="radio" name="pswd_type" value="pattern_desc" class="mr-1.5 focus:ring-blue-500" onchange="togglePswd('desc')"> Pola
                                    </label>
                                </div>
                            </div>
                            <!-- Conditional: Password Text -->
                            <div id="pswd_text_div">
                                <label class="text-xs font-semibold text-gray-600 block mb-1" id="qs_pswd_label">Password Text</label>
                                <input type="text" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="pswd" id="qs_pswd_input" placeholder="Masukan password text">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Asesoris</label>
                                <input type="text" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="asesoris" placeholder="Masukan asesoris">
                            </div>
                        </div>
                        <!-- Right Side -->
                        <div class="col-span-12 md:col-span-6 flex flex-col gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Device</label>
                                <input type="text" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="device" placeholder="Masukan device">
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Model</label>
                                <input type="text" class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="model" placeholder="Masukan model unit">
                            </div>
                        </div>

                        <!-- Bagian Pola: Full-Width (Gambar Pola di Kiri & Keterangan di Kanan) -->
                        <div class="col-span-12" id="pswd_desc_div" style="display: none;">
                            <div class="grid grid-cols-12 gap-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                                <!-- Kolom Kiri: Gambar Pola Interaktif -->
                                <div class="col-span-12 md:col-span-6 flex flex-col items-center justify-center p-4 bg-[#111317] rounded-xl border border-gray-800 shadow-lg">
                                    <div class="flex items-center justify-between w-full mb-3 px-1">
                                        <span class="font-semibold text-xs text-gray-200 flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                                            Gambar Pola (9 Titik)
                                        </span>
                                        <button type="button" id="btnClearQSPattern" class="text-xs text-red-400 hover:text-red-300 font-semibold flex items-center gap-1 bg-red-500/15 hover:bg-red-500/25 px-2.5 py-1 rounded-md transition-all">
                                            <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Reset Pola
                                        </button>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <canvas id="qsPatternCanvas" width="220" height="220" style="touch-action: none; background: #181b1f; cursor: crosshair; border-radius: 14px;" class="border border-gray-700/80 shadow-inner"></canvas>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-3 text-center">Tarik garis menghubungkan titik-titik untuk membentuk pola</p>
                                    <input type="hidden" name="pswd_canvas" id="qs_pswd_canvas">
                                </div>

                                <!-- Kolom Kanan: Keterangan Pola -->
                                <div class="col-span-12 md:col-span-6 flex flex-col justify-between">
                                    <div>
                                        <label class="block font-semibold text-xs text-gray-700 mb-1">Keterangan Pola</label>
                                        <p class="text-[11px] text-gray-500 mb-2">Tuliskan deskripsi pola (contoh: bentuk huruf L, Z, atau urutan nomor)</p>
                                        <textarea class="input w-full border border-gray-300 rounded-lg p-2.5 text-sm" name="pswd_desc" id="qs_pswd_desc" rows="5" placeholder="Misal: Bentuk huruf L dari kiri atas turun ke bawah lalu ke kanan"></textarea>
                                    </div>
                                    <div class="mt-2 text-[11px] text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-2.5">
                                        <div class="font-semibold text-blue-800 mb-0.5">ℹ️ Petunjuk:</div>
                                        Saat menggambar pola pada canvas di sebelah kiri, gambar pola otomatis terekam dan urutan nomor titik akan otomatis terisi pada keterangan ini.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between gap-2 mt-8 pt-4 border-t border-gray-100">
                        <button type="button" class="button border text-gray-700 text-xs px-4 py-2 rounded prev-step" data-prev="#custom">Back</button>
                        <button type="button" class="button bg-indigo-900 text-white text-xs px-4 py-2 rounded next-step shadow" data-next="#kelket">Next</button>
                    </div>
                </div>

                <!-- STEP 3: Keluhan & Keterangan -->
                <div class="tab-content__pane" id="kelket" style="display: none;">
                    <div class="font-bold text-sm text-gray-700 border-b border-gray-100 pb-2 mb-4">Keluhan dan Keterangan</div>
                    <div class="grid grid-cols-12 gap-5">
                        <div class="col-span-12 flex flex-col gap-4">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Keluhan <span class="text-red-500">*</span></label>
                                <textarea class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="keluhan" rows="3" placeholder="Masukan keluhan"></textarea>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 block mb-1">Keterangan</label>
                                <textarea class="input w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" name="ket" rows="3" placeholder="Masukan keterangan tambahan"></textarea>
                            </div>
                            <div>
                                <label class="flex items-center text-xs font-semibold text-gray-700 cursor-pointer mt-2">
                                    <input type="checkbox" name="is_quick_service" value="1" checked class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"> Quick Service
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center mt-8 pt-4 border-t border-gray-100">
                        <button type="button" class="button border text-gray-700 text-xs px-4 py-2 rounded prev-step" data-prev="#unit">Back</button>
                        <div class="flex gap-2">
                            <button type="button" data-dismiss="modal" class="button text-gray-500 hover:text-gray-700 text-xs px-4 py-2">Cancel</button>
                            <button type="submit" id="btnSimpanQS" class="button bg-indigo-900 text-white text-xs px-6 py-2 rounded font-semibold shadow">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>            
    </div>
</div>

<script>
// Define activateTab globally immediately as a fail-safe
window.activateTab = function(tabId) {
    try {
        console.log('Activating tab:', tabId);
        // Toggle tab content panes
        if (typeof jQuery !== 'undefined') {
            jQuery('.tab-content__pane').removeClass('active').hide();
            jQuery(tabId).addClass('active').fadeIn(200);

            // Update step button styles and label colors inside wizard-step-trigger
            jQuery('.wizard-step-trigger').each(function() {
                var btnTarget = jQuery(this).attr('data-target');
                var btn = jQuery(this).find('.wizard-step-btn');
                var label = jQuery(this).find('.step-label');
                if (btnTarget === tabId) {
                    btn.addClass('active-step').removeClass('inactive-step');
                    label.removeClass('text-gray-500 font-semibold').addClass('text-gray-700 font-bold');
                } else {
                    btn.removeClass('active-step').addClass('inactive-step');
                    label.removeClass('text-gray-700 font-bold').addClass('text-gray-500 font-semibold');
                }
            });
        } else {
            // Vanilla JS Fallback
            var panes = document.querySelectorAll('.tab-content__pane');
            for (var i = 0; i < panes.length; i++) {
                panes[i].classList.remove('active');
                panes[i].style.display = 'none';
            }
            var targetPane = document.querySelector(tabId);
            if (targetPane) {
                targetPane.classList.add('active');
                targetPane.style.display = 'block';
            }
            
            // Stepper indicator styles fallback
            var triggers = document.querySelectorAll('.wizard-step-trigger');
            for (var j = 0; j < triggers.length; j++) {
                var trigger = triggers[j];
                var btnTarget = trigger.getAttribute('data-target');
                var btn = trigger.querySelector('.wizard-step-btn');
                var label = trigger.querySelector('.step-label');
                if (btn && label) {
                    if (btnTarget === tabId) {
                        btn.className = 'wizard-step-btn active-step pointer-events-none';
                        label.className = 'step-label text-[10px] sm:text-xs font-bold mt-2 text-gray-700';
                    } else {
                        btn.className = 'wizard-step-btn inactive-step pointer-events-none';
                        label.className = 'step-label text-[10px] sm:text-xs font-semibold mt-2 text-gray-500';
                    }
                }
            }
        }
    } catch (err) {
        console.error('JS Error in activateTab:', err);
    }
};

if (typeof jQuery !== 'undefined') {
    jQuery(function($) {
        // 1. Initialize DataTable on #quickServiceTable
        try {
            if ($.fn.DataTable) {
                if (!$.fn.DataTable.isDataTable('#quickServiceTable')) {
                    $('#quickServiceTable').DataTable({
                        responsive: true,
                        autoWidth: false,
                        paging: true,
                        info: true,
                        lengthMenu: [10, 25, 50, 100],
                        pageLength: 10,
                        language: {
                            search: "Search:",
                            lengthMenu: "Show _MENU_ entries",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            paginate: {
                                previous: "Previous",
                                next: "Next"
                            }
                        }
                    });
                }
            }
        } catch (e) {
            console.error('DataTable init error:', e);
        }

        // 2. Next Step Navigation (Event Delegation)
        $(document).on('click', '.next-step', function() {
            try {
                var nextTab = $(this).attr('data-next');
                console.log('Next step button clicked. Target tab:', nextTab);
                
                // Validate before moving from step 1
                if (nextTab === '#unit') {
                    const namaVal = $('#transForm [name="nama"]').val();
                    const tlpVal = $('#transForm [name="tlp"]').val();
                    if (!namaVal || !namaVal.trim()) {
                        $('#transForm [name="nama"]').focus();
                        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon isi Nama Customer di Step 1' });
                        return;
                    }
                    if (!tlpVal || !tlpVal.trim()) {
                        $('#transForm [name="tlp"]').focus();
                        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon isi No Telepon Customer di Step 1' });
                        return;
                    }
                }
                // Validate before moving from step 2
                if (nextTab === '#kelket') {
                    const typeVal = $('#transForm [name="type"]').val();
                    if (!typeVal || !typeVal.trim()) {
                        $('#transForm [name="type"]').focus();
                        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon isi Merk / Type Unit di Step 2' });
                        return;
                    }
                }
                activateTab(nextTab);
            } catch (err) {
                console.error('JS Error in next-step:', err);
                alert('Error navigating to next step: ' + err.message);
            }
        });

        // 3. Prev Step Navigation
        $(document).on('click', '.prev-step', function() {
            try {
                var prevTab = $(this).attr('data-prev');
                activateTab(prevTab);
            } catch (err) {
                console.error('JS Error in prev-step:', err);
            }
        });

        // 4. Stepper Indicator Click Navigation
        $(document).on('click', '.wizard-step-trigger', function(e) {
            e.preventDefault();
            try {
                var target = $(this).attr('data-target');
                if (target === '#unit' || target === '#kelket') {
                    const namaVal = $('#transForm [name="nama"]').val();
                    const tlpVal = $('#transForm [name="tlp"]').val();
                    if (!namaVal || !namaVal.trim() || !tlpVal || !tlpVal.trim()) {
                        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon lengkapi Data Customer di Step 1 terlebih dahulu' });
                        activateTab('#custom');
                        return;
                    }
                }
                if (target === '#kelket') {
                    const typeVal = $('#transForm [name="type"]').val();
                    if (!typeVal || !typeVal.trim()) {
                        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon lengkapi Data Unit di Step 2 terlebih dahulu' });
                        activateTab('#unit');
                        return;
                    }
                }
                activateTab(target);
            } catch (err) {
                console.error('JS Error in step trigger:', err);
            }
        });
    });
} else {
    // Pure Vanilla JS Fallback event handling
    document.addEventListener('click', function(e) {
        var nextBtn = e.target.closest('.next-step');
        if (nextBtn) {
            var nextTab = nextBtn.getAttribute('data-next');
            if (nextTab === '#unit') {
                var nama = document.querySelector('#transForm [name="nama"]');
                var tlp = document.querySelector('#transForm [name="tlp"]');
                if (!nama || !nama.value.trim() || !tlp || !tlp.value.trim()) {
                    alert('Mohon lengkapi Data Customer di Step 1');
                    return;
                }
            }
            if (nextTab === '#kelket') {
                var type = document.querySelector('#transForm [name="type"]');
                if (!type || !type.value.trim()) {
                    alert('Mohon lengkapi Data Unit di Step 2');
                    return;
                }
            }
            activateTab(nextTab);
        }

        var prevBtn = e.target.closest('.prev-step');
        if (prevBtn) {
            var prevTab = prevBtn.getAttribute('data-prev');
            activateTab(prevTab);
        }

        var trigger = e.target.closest('.wizard-step-trigger');
        if (trigger) {
            e.preventDefault();
            var target = trigger.getAttribute('data-target');
            if (target === '#unit' || target === '#kelket') {
                var nama = document.querySelector('#transForm [name="nama"]');
                var tlp = document.querySelector('#transForm [name="tlp"]');
                if (!nama || !nama.value.trim() || !tlp || !tlp.value.trim()) {
                    alert('Mohon lengkapi Data Customer di Step 1');
                    activateTab('#custom');
                    return;
                }
            }
            if (target === '#kelket') {
                var type = document.querySelector('#transForm [name="type"]');
                if (!type || !type.value.trim()) {
                    alert('Mohon lengkapi Data Unit di Step 2');
                    activateTab('#unit');
                    return;
                }
            }
            activateTab(target);
        }
    });
}

let qsPatternLockInstance = null;

function initQSPatternLock() {
    if (!qsPatternLockInstance) {
        qsPatternLockInstance = new PatternLock('qsPatternCanvas', 'qs_pswd_canvas', 'qs_pswd_desc', 'btnClearQSPattern');
    } else {
        qsPatternLockInstance.draw();
    }
}

function togglePswd(type) {
    const textDiv = $('#pswd_text_div');
    const descDiv = $('#pswd_desc_div');
    const labelElem = $('#qs_pswd_label');
    const inputElem = $('#qs_pswd_input');

    if (type === 'text') {
        textDiv.show();
        descDiv.hide();
        if (labelElem.length) labelElem.text('Password Text');
        if (inputElem.length) {
            inputElem.attr('placeholder', 'Masukan password text');
            inputElem.removeAttr('inputmode');
        }
    } else if (type === 'pin') {
        textDiv.show();
        descDiv.hide();
        if (labelElem.length) labelElem.text('Password PIN');
        if (inputElem.length) {
            inputElem.attr('placeholder', 'Masukan PIN angka (misal: 1234 / 123456)');
            inputElem.attr('inputmode', 'numeric');
        }
    } else {
        textDiv.hide();
        descDiv.show();
        setTimeout(initQSPatternLock, 50);
    }
}

class PatternLock {
    constructor(canvasId, hiddenInputId, descInputId = null, clearBtnId = null) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;
        this.ctx = this.canvas.getContext('2d');
        this.hiddenInput = document.getElementById(hiddenInputId);
        this.descInput = descInputId ? document.getElementById(descInputId) : null;
        this.clearBtn = clearBtnId ? document.getElementById(clearBtnId) : null;
        
        this.rows = 3;
        this.cols = 3;
        this.dots = [];
        this.selectedDots = [];
        this.isDrawing = false;
        this.currentPos = null;
        
        this.init();
    }
    
    init() {
        this.calculateDots();
        this.draw();
        this.bindEvents();
        
        if (this.clearBtn) {
            this.clearBtn.onclick = (e) => {
                e.preventDefault();
                this.reset();
            };
        }
    }
    
    calculateDots() {
        this.dots = [];
        const width = this.canvas.width;
        const height = this.canvas.height;
        const xStep = width / (this.cols + 1);
        const yStep = height / (this.rows + 1);
        
        let id = 1;
        for (let r = 0; r < this.rows; r++) {
            for (let c = 0; c < this.cols; c++) {
                this.dots.push({
                    id: id++,
                    x: (c + 1) * xStep,
                    y: (r + 1) * yStep,
                    radius: 8
                });
            }
        }
    }
    
    draw() {
        const ctx = this.ctx;
        // Dark sleek background matching user screenshot
        ctx.fillStyle = '#181b1f';
        ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Draw connecting lines
        if (this.selectedDots.length > 0) {
            ctx.beginPath();
            ctx.strokeStyle = '#38bdf8'; // Glowing neon cyan
            ctx.lineWidth = 4.5;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            
            ctx.moveTo(this.selectedDots[0].x, this.selectedDots[0].y);
            for (let i = 1; i < this.selectedDots.length; i++) {
                ctx.lineTo(this.selectedDots[i].x, this.selectedDots[i].y);
            }
            
            if (this.isDrawing && this.currentPos) {
                ctx.lineTo(this.currentPos.x, this.currentPos.y);
            }
            ctx.stroke();
        }
        
        // Draw 9 dots (3x3)
        this.dots.forEach(dot => {
            const isSelected = this.selectedDots.some(d => d.id === dot.id);
            
            if (isSelected) {
                // Glowing cyan ring
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, 22, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(56, 189, 248, 0.25)';
                ctx.fill();
                
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, 22, 0, Math.PI * 2);
                ctx.strokeStyle = '#38bdf8';
                ctx.lineWidth = 2;
                ctx.stroke();
                
                // Order number indicator
                const order = this.selectedDots.findIndex(d => d.id === dot.id) + 1;
                ctx.font = 'bold 11px sans-serif';
                ctx.fillStyle = '#bae6fd';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(order, dot.x + 15, dot.y - 15);
            }
            
            // Dot center (white dot like screenshot 2)
            ctx.beginPath();
            ctx.arc(dot.x, dot.y, isSelected ? 8 : 6.5, 0, Math.PI * 2);
            ctx.fillStyle = isSelected ? '#38bdf8' : '#ffffff';
            ctx.fill();
        });
    }
    
    getCanvasPoint(e) {
        const rect = this.canvas.getBoundingClientRect();
        const clientX = (e.touches && e.touches.length > 0) ? e.touches[0].clientX : e.clientX;
        const clientY = (e.touches && e.touches.length > 0) ? e.touches[0].clientY : e.clientY;
        const scaleX = this.canvas.width / rect.width;
        const scaleY = this.canvas.height / rect.height;
        return {
            x: (clientX - rect.left) * scaleX,
            y: (clientY - rect.top) * scaleY
        };
    }
    
    checkDot(pos) {
        const hitRadius = 24;
        for (const dot of this.dots) {
            const dist = Math.hypot(dot.x - pos.x, dot.y - pos.y);
            if (dist <= hitRadius) {
                if (!this.selectedDots.some(d => d.id === dot.id)) {
                    this.selectedDots.push(dot);
                    return true;
                }
            }
        }
        return false;
    }
    
    bindEvents() {
        const start = (e) => {
            e.preventDefault();
            this.isDrawing = true;
            this.selectedDots = [];
            const pos = this.getCanvasPoint(e);
            this.currentPos = pos;
            this.checkDot(pos);
            this.draw();
        };
        
        const move = (e) => {
            if (!this.isDrawing) return;
            e.preventDefault();
            const pos = this.getCanvasPoint(e);
            this.currentPos = pos;
            this.checkDot(pos);
            this.draw();
        };
        
        const end = (e) => {
            if (!this.isDrawing) return;
            this.isDrawing = false;
            this.currentPos = null;
            this.draw();
            this.save();
        };
        
        this.canvas.addEventListener('mousedown', start);
        window.addEventListener('mousemove', move);
        window.addEventListener('mouseup', end);
        
        this.canvas.addEventListener('touchstart', start, { passive: false });
        window.addEventListener('touchmove', move, { passive: false });
        window.addEventListener('touchend', end);
    }
    
    save() {
        if (this.selectedDots.length > 0) {
            const dataUrl = this.canvas.toDataURL('image/png');
            if (this.hiddenInput) {
                this.hiddenInput.value = dataUrl;
            }
            if (this.descInput && (!this.descInput.value.trim() || this.descInput.value.startsWith('Pola: '))) {
                const sequence = this.selectedDots.map(d => d.id).join(' -> ');
                this.descInput.value = 'Pola: ' + sequence;
            }
        }
    }
    
    reset() {
        this.selectedDots = [];
        this.isDrawing = false;
        this.currentPos = null;
        if (this.hiddenInput) this.hiddenInput.value = '';
        if (this.descInput && this.descInput.value.startsWith('Pola: ')) {
            this.descInput.value = '';
        }
        this.draw();
    }
}

function submitTransForm(form) {
    // Validate Step 1: Data Customer
    const nama = form.querySelector('[name="nama"]');
    const tlp = form.querySelector('[name="tlp"]');
    if (!nama || !nama.value.trim()) {
        activateTab('#custom');
        nama.focus();
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon isi Nama Customer di Step 1' });
        return false;
    }
    if (!tlp || !tlp.value.trim()) {
        activateTab('#custom');
        tlp.focus();
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon isi No Telepon Customer di Step 1' });
        return false;
    }

    // Validate Step 2: Data Unit
    const type = form.querySelector('[name="type"]');
    if (!type || !type.value.trim()) {
        activateTab('#unit');
        type.focus();
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon isi Merk / Type Unit di Step 2' });
        return false;
    }

    // Validate Step 3: Keluhan
    const keluhan = form.querySelector('[name="keluhan"]');
    if (!keluhan || !keluhan.value.trim()) {
        activateTab('#kelket');
        keluhan.focus();
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Mohon isi Keluhan di Step 3' });
        return false;
    }

    var btn = form.querySelector('#btnSimpanQS');
    if (btn) {
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';
    }

    var formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok || data.status !== 'success') {
            throw new Error(data.message || 'Terjadi kesalahan pada respon server');
        }
        return data;
    })
    .then(data => {
        if (typeof window.closeAppModal === 'function') {
            window.closeAppModal('#add-new-costom');
        } else {
            $('#add-new-costom').modal('hide');
        }
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Transaksi Quick Service berhasil dibuat!',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({ icon: 'error', title: 'Gagal', text: error.message || 'Terjadi kesalahan jaringan' });
        if (btn) {
            btn.disabled = false;
            btn.innerText = 'Simpan';
        }
    });
    return false;
}

function sendToWA(url, hp, nama, kode, trans_kode) {
    if (hp.startsWith('0')) {
        hp = '62' + hp.substring(1);
    }
    hp = hp.replace(/\D/g, '');
    let message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami. Untuk Mengecek Transaksi QS Anda Silahkan Login menggunakan username: ${kode}, password: ${kode}.`;
    const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
    window.open(waUrl, '_blank');
}
</script>
@endsection

