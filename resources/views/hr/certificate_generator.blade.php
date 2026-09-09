@extends('layouts.app')

@section('content')
<!-- Google Fonts for Certificate Typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Cinzel:wght@600;700;800&family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,500;1,600&family=Great+Vibes&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300;1,400&family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600&display=swap" rel="stylesheet">
<!-- html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<header class="page-header print:hidden">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="award" class="w-6 h-6 inline-block mr-2 text-indigo-600"></i>Cetak Sertifikat Magang & Karyawan</h1>
        <p>Gunakan template sertifikat asli, ubah nama penerima, keterangan isi, serta tanggal penerbitan</p>
    </div>
    <div class="header-actions">
        <button type="button" onclick="printCertificate()" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg shadow-sm inline-flex items-center gap-2">
            <i data-feather="printer" class="w-4 h-4"></i> Cetak Sertifikat (A4)
        </button>
        <button type="button" onclick="downloadImage()" class="btn btn-outline-secondary bg-white hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg border border-gray-300 shadow-sm inline-flex items-center gap-2 ml-2">
            <i data-feather="download" class="w-4 h-4"></i> Unduh Gambar (PNG)
        </button>
    </div>
</header>

<div class="content-area py-4 print:p-0 print:m-0">
    <div class="grid grid-cols-12 gap-6 print:block">
        
        <!-- KOLOM KIRI: FORM PENGISIAN & PENGATURAN SPASI JARAK (Hidden saat print) -->
        <div class="col-span-12 lg:col-span-4 print:hidden space-y-4">
            
            <!-- Card 1: Input Nama Penerima -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 space-y-3">
                <div class="border-b border-gray-100 pb-2.5 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i data-feather="user" class="w-4 h-4 text-indigo-600"></i> 1. Nama Penerima
                    </h3>
                    <span class="text-[10px] bg-indigo-50 text-indigo-700 font-bold px-2 py-0.5 rounded">Bisa Ketik Bebas</span>
                </div>

                <!-- Input Nama Bebas / Sendiri -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Nama Lengkap / Nama Sendiri <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="inputRecipientName" 
                           class="w-full px-3 py-2 bg-gray-50 focus:bg-white border-2 border-indigo-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-100 rounded-lg text-sm font-bold text-gray-900 transition-all placeholder:font-normal placeholder:text-gray-400" 
                           placeholder="Ketik nama di sini..." 
                           value="Muhammad Rian Pratama"
                           autocomplete="off">
                </div>

                <!-- Pilihan Cepat dari Karyawan -->
                @if(isset($karyawan_list) && count($karyawan_list) > 0)
                <div>
                    <select onchange="if(this.value){ document.getElementById('inputRecipientName').value = this.value; updatePreview(); }" class="w-full px-2.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs text-gray-700 focus:border-indigo-500">
                        <option value="">-- Atau Pilih Cepat dari Karyawan --</option>
                        @foreach($karyawan_list as $kry)
                        <option value="{{ $kry->kry_nama }}">{{ $kry->kry_nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Pilihan Gaya Font & Ukuran Nama -->
                <div class="grid grid-cols-2 gap-2 pt-1">
                    <div>
                        <label class="block text-[11px] text-gray-600 mb-1">Font Nama</label>
                        <select id="selectFontFamily" onchange="updatePreview()" class="w-full px-2 py-1.5 bg-white border border-gray-300 rounded-lg text-xs text-gray-800 focus:border-indigo-500">
                            <option value="font-serif-lux" selected>Playfair Display</option>
                            <option value="font-cormorant">Cormorant Garamond</option>
                            <option value="font-cinzel">Cinzel</option>
                            <option value="font-script-alex">Alex Brush (Kaligrafi)</option>
                            <option value="font-script-vibes">Great Vibes</option>
                            <option value="font-montserrat">Montserrat</option>
                        </select>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="text-[11px] text-gray-600">Ukuran: <span id="fontSizeDisplay" class="font-bold text-indigo-600">36px</span></label>
                        </div>
                        <input type="range" id="inputFontSize" min="22" max="52" value="36" step="1" oninput="updatePreview()" class="w-full accent-indigo-600 cursor-pointer mt-1">
                    </div>
                </div>
            </div>

            <!-- Card 2: Ubah Keterangan / Deskripsi Capaian -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 space-y-3">
                <div class="border-b border-gray-100 pb-2.5 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i data-feather="file-text" class="w-4 h-4 text-indigo-600"></i> 2. Keterangan / Deskripsi
                    </h3>
                </div>

                <!-- Textarea Keterangan -->
                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">
                        Isi Teks Keterangan Sertifikat <span class="text-red-500">*</span>
                    </label>
                    <textarea id="inputDescription" rows="4" 
                              class="w-full px-3 py-2 bg-gray-50 focus:bg-white border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg text-xs font-normal text-gray-800 leading-relaxed transition" 
                              oninput="updatePreview()" 
                              placeholder="Masukkan teks keterangan...">Telah Menyelesaikan Rangkaian Program Magang di Authorized Bekasi Laptop Store (Tahun 2026) dengan capaian hasil yang baik. Berhasil mengaplikasikan pemahaman teknis dan operasional secara optimal serta berkontribusi positif terhadap kinerja tim selama masa magang.</textarea>
                </div>

                <!-- Template Keterangan Cepat -->
                <div>
                    <span class="block text-[11px] text-gray-500 mb-1.5 font-medium">Pilihan Template Keterangan Cepat:</span>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" onclick="setDescPreset('magang')" class="text-[11px] px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium rounded border border-indigo-200 transition">
                            Magang (Default)
                        </button>
                        <button type="button" onclick="setDescPreset('karyawan')" class="text-[11px] px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded border border-gray-200 transition">
                            Karyawan Terbaik
                        </button>
                        <button type="button" onclick="setDescPreset('pelatihan')" class="text-[11px] px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded border border-gray-200 transition">
                            Pelatihan Teknis
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3: Tanggal, Bulan & Tahun -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 space-y-3">
                <div class="border-b border-gray-100 pb-2.5 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i data-feather="calendar" class="w-4 h-4 text-indigo-600"></i> 3. Tanggal, Bulan & Tahun
                    </h3>
                    <button type="button" onclick="setTodayDate()" class="text-[11px] text-indigo-600 hover:underline font-bold">Hari Ini</button>
                </div>

                <!-- Date Picker & Kota -->
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-4">
                        <label class="block text-[11px] text-gray-600 mb-1">Kota</label>
                        <input type="text" id="inputCity" class="w-full px-2.5 py-1.5 bg-gray-50 focus:bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-800 focus:border-indigo-500" value="Tegal" oninput="buildDateString()">
                    </div>
                    <div class="col-span-8">
                        <label class="block text-[11px] text-gray-600 mb-1">Kalender</label>
                        <input type="date" id="inputDatePicker" class="w-full px-2.5 py-1.5 bg-gray-50 focus:bg-white border border-gray-300 rounded-lg text-xs text-gray-800 focus:border-indigo-500" value="2026-08-08" onchange="syncDatePickerToCustom()">
                    </div>
                </div>

                <!-- Dropdown Tanggal, Bulan, Tahun -->
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-3">
                        <label class="block text-[10px] text-gray-500 mb-0.5">Tgl</label>
                        <select id="selectDay" class="w-full px-1.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs text-gray-800 focus:border-indigo-500" onchange="buildDateString()">
                            @for($d = 1; $d <= 31; $d++)
                            <option value="{{ $d }}" {{ $d == 8 ? 'selected' : '' }}>{{ $d }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-span-5">
                        <label class="block text-[10px] text-gray-500 mb-0.5">Bulan</label>
                        <select id="selectMonth" class="w-full px-1.5 py-1.5 bg-white border border-gray-300 rounded-lg text-xs text-gray-800 focus:border-indigo-500" onchange="buildDateString()">
                            <option value="Januari">Januari</option>
                            <option value="Februari">Februari</option>
                            <option value="Maret">Maret</option>
                            <option value="April">April</option>
                            <option value="Mei">Mei</option>
                            <option value="Juni">Juni</option>
                            <option value="Juli">Juli</option>
                            <option value="Agustus" selected>Agustus</option>
                            <option value="September">September</option>
                            <option value="Oktober">Oktober</option>
                            <option value="November">November</option>
                            <option value="Desember">Desember</option>
                        </select>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-[10px] text-gray-500 mb-0.5">Tahun</label>
                        <input type="number" id="inputYear" class="w-full px-2 py-1.5 bg-white border border-gray-300 rounded-lg text-xs text-gray-800 focus:border-indigo-500" value="2026" min="2020" max="2035" oninput="buildDateString()">
                    </div>
                </div>

                <!-- Input Teks Lengkap Tanggal -->
                <div>
                    <label class="block text-[10px] text-gray-500 mb-0.5">Teks Lengkap Tanggal</label>
                    <input type="text" id="inputDateFullText" class="w-full px-2.5 py-1.5 bg-gray-50 focus:bg-white border border-gray-300 rounded-lg text-xs font-medium text-gray-800 focus:border-indigo-500" value="Tegal, 8 Agustus 2026" oninput="updatePreview()">
                </div>
            </div>

            <!-- Card 4: Kerapian Jarak, Spasi & Tata Letak (Atas, Bawah, Kanan, Kiri) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 space-y-3">
                <div class="border-b border-gray-100 pb-2.5 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800 text-xs uppercase tracking-wider flex items-center gap-2">
                        <i data-feather="sliders" class="w-4 h-4 text-indigo-600"></i> 4. Pengaturan Jarak & Spasi
                    </h3>
                    <button type="button" onclick="resetSpacingDefaults()" class="text-[11px] text-indigo-600 hover:underline font-bold">Reset Standar</button>
                </div>

                <!-- Font Style Bersama -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Gaya Font (Keterangan & Tanggal):</label>
                    <select id="selectBodyFontFamily" onchange="updatePreview()" class="w-full px-2 py-1.5 bg-white border border-indigo-200 rounded-lg text-xs font-medium text-indigo-900 focus:border-indigo-500">
                        <option value="font-cormorant" selected>Cormorant Garamond (Elegan & Sesuai Asli)</option>
                        <option value="font-merriweather">Merriweather (Serif Bersih)</option>
                        <option value="font-serif-lux">Playfair Display (Serif Formal)</option>
                        <option value="font-montserrat">Montserrat (Modern Sans-Serif)</option>
                        <option value="font-times">Times New Roman (Standar)</option>
                    </select>
                </div>

                <!-- Ukuran Font Keterangan & Tanggal (18px) -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-700">Ukuran Font (Keterangan & Tgl):</label>
                        <span id="bodyFontSizeDisplay" class="text-xs font-mono font-bold text-indigo-600">18 px</span>
                    </div>
                    <input type="range" id="inputBodyFontSize" min="14" max="24" value="18" step="1" oninput="updatePreview()" class="w-full accent-indigo-600 cursor-pointer">
                </div>

                <!-- Jarak Kiri-Kanan (Margin / Lebar Maksimum) -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-700">Lebar Keterangan (Jarak Kiri-Kanan):</label>
                        <span id="descWidthDisplay" class="text-xs font-mono text-gray-600">76 %</span>
                    </div>
                    <input type="range" id="inputDescWidth" min="60" max="90" value="76" step="1" oninput="updatePreview()" class="w-full accent-indigo-600 cursor-pointer">
                </div>

                <!-- Spasi Baris (Line Height Keterangan) -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-700">Spasi Antar Baris (Line Spacing):</label>
                        <span id="lineHeightDisplay" class="text-xs font-mono text-gray-600">1.60</span>
                    </div>
                    <input type="range" id="inputLineHeight" min="1.3" max="2.0" value="1.60" step="0.05" oninput="updatePreview()" class="w-full accent-indigo-600 cursor-pointer">
                </div>

                <!-- Posisi Atas/Bawah Nama -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-700">Posisi Vertikal Nama (Atas/Bawah):</label>
                        <span id="namePosYDisplay" class="text-xs font-mono text-gray-600">42.8 %</span>
                    </div>
                    <input type="range" id="inputNamePosY" min="0" max="100" value="42.8" step="0.1" oninput="updatePreview()" class="w-full accent-indigo-600 cursor-pointer">
                </div>

                <!-- Posisi Atas/Bawah Keterangan -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-700">Posisi Vertikal Keterangan (Atas/Bawah):</label>
                        <span id="descPosYDisplay" class="text-xs font-mono text-gray-600">57.2 %</span>
                    </div>
                    <input type="range" id="inputDescPosY" min="0" max="100" value="57.2" step="0.1" oninput="updatePreview()" class="w-full accent-indigo-600 cursor-pointer">
                </div>

                <!-- Posisi Atas/Bawah Tanggal (Rentang 0% - 100%) -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-[11px] font-semibold text-gray-700">Posisi Vertikal Tanggal (Atas/Bawah):</label>
                        <span id="datePosYDisplay" class="text-xs font-mono text-gray-600">65.4 %</span>
                    </div>
                    <input type="range" id="inputDatePosY" min="0" max="100" value="65.4" step="0.1" oninput="updatePreview()" class="w-full accent-indigo-600 cursor-pointer">
                </div>

                <!-- Tombol Cetak & Unduh -->
                <div class="pt-3 border-t border-gray-100 flex flex-col gap-2">
                    <button type="button" onclick="printCertificate()" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition text-xs flex items-center justify-center gap-2">
                        <i data-feather="printer" class="w-4 h-4"></i> Cetak Sertifikat (A4 Landscape)
                    </button>
                    <button type="button" onclick="downloadImage()" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition text-xs flex items-center justify-center gap-2">
                        <i data-feather="image" class="w-4 h-4"></i> Unduh File Gambar (.PNG)
                    </button>
                </div>
            </div>

        </div>

        <!-- KOLOM KANAN: PREVIEW SERTIFIKAT DENGAN SPASI & TATA LETAK RAPI -->
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 lg:p-6 sticky top-20 print:p-0 print:border-0 print:shadow-none">
                
                <!-- Preview Toolbar -->
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 print:hidden">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Preview Realtime Presisi
                        </span>
                        <span class="text-xs text-gray-400">| Rasio A4 Landscape (300 DPI)</span>
                    </div>
                    <button type="button" onclick="toggleFullscreenPreview()" class="text-gray-500 hover:text-indigo-600 p-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 text-xs flex items-center gap-1" title="Layar Penuh">
                        <i data-feather="maximize-2" class="w-3.5 h-3.5"></i> <span class="hidden sm:inline">Layar Penuh</span>
                    </button>
                </div>

                <!-- Preview Viewport Container -->
                <div class="certificate-viewport-container flex justify-center items-center bg-gray-100/80 p-2 sm:p-4 rounded-xl border border-dashed border-gray-300 overflow-x-auto print:bg-white print:p-0 print:m-0 print:border-0">
                    
                    <!-- ========================================================================= -->
                    <!-- KANVAS SERTIFIKAT DENGAN BACKGROUND TEMPLATE ASLI PDF -->
                    <!-- ========================================================================= -->
                    <div id="certificateContainer" class="cert-canvas relative select-none shadow-2xl print:shadow-none">
                        
                        <!-- Gambar Background Asli dari PDF -->
                        <img src="{{ asset('assets/image/template_sertifikat_clean.png') }}" 
                             alt="Template Sertifikat Asli" 
                             class="w-full h-full object-cover block pointer-events-none" 
                             id="certTemplateImg">

                        <!-- OVERLAY 1: NAMA PENERIMA DINAMIS (Y ~ 42.8%) -->
                        <div id="nameOverlayWrapper" 
                             class="absolute left-0 right-0 w-full flex items-center justify-center pointer-events-none px-12"
                             style="top: 42.8%; transform: translateY(-50%);">
                            <div id="viewRecipientName" 
                                 class="font-serif-lux font-bold tracking-wide text-center transition-all" 
                                 style="font-size: 36px; color: #0c2340; line-height: 1.2;">
                                Muhammad Rian Pratama
                            </div>
                        </div>

                        <!-- OVERLAY 2: KETERANGAN / DESKRIPSI DINAMIS (Y ~ 57.2% - Spasi & Margin Rapi) -->
                        <div id="descOverlayWrapper" 
                             class="absolute left-0 right-0 w-full flex items-center justify-center pointer-events-none"
                             style="top: 57.2%; transform: translateY(-50%);">
                            <div id="viewDescription" 
                                 class="font-cormorant font-normal text-center text-slate-800 transition-all mx-auto" 
                                 style="width: 76%; font-size: 18px; line-height: 1.6; color: #2d3748; letter-spacing: 0.015em;">
                                Telah Menyelesaikan Rangkaian Program Magang di Authorized Bekasi Laptop Store (Tahun 2026) dengan capaian hasil yang baik. Berhasil mengaplikasikan pemahaman teknis dan operasional secara optimal serta berkontribusi positif terhadap kinerja tim selama masa magang.
                            </div>
                        </div>

                        <!-- OVERLAY 3: TANGGAL, BULAN, TAHUN & KOTA DINAMIS (Y ~ 65.4% - Spasi Presisi) -->
                        <div id="dateOverlayWrapper" 
                             class="absolute left-0 right-0 w-full flex items-center justify-center pointer-events-none px-12"
                             style="top: 65.4%; transform: translateY(-50%);">
                            <div id="viewDatePlace" 
                                 class="font-cormorant font-normal text-center text-slate-800 transition-all" 
                                 style="font-size: 18px; line-height: 1.2; color: #2d3748; letter-spacing: 0.015em;">
                                Tegal, 8 Agustus 2026
                            </div>
                        </div>

                    </div>
                    <!-- ========================================================================= -->

                </div>

                <!-- Info footer under preview -->
                <div class="mt-4 flex items-center justify-between text-xs text-gray-500 print:hidden">
                    <div class="flex items-center gap-1.5">
                        <i data-feather="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Tata letak, margin kanan-kiri, jarak atas-bawah, dan spasi baris telah dirapikan secara proporsional.</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- STYLES & PRINT MEDIA CSS -->
<style>
/* Certificate Dimensions: Standard Landscape A4 Ratio (1.414) */
.cert-canvas {
    width: 100%;
    max-width: 960px;
    height: auto;
    aspect-ratio: 3509 / 2479;
    margin: 0 auto;
    border-radius: 4px;
    overflow: hidden;
    background-color: #ffffff;
    box-sizing: border-box;
}

/* Font Styles */
.font-serif-lux {
    font-family: 'Playfair Display', Georgia, serif;
}
.font-cormorant {
    font-family: 'Cormorant Garamond', Garamond, Georgia, serif;
    font-weight: 500;
}
.font-merriweather {
    font-family: 'Merriweather', Georgia, serif;
}
.font-cinzel {
    font-family: 'Cinzel', serif;
}
.font-script-alex {
    font-family: 'Alex Brush', cursive;
    font-size: 48px !important;
    font-weight: 400 !important;
}
.font-script-vibes {
    font-family: 'Great Vibes', cursive;
    font-size: 48px !important;
    font-weight: 400 !important;
}
.font-montserrat {
    font-family: 'Montserrat', sans-serif;
}
.font-times {
    font-family: 'Times New Roman', Times, serif;
}

/* Print Optimization CSS */
@media print {
    body, html {
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    .app, .content, .page-header, .sidebar, .mobile-menu-btn, .header-actions, .print\\:hidden, #midone-preloader, header, nav, aside {
        display: none !important;
    }
    
    .content-area {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }
    
    .certificate-viewport-container {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        background: transparent !important;
        display: block !important;
    }

    #certificateContainer {
        width: 100vw !important;
        max-width: 100vw !important;
        height: 100vh !important;
        max-height: 100vh !important;
        aspect-ratio: unset !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
    }

    #certTemplateImg {
        width: 100% !important;
        height: 100% !important;
        object-fit: fill !important;
    }

    @page {
        size: A4 landscape;
        margin: 0;
    }
}
</style>

<!-- SCRIPT: REALTIME BINDING, PRESETS, SPACING, DATE LOGIC, PRINT & DOWNLOAD -->
<script>
const MONTH_NAMES = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni", 
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
];

const DESC_PRESETS = {
    magang: "Telah Menyelesaikan Rangkaian Program Magang di Authorized Bekasi Laptop Store (Tahun 2026) dengan capaian hasil yang baik. Berhasil mengaplikasikan pemahaman teknis dan operasional secara optimal serta berkontribusi positif terhadap kinerja tim selama masa magang.",
    karyawan: "Atas dedikasi, loyalitas, dan pencapaian kinerja luar biasa sebagai Karyawan Terbaik (Employee of the Month) di Azzahra Computer Authorized Service Center. Teruslah berkontribusi dan menjadi teladan bagi tim.",
    pelatihan: "Telah berhasil menyelesaikan Program Pelatihan & Uji Kompetensi Teknisi Hardware Laptop & Motherboard Tingkat Lanjut yang diselenggarakan oleh Azzahra Computer Authorized Service Center dengan hasil sangat memuaskan."
};

// Initial Setup
document.addEventListener("DOMContentLoaded", function() {
    const inputName = document.getElementById("inputRecipientName");
    if (inputName) {
        inputName.addEventListener("input", updatePreview);
    }
    updatePreview();
});

// Update Live Preview
function updatePreview() {
    // Elements
    const nameInput = document.getElementById("inputRecipientName");
    const nameTarget = document.getElementById("viewRecipientName");
    const nameWrapper = document.getElementById("nameOverlayWrapper");
    const fontSelect = document.getElementById("selectFontFamily");
    const sizeInput = document.getElementById("inputFontSize");
    const sizeDisplay = document.getElementById("fontSizeDisplay");
    
    const descInput = document.getElementById("inputDescription");
    const descTarget = document.getElementById("viewDescription");
    const descWrapper = document.getElementById("descOverlayWrapper");
    
    const dateFullInput = document.getElementById("inputDateFullText");
    const dateTarget = document.getElementById("viewDatePlace");
    const dateWrapper = document.getElementById("dateOverlayWrapper");
    
    const bodyFontSelect = document.getElementById("selectBodyFontFamily");
    const bodyFontClass = bodyFontSelect.value;
    
    // Spacing & Size Controls
    const bodySizeInput = document.getElementById("inputBodyFontSize");
    const bodySizeDisplay = document.getElementById("bodyFontSizeDisplay");
    const bodyFontSize = bodySizeInput ? bodySizeInput.value : 18;

    const descWidthInput = document.getElementById("inputDescWidth");
    const descWidthDisplay = document.getElementById("descWidthDisplay");
    const descWidth = descWidthInput ? descWidthInput.value : 76;

    const lineHeightInput = document.getElementById("inputLineHeight");
    const lineHeightDisplay = document.getElementById("lineHeightDisplay");
    const lineHeight = lineHeightInput ? lineHeightInput.value : 1.6;

    const namePosYInput = document.getElementById("inputNamePosY");
    const namePosYDisplay = document.getElementById("namePosYDisplay");
    const namePosY = namePosYInput ? namePosYInput.value : 42.8;

    const descPosYInput = document.getElementById("inputDescPosY");
    const descPosYDisplay = document.getElementById("descPosYDisplay");
    const descPosY = descPosYInput ? descPosYInput.value : 57.2;

    const datePosYInput = document.getElementById("inputDatePosY");
    const datePosYDisplay = document.getElementById("datePosYDisplay");
    const datePosY = datePosYInput ? datePosYInput.value : 65.4;

    // 1. Update Recipient Name
    const textValue = nameInput.value.trim() || "Nama Penerima";
    nameTarget.innerText = textValue;
    nameTarget.className = `${fontSelect.value} font-bold tracking-wide text-center transition-all`;
    const fontSize = sizeInput.value;
    nameTarget.style.fontSize = fontSize + "px";
    sizeDisplay.innerText = fontSize + " px";
    if (nameWrapper) nameWrapper.style.top = namePosY + "%";
    if (namePosYDisplay) namePosYDisplay.innerText = namePosY + " %";

    // 2. Update Description (Spasi rapi, line-height, width)
    descTarget.innerText = descInput.value.trim() || "-";
    descTarget.className = `${bodyFontClass} font-normal text-center text-slate-800 transition-all mx-auto`;
    descTarget.style.fontSize = bodyFontSize + "px";
    descTarget.style.width = descWidth + "%";
    descTarget.style.lineHeight = lineHeight;
    if (descWrapper) descWrapper.style.top = descPosY + "%";
    if (descWidthDisplay) descWidthDisplay.innerText = descWidth + " %";
    if (lineHeightDisplay) lineHeightDisplay.innerText = Number(lineHeight).toFixed(2);
    if (descPosYDisplay) descPosYDisplay.innerText = descPosY + " %";

    // 3. Update Date Place
    dateTarget.innerText = dateFullInput.value.trim() || "Tegal, 8 Agustus 2026";
    dateTarget.className = `${bodyFontClass} font-normal text-center text-slate-800 transition-all`;
    dateTarget.style.fontSize = bodyFontSize + "px";
    if (dateWrapper) dateWrapper.style.top = datePosY + "%";
    if (datePosYDisplay) datePosYDisplay.innerText = datePosY + " %";

    if (bodySizeDisplay) {
        bodySizeDisplay.innerText = bodyFontSize + " px";
    }
}

// Reset Spacing Defaults
function resetSpacingDefaults() {
    document.getElementById("inputBodyFontSize").value = 18;
    document.getElementById("inputDescWidth").value = 76;
    document.getElementById("inputLineHeight").value = 1.60;
    document.getElementById("inputNamePosY").value = 42.8;
    document.getElementById("inputDescPosY").value = 57.2;
    document.getElementById("inputDatePosY").value = 65.4;
    updatePreview();
}

// Preset Keterangan
function setDescPreset(key) {
    if (DESC_PRESETS[key]) {
        document.getElementById("inputDescription").value = DESC_PRESETS[key];
        updatePreview();
    }
}

// Synchronize Date Picker -> Custom select & input
function syncDatePickerToCustom() {
    const picker = document.getElementById("inputDatePicker");
    if (!picker.value) return;

    const parts = picker.value.split("-");
    const year = parseInt(parts[0], 10);
    const monthIndex = parseInt(parts[1], 10) - 1;
    const day = parseInt(parts[2], 10);

    document.getElementById("selectDay").value = day;
    document.getElementById("selectMonth").value = MONTH_NAMES[monthIndex];
    document.getElementById("inputYear").value = year;

    buildDateString();
}

// Build Date String from City, Day, Month, Year
function buildDateString() {
    const city = document.getElementById("inputCity").value.trim() || "Tegal";
    const day = document.getElementById("selectDay").value;
    const month = document.getElementById("selectMonth").value;
    const year = document.getElementById("inputYear").value;

    const formatted = `${city}, ${day} ${month} ${year}`;
    document.getElementById("inputDateFullText").value = formatted;

    const monthIndex = MONTH_NAMES.indexOf(month) + 1;
    const mm = String(monthIndex).padStart(2, '0');
    const dd = String(day).padStart(2, '0');
    document.getElementById("inputDatePicker").value = `${year}-${mm}-${dd}`;

    updatePreview();
}

// Set Today Date
function setTodayDate() {
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');

    document.getElementById("inputDatePicker").value = `${yyyy}-${mm}-${dd}`;
    syncDatePickerToCustom();
}

// Print Handler
function printCertificate() {
    window.print();
}

// Download PNG Image using html2canvas
function downloadImage() {
    const certElem = document.getElementById("certificateContainer");
    if (!certElem) return;

    const recipientName = (document.getElementById("inputRecipientName").value || "Sertifikat").trim().replace(/[^a-zA-Z0-9_-]/g, "_");

    Swal.fire({
        title: 'Menyiapkan Gambar...',
        text: 'Sedang merender sertifikat resolusi tinggi',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    html2canvas(certElem, {
        scale: 3,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff'
    }).then(canvas => {
        Swal.close();
        const link = document.createElement('a');
        link.download = `Sertifikat_${recipientName}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    }).catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal Mengunduh',
            text: 'Terjadi kesalahan: ' + err
        });
    });
}

// Fullscreen preview
function toggleFullscreenPreview() {
    const certElem = document.getElementById("certificateContainer");
    if (!document.fullscreenElement) {
        if (certElem.requestFullscreen) {
            certElem.requestFullscreen();
        } else if (certElem.webkitRequestFullscreen) {
            certElem.webkitRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}
</script>
@endsection