@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i>Pencatatan Absensi (USB RFID Reader)</h1>
        <p>Pencatatan 4 tahap harian: Jam Masuk &rarr; Istirahat &rarr; Kembali Istirahat &rarr; Jam Pulang</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;"><i data-feather="bell"></i><div class="badge-dot"></div></div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded-lg mb-4 flex items-center gap-2 border border-green-200">
        <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
        <span>{{ session('sukses') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded-lg mb-4 flex items-center gap-2 border border-red-200">
        <i data-feather="alert-circle" class="w-5 h-5 text-red-600"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

@php
    $activeMode = session('active_scan_mode', request('scan_mode', 'auto'));
@endphp

    <!-- SECTION 1: USB RFID CARD SCANNER TAP AREA -->
    <div class="intro-y p-6 rounded-xl shadow-lg mb-6 border-2 border-blue-700" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e1b4b 100%); color: #ffffff !important;">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-800/80 rounded-xl border border-blue-400/30">
                    <i data-feather="radio" class="w-10 h-10 text-yellow-300 animate-pulse"></i>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold tracking-wide" style="color: #ffffff !important; margin: 0;">Tap / Scan Kartu RFID Absensi</h3>
                    <p class="text-sm font-bold mt-1" style="color: #dbeafe !important; margin: 0;">Tempelkan kartu RFID ke alat USB Reader untuk otomatis mencatat jam kerja harian.</p>
                </div>
            </div>

            <!-- RFID Input Form -->
            <form action="{{ route('hr.save_absensi') }}" method="POST" id="rfidForm" class="w-full lg:w-auto flex flex-col sm:flex-row items-center gap-3">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $selected_date }}">
                <input type="hidden" name="scan_mode" id="scan_mode_input" value="{{ $activeMode }}">
                
                <div class="flex flex-wrap items-center gap-1.5 bg-blue-950/80 p-2 rounded-lg border border-blue-400/40 text-xs font-bold">
                    <button type="button" onclick="setScanMode('auto')" id="mode-btn-auto" class="scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $activeMode == 'auto' ? 'bg-yellow-400 text-blue-950 font-extrabold shadow scale-105' : 'text-white hover:bg-white/10' }}">
                        ⚡ <span>Auto (Urut)</span>
                    </button>
                    <button type="button" onclick="setScanMode('masuk')" id="mode-btn-masuk" class="scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $activeMode == 'masuk' ? 'bg-green-500 text-white font-extrabold shadow scale-105' : 'text-white hover:bg-white/10' }}">
                        🟢 <span>Jam Masuk</span>
                    </button>
                    <button type="button" onclick="setScanMode('istirahat')" id="mode-btn-istirahat" class="scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $activeMode == 'istirahat' ? 'bg-yellow-500 text-white font-extrabold shadow scale-105' : 'text-white hover:bg-white/10' }}">
                        🟡 <span>Jam Istirahat</span>
                    </button>
                    <button type="button" onclick="setScanMode('kembali_istirahat')" id="mode-btn-kembali_istirahat" class="scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $activeMode == 'kembali_istirahat' ? 'bg-blue-500 text-white font-extrabold shadow scale-105' : 'text-white hover:bg-white/10' }}">
                        🔵 <span>Kembali</span>
                    </button>
                    <button type="button" onclick="setScanMode('pulang')" id="mode-btn-pulang" class="scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 {{ $activeMode == 'pulang' ? 'bg-red-500 text-white font-extrabold shadow scale-105' : 'text-white hover:bg-white/10' }}">
                        🔴 <span>Jam Pulang</span>
                    </button>
                </div>

                <div class="relative w-full sm:w-64">
                    <input type="text" name="rfid_code" id="rfid_code" placeholder="Scan Kartu RFID / NIK..." class="input w-full px-3 py-2.5 bg-white text-gray-900 rounded-lg text-sm font-extrabold shadow border-2 border-yellow-400 focus:outline-none" autofocus autocomplete="off">
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Form Manual Absensi (Bisa Simpan Satu Per Satu) -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-lg mb-4 text-gray-800 flex items-center gap-2">
                    <i data-feather="edit-3" class="w-5 h-5 text-blue-600"></i> Input Manual Kehadiran
                </h2>
                <form action="{{ route('hr.save_absensi') }}" method="POST" id="manualAbsensiForm">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $selected_date }}" class="input w-full border rounded-lg p-2 text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Karyawan</label>
                        <select name="id_karyawan" class="input w-full border rounded-lg p-2 text-sm select2" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawan_list as $k)
                                <option value="{{ $k->kry_kode }}">{{ $k->kry_nama }} {{ $k->kry_telp ? '('.$k->kry_telp.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Status Kehadiran</label>
                        <select name="status" class="input w-full border rounded-lg p-2 text-sm">
                            <option value="HADIR">HADIR</option>
                            <option value="TELAT">TELAT</option>
                            <option value="IZIN">IZIN</option>
                            <option value="SAKIT">SAKIT</option>
                            <option value="CUTI">CUTI</option>
                            <option value="ALPA">ALPA</option>
                        </select>
                    </div>

                    <!-- Input Jam Kerja Per Tahap (Bisa diisi satu-satu) -->
                    <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg mb-4 space-y-3">
                        <div class="text-xs font-bold text-gray-700 uppercase tracking-wider flex items-center justify-between">
                            <span>Jam Kerja (Diisi Per Jam)</span>
                            <span class="text-gray-400 font-normal italic text-[11px]">Bisa simpan partial</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">🟢 Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="input w-full border rounded p-1.5 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">🟡 Jam Istirahat</label>
                                <input type="time" name="jam_istirahat" class="input w-full border rounded p-1.5 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">🔵 Kembali Istirahat</label>
                                <input type="time" name="jam_kembali_istirahat" class="input w-full border rounded p-1.5 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">🔴 Jam Pulang</label>
                                <input type="time" name="jam_pulang" class="input w-full border rounded p-1.5 text-xs">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan</label>
                        <input type="text" name="keterangan" class="input w-full border rounded-lg p-2 text-sm" placeholder="Opsional">
                    </div>
                    
                    <button type="submit" class="button bg-theme-1 text-white w-full py-2.5 rounded-lg font-semibold text-sm shadow">
                        Simpan Data Absensi
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabel Absensi 1 Hari Full dengan Fitur Edit Modal -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-4">
                    <h2 class="font-bold text-gray-800 text-base">Rekap Absensi 1 Hari Full</h2>
                    <form method="GET" action="{{ route('hr.absensi') }}" class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-600">Filter Tanggal:</label>
                        <input type="date" name="tanggal" value="{{ $selected_date }}" class="input border rounded-lg px-3 py-1.5 text-xs">
                        <button type="submit" class="button bg-gray-200 py-1.5 px-3 rounded-lg text-xs font-semibold">Filter</button>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table w-full text-xs">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border-b-2 p-2">NAMA KARYAWAN</th>
                                <th class="border-b-2 text-center p-2">STATUS</th>
                                <th class="border-b-2 text-center p-2">MASUK</th>
                                <th class="border-b-2 text-center p-2">ISTIRAHAT</th>
                                <th class="border-b-2 text-center p-2">KEMBALI ISTIRAHAT</th>
                                <th class="border-b-2 text-center p-2">PULANG</th>
                                <th class="border-b-2 text-center p-2">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi_list as $a)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="font-bold p-3 text-gray-800">
                                    {{ $a->nama_karyawan }}
                                    <div class="text-gray-400 font-normal text-xs">{{ $a->posisi }}</div>
                                </td>
                                <td class="text-center p-3">
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $a->status == 'HADIR' ? 'bg-green-100 text-green-800' : ($a->status == 'ALPA' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $a->status }}
                                    </span>
                                </td>
                                <td class="text-center p-3 font-mono font-semibold text-green-700">{{ $a->jam_masuk ?? '-' }}</td>
                                <td class="text-center p-3 font-mono font-semibold text-yellow-700">{{ $a->jam_istirahat ?? '-' }}</td>
                                <td class="text-center p-3 font-mono font-semibold text-blue-700">{{ $a->jam_kembali_istirahat ?? '-' }}</td>
                                <td class="text-center p-3 font-mono font-semibold text-red-700">{{ $a->jam_pulang ?? '-' }}</td>
                                <td class="text-center p-3">
                                    <div class="flex items-center justify-center gap-1">
                                        <!-- Tombol Edit Modal -->
                                        <a role="button" class="button border text-blue-600 bg-blue-50 py-1 px-2 rounded text-xs font-semibold hover:bg-blue-100" data-toggle="modal" data-target="#editAbsensiModal_{{ $a->absensi_id }}">
                                            Edit
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="{{ route('hr.delete_absensi', $a->absensi_id) }}" method="POST" onsubmit="return confirm('Hapus absen ini?');" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:underline text-xs font-semibold p-1">Hapus</button>
                                        </form>
                                    </div>

                                    <!-- MODAL EDIT ABSENSI KARYAWAN -->
                                    <div class="modal" id="editAbsensiModal_{{ $a->absensi_id }}">
                                        <div class="modal__content modal__content--lg p-6">
                                            <div class="flex items-center px-4 py-3 border-b border-gray-200">
                                                <h3 class="font-bold text-base text-gray-800 mr-auto">Edit Absensi: {{ $a->nama_karyawan }}</h3>
                                                <button type="button" data-dismiss="modal" class="button border text-gray-700">&times;</button>
                                            </div>
                                            <form action="{{ route('hr.save_absensi') }}" method="POST" class="p-4 text-left">
                                                @csrf
                                                <input type="hidden" name="is_edit" value="1">
                                                <input type="hidden" name="tanggal" value="{{ $a->tanggal }}">
                                                <input type="hidden" name="id_karyawan" value="{{ $a->id_karyawan }}">

                                                <div class="mb-3">
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Kehadiran</label>
                                                    <select name="status" class="input w-full border rounded p-2 text-xs">
                                                        <option value="HADIR" {{ $a->status == 'HADIR' ? 'selected' : '' }}>HADIR</option>
                                                        <option value="TELAT" {{ $a->status == 'TELAT' ? 'selected' : '' }}>TELAT</option>
                                                        <option value="IZIN" {{ $a->status == 'IZIN' ? 'selected' : '' }}>IZIN</option>
                                                        <option value="SAKIT" {{ $a->status == 'SAKIT' ? 'selected' : '' }}>SAKIT</option>
                                                        <option value="CUTI" {{ $a->status == 'CUTI' ? 'selected' : '' }}>CUTI</option>
                                                        <option value="ALPA" {{ $a->status == 'ALPA' ? 'selected' : '' }}>ALPA</option>
                                                    </select>
                                                </div>

                                                <div class="grid grid-cols-2 gap-3 mb-3 bg-gray-50 p-3 border rounded-lg">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">🟢 Jam Masuk</label>
                                                        <input type="time" name="jam_masuk" value="{{ $a->jam_masuk }}" class="input w-full border rounded p-1.5 text-xs">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">🟡 Jam Istirahat</label>
                                                        <input type="time" name="jam_istirahat" value="{{ $a->jam_istirahat }}" class="input w-full border rounded p-1.5 text-xs">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">🔵 Kembali Istirahat</label>
                                                        <input type="time" name="jam_kembali_istirahat" value="{{ $a->jam_kembali_istirahat }}" class="input w-full border rounded p-1.5 text-xs">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">🔴 Jam Pulang</label>
                                                        <input type="time" name="jam_pulang" value="{{ $a->jam_pulang }}" class="input w-full border rounded p-1.5 text-xs">
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan</label>
                                                    <input type="text" name="keterangan" value="{{ $a->keterangan }}" class="input w-full border rounded p-2 text-xs">
                                                </div>

                                                <div class="flex justify-end gap-2 pt-3 border-t border-gray-200">
                                                    <button type="button" data-dismiss="modal" class="button border text-gray-700 text-xs px-4 py-2">Batal</button>
                                                    <button type="submit" class="button bg-theme-1 text-white text-xs px-4 py-2 font-semibold">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-gray-500 italic p-6">Data absensi belum tersedia untuk tanggal ini.</td></tr>
                            @endforelse
                            <tr id="noSearchResultRow" style="display: none;">
                                <td colspan="7" class="text-center py-6 text-gray-500">
                                    <i data-feather="search" class="w-8 h-8 mx-auto mb-2 opacity-40"></i>
                                    <p>Tidak ada data absensi yang cocok dengan pencarian.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setScanMode(mode) {
        document.getElementById('scan_mode_input').value = mode;
        
        // Reset all mode buttons
        document.querySelectorAll('.scan-mode-btn').forEach(btn => {
            btn.className = 'scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 text-white hover:bg-white/10';
        });

        // Highlight active mode button
        const activeBtn = document.getElementById('mode-btn-' + mode);
        if (activeBtn) {
            if (mode === 'auto') activeBtn.className = 'scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 bg-yellow-400 text-blue-950 font-extrabold shadow scale-105';
            else if (mode === 'masuk') activeBtn.className = 'scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 bg-green-500 text-white font-extrabold shadow scale-105';
            else if (mode === 'istirahat') activeBtn.className = 'scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 bg-yellow-500 text-white font-extrabold shadow scale-105';
            else if (mode === 'kembali_istirahat') activeBtn.className = 'scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 bg-blue-500 text-white font-extrabold shadow scale-105';
            else if (mode === 'pulang') activeBtn.className = 'scan-mode-btn px-3 py-1.5 rounded-lg transition-all flex items-center gap-1.5 bg-red-500 text-white font-extrabold shadow scale-105';
        }

        const rfidInput = document.getElementById('rfid_code');
        if (rfidInput) {
            rfidInput.focus();
        }
    }

    // Keep focus on RFID input continuously for seamless USB Reader scanning
    document.addEventListener('DOMContentLoaded', function() {
        const rfidInput = document.getElementById('rfid_code');
        if (rfidInput) {
            rfidInput.focus();
            
            // Re-focus if user clicks away into void
            document.addEventListener('click', function(e) {
                if (!['INPUT', 'SELECT', 'TEXTAREA', 'BUTTON', 'A'].includes(e.target.tagName)) {
                    rfidInput.focus();
                }
            });
        }

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection
