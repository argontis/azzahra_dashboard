@extends('layouts.app')

@section('content')
<!-- Header Area -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Customer</h1>
        <p>Antrean Transaksi Service Customer</p>
    </div>
    <div class="header-actions">
        <form id="serviceAntreanSearchForm" action="{{ route('service.antrean', $current_status) }}" method="GET" style="margin: 0;">
            <div class="search-input-wrapper">
                <i data-feather="search" class="search-icon"></i>
                <input type="text" name="search" id="serviceAntreanSearchInput" class="search-input" placeholder="Search..." value="{{ request('search') }}">
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
    <div class="sukses" data-sukses="{{ session('sukses') }}"></div>
    <div class="gagal" data-gagal="{{ session('gagal') }}"></div>
    
    <div class="intro-y flex flex-col sm:flex-row items-center mt-2 mb-3">
        <h2 class="text-lg font-medium mr-auto">
            Antrean Transaksi: {{ ucfirst($current_status) }}
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            @if($current_status == 'baru')
            <a role="button" class="button text-white bg-theme-1 shadow-md mr-2" data-toggle="modal" data-target="#add-new-costom">
                Buat Transaksi Baru
            </a>
            @endif
        </div>
    </div>
    
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-3">
        <div class="col-span-12 lg:col-span-3 xxl:col-span-2">
            <div class="intro-y box p-3 lg:p-5">
                <div class="flex flex-row lg:flex-col overflow-x-auto lg:overflow-visible gap-2 pb-1 lg:pb-0 filter-pill-bar">
                    <a href="{{ route('service.antrean', 'baru') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'baru' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}">
                        <i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi Baru
                    </a>
                    <a href="{{ route('service.antrean', 'proses') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'proses' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi Diproses 
                    </a>
                    <a href="{{ route('service.antrean', 'konfirmasi') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'konfirmasi' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}">
                        <i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfirmasi
                    </a>
                    <a href="{{ route('service.antrean', 'pelunasan') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'pelunasan' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Pelunasan 
                    </a>
                    <a href="{{ route('service.antrean', 'lunas') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'lunas' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="users"></i> Selesai (Lunas)
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10">
            <div class="intro-y datatable-wrapper box p-5">
                <div class="overflow-x-auto">
                    <table class="table table-report table-report--bordered w-full">
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
                            @foreach ($transaksis as $index => $row)
                                @php
                                    $customer = $row->customer;
                                    $isPriority = $customer && (($customer->cos_tier === 'prioritas') || ($customer->cos_score >= 5) || ($customer->total_transaksi >= 5));
                                    $isLoyal = $customer && (($customer->cos_tier === 'loyal') || ($customer->cos_score >= 3 && !$isPriority));
                                    $hp = $customer->cos_hp ?? '';
                                    $masked_hp = strlen($hp) > 4 ? substr($hp, 0, -4) . 'XXXX' : $hp;
                                @endphp
                                <tr class="{{ $isPriority ? 'bg-amber-50/50 hover:bg-amber-50/80 border-l-4 border-l-amber-500' : '' }}">
                                    <td class="text-center border-b whitespace-no-wrap">{{ $transaksis->firstItem() + $index }}</td>
                                    <td class="text-center border-b whitespace-no-wrap font-medium">{{ $row->trans_kode }}</td>
                                    <td class="border-b whitespace-no-wrap">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-medium text-gray-800">{{ $customer->cos_nama ?? '-' }}</span>
                                            @if($isPriority)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-xs" title="Pelanggan Prioritas (VIP)">
                                                    👑 PRIORITAS
                                                </span>
                                            @elseif($isLoyal)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                                    ⭐ Loyal
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="border-b whitespace-no-wrap text-gray-600">{{ $customer->cos_alamat ?? '-' }}</td>
                                    <td class="text-center border-b whitespace-no-wrap font-medium text-gray-700">
                                        {{ $masked_hp }}
                                    </td>
                                    <td class="text-center border-b whitespace-no-wrap">
                                        <div class="flex sm:justify-center items-center">
                                            <a href="{{ route('service.proses', $row->trans_kode) }}" class="button px-3 py-1.5 mr-1 mb-2 bg-theme-9 text-white tooltip flex items-center justify-center font-medium shadow-sm" title="Proses">
                                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Proses
                                            </a>
                                            @if($current_status == 'konfirmasi')
                                                <a href="{{ route('service.batal_transaksi', $row->trans_kode) }}" class="button px-2 mr-1 mb-2 bg-theme-6 text-white" onclick="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                                    Batal
                                                </a>
                                            @elseif($current_status == 'pelunasan')
                                                <a href="{{ route('service.return_pembayaran', $row->trans_kode) }}" class="button px-2 mr-1 mb-2 bg-theme-6 text-white" onclick="return confirm('Yakin ingin return pembayaran ini?')">
                                                    Return
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.cetak.print_1', $row->trans_kode) }}" target="_blank" class="button px-2 mr-1 mb-2 bg-theme-6 text-white tooltip" title="Print TTS (PDF)">
                                                <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="printer" class="w-4 h-4"></i> </span>
                                            </a>
                                            <a role="button" onclick="sendToWA('{{ url('/') }}', '{{ $row->customer->cos_hp ?? '' }}', '{{ $row->customer->cos_nama ?? '' }}', '{{ $row->cos_kode }}', '{{ $row->trans_kode }}')" class="button px-2 mr-1 mb-2 bg-theme-9 text-white tooltip" title="Kirim WA">
                                                <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="message-circle" class="w-4 h-4"></i> </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="noSearchResultRow" style="display: none;">
                                <td colspan="6" class="text-center py-6 text-gray-500">
                                    <i data-feather="search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                                    <p>Tidak ada transaksi yang cocok dengan pencarian.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $transaksis->links() }}
                </div>
            </div>
        </div>
    </div>    
</div>

@if($current_status == 'baru')
<!-- modal tambah customer -->
<div class="modal flex items-center justify-center" id="add-new-costom" style="z-index: 1050;">
    <div class="modal__content modal__content--xl p-10 intro-y box sm:py-15" style="max-height: 80vh; overflow-y: auto;">
        <div class="nav-tabs wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10 ">
                <a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1 active" data-toggle="tab" data-target="#custom">1</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Customer</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <a href="#" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200" data-toggle="tab" data-target="#unit">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Unit</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <a href="#" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200" data-toggle="tab" data-target="#kelket">3</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Keluhan & Keterangan</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-2"></div>
        </div>
        <form id="transForm" novalidate method="post" action="{{ route('service.save_trans') }}" onsubmit="return submitTransForm(this);">
            @csrf
            <div class="tab-content">
                <div class="tab-content__pane active" id="custom">
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
                        <div class="font-medium text-base">Data Customer</div>
                        <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Nama <span class="text-red-500">*</span></div>
                                <input type="text" class="input w-full border flex-1" name="nama" required placeholder="Masukan nama customer">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">No Telepon <span class="text-red-500">*</span></div>
                                <input type="number" class="input w-full border flex-1" name="tlp" required placeholder="Masukan no tlep customer">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Alamat</div>
                                <textarea class="input w-full border mt-2 flex-1" name="alamat"></textarea>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Cabang</div>
                                <select class="input w-full border mt-2 flex-1" name="cabang">
                                    <option value="Tegal" selected>Tegal</option>
                                    <option value="Cibubur">Cibubur</option>
                                </select>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Tanggal Lahir</div>
                                <input type="date" class="input w-full border flex-1" name="cos_tgl_lahir">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="unit">
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
                        <div class="font-medium text-base">Data Unit</div>
                        <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Status Garansi</div>
                                <select class="input w-full border flex-1" name="status">
                                     <option value="">-</option>
                                     <option value="CID">CID</option>
                                     <option value="IW">IW</option>
                                     <option value="OOW">OOW</option>
                                 </select>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Device</div>
                                <input type="text" class="input w-full border flex-1" name="device" placeholder="Masukan device">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Merk / Type <span class="text-red-500">*</span></div>
                                <input type="text" class="input w-full border flex-1" name="type" required placeholder="Masukan type unit">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Model</div>
                                <input type="text" class="input w-full border flex-1" name="model" placeholder="Masukan model unit">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">No Seri</div>
                                <input type="text" class="input w-full border flex-1" name="seri" placeholder="Masukan no seri">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2 font-medium">Tipe Password</div>
                                <div class="flex items-center space-x-4 mt-2">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="pswd_type" value="text" checked class="mr-2" onchange="togglePswd('text')"> Text
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="pswd_type" value="pattern_desc" class="mr-2" onchange="togglePswd('desc')"> Pola
                                    </label>
                                </div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6 pswd_text" id="pswd_text_div">
                                <div class="mb-2 font-medium">Password Text</div>
                                <input type="text" class="input w-full border flex-1" name="pswd" placeholder="Masukan password text">
                            </div>
                            <!-- Bagian Pola: Gambar Pola di Kiri & Keterangan Pola di Kanan -->
                            <div class="intro-y col-span-12 pswd_pattern_desc" id="pswd_desc_div" style="display: none;">
                                <div class="grid grid-cols-12 gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-dark-1">
                                    <!-- Kolom Kiri: Gambar Pola Interaktif -->
                                    <div class="col-span-12 sm:col-span-6 flex flex-col items-center justify-center p-4 bg-[#111317] rounded-xl border border-gray-800 shadow-lg">
                                        <div class="flex items-center justify-between w-full mb-3 px-1">
                                            <span class="font-semibold text-xs text-gray-200 flex items-center gap-1.5">
                                                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                                                Gambar Pola (9 Titik)
                                            </span>
                                            <button type="button" id="btnClearPattern" class="text-xs text-red-400 hover:text-red-300 font-semibold flex items-center gap-1 bg-red-500/15 hover:bg-red-500/25 px-2.5 py-1 rounded-md transition-all">
                                                <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                Reset Pola
                                            </button>
                                        </div>
                                        <div class="relative flex justify-center">
                                            <canvas id="patternCanvas" width="220" height="220" style="touch-action: none; background: #181b1f; cursor: crosshair; border-radius: 14px;" class="border border-gray-700/80 shadow-inner"></canvas>
                                        </div>
                                        <p class="text-[11px] text-gray-400 mt-3 text-center">Tarik garis menghubungkan titik-titik untuk membentuk pola</p>
                                        <input type="hidden" name="pswd_canvas" id="pswd_canvas">
                                    </div>

                                    <!-- Kolom Kanan: Keterangan Pola -->
                                    <div class="col-span-12 sm:col-span-6 flex flex-col justify-between">
                                        <div>
                                            <label class="block font-semibold text-xs text-gray-700 mb-1">Keterangan Pola</label>
                                            <p class="text-[11px] text-gray-500 mb-2">Tuliskan deskripsi pola (contoh: huruf L, segitiga, atau urutan nomor)</p>
                                            <textarea class="input w-full border rounded-lg p-2.5 text-sm" name="pswd_desc" id="pswd_desc" rows="5" placeholder="Misal: Bentuk huruf L dari kiri atas turun ke bawah lalu ke kanan"></textarea>
                                        </div>
                                        <div class="mt-2 text-[11px] text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-2.5">
                                            <div class="font-semibold text-blue-800 mb-0.5">ℹ️ Petunjuk:</div>
                                            Saat menggambar pola pada canvas di sebelah kiri, gambar pola otomatis terekam dan urutan nomor titik akan otomatis terisi pada keterangan ini.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="intro-y col-span-12">
                                <div class="mb-2">Asesoris</div>
                                <textarea class="input w-full border mt-2 flex-1" name="asesoris"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="kelket">
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
                        <div class="font-medium text-base">Keluhan dan Keterangan</div>
                        <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                            <div class="intro-y col-span-12">
                                <div class="mb-2">Keluhan <span class="text-red-500">*</span></div>
                                <textarea class="input w-full border mt-2 flex-1" name="keluhan" required></textarea>
                            </div>
                            <div class="intro-y col-span-12">
                                <div class="mb-2">Keterangan Tambahan</div>
                                <textarea class="input w-full border mt-2 flex-1" name="ket"></textarea>
                            </div>
                            <!-- Jika Quick Service, bisa tambahkan checkbox is_quick_service di sini -->
                            @if(Route::is('quickservice.*'))
                            <input type="hidden" name="is_quick_service" value="1">
                            @endif
                        </div>
                    </div>
                    <div class="px-5 py-3 text-right border-t border-gray-200">
                        <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
                        <button type="submit" id="btnSimpanTrans" class="button w-20 bg-theme-1 text-white">Simpan</button>
                    </div>
                </div>
            </div>
        </form>            
    </div>
</div>

<script>
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

    let patternLockInstance = null;

    function initPatternLock() {
        if (!patternLockInstance) {
            patternLockInstance = new PatternLock('patternCanvas', 'pswd_canvas', 'pswd_desc', 'btnClearPattern');
        } else {
            patternLockInstance.draw();
        }
    }

    function togglePswd(type) {
        if (type === 'text') {
            document.getElementById('pswd_text_div').style.display = 'block';
            document.getElementById('pswd_desc_div').style.display = 'none';
        } else {
            document.getElementById('pswd_text_div').style.display = 'none';
            document.getElementById('pswd_desc_div').style.display = 'block';
            setTimeout(initPatternLock, 50);
        }
    }

    function activateTab(tabId) {
        var $tabLink = $('.wizard a[data-target="' + tabId + '"]');
        if ($tabLink.length) {
            $tabLink.trigger('click');
        }
        if (tabId === '#unit') {
            setTimeout(function() {
                var pswdType = document.querySelector('input[name="pswd_type"]:checked');
                if (pswdType && pswdType.value === 'pattern_desc') {
                    initPatternLock();
                }
            }, 100);
        }
    }

    $(document).ready(function() {
        $('.wizard a[data-target="#unit"]').on('click', function() {
            setTimeout(function() {
                var pswdType = document.querySelector('input[name="pswd_type"]:checked');
                if (pswdType && pswdType.value === 'pattern_desc') {
                    initPatternLock();
                }
            }, 100);
        });
    });

    function submitTransForm(form) {
        // Validate Step 1: Data Customer
        const nama = form.querySelector('[name="nama"]');
        const tlp = form.querySelector('[name="tlp"]');
        if (!nama || !nama.value.trim()) {
            activateTab('#custom');
            nama.focus();
            alert('Mohon isi Nama Customer di Step 1 (Data Customer)');
            return false;
        }
        if (!tlp || !tlp.value.trim()) {
            activateTab('#custom');
            tlp.focus();
            alert('Mohon isi No Telepon Customer di Step 1 (Data Customer)');
            return false;
        }

        // Validate Step 2: Data Unit
        const type = form.querySelector('[name="type"]');
        if (!type || !type.value.trim()) {
            activateTab('#unit');
            type.focus();
            alert('Mohon isi Merk / Type Unit di Step 2 (Data Unit)');
            return false;
        }

        // Validate Step 3: Keluhan
        const keluhan = form.querySelector('[name="keluhan"]');
        if (!keluhan || !keluhan.value.trim()) {
            activateTab('#kelket');
            keluhan.focus();
            alert('Mohon isi Keluhan di Step 3 (Keluhan & Keterangan)');
            return false;
        }

        var btn = form.querySelector('#btnSimpanTrans');
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
            $('#add-new-costom').modal('hide');
            alert('DATA BERHASIL DI TAMBAHKAN');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Terjadi kesalahan jaringan');
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
        let message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami. Untuk Mengecek Transaksi Anda Silahkan Login menggunakan username: ${kode}, password: ${kode}.`;
        const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
    }
</script>
@endif

<script>
// Auto-submit service antrean search form when typing (debounced 400ms)
(function() {
    var searchInput = document.getElementById('serviceAntreanSearchInput');
    if (!searchInput) return;
    var debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            document.getElementById('serviceAntreanSearchForm').submit();
        }, 400);
    });
})();
</script>
@endsection
