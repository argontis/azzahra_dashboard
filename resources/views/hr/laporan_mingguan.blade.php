@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="bar-chart-2" class="w-6 h-6 inline-block mr-2 text-blue-600"></i>KPI Karyawan</h1>
        <p>Pencatatan & Import Data Performance KPI Karyawan (Periode Bulanan)</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('hr.template_kpi') }}" class="button border bg-white text-gray-700 hover:bg-gray-50 flex items-center gap-2 text-xs py-2 px-3 rounded-lg shadow-sm">
            <i data-feather="download" class="w-4 h-4 text-blue-600"></i> Template
        </a>
        <a role="button" class="button bg-theme-1 text-white flex items-center gap-2 text-xs py-2 px-3 rounded-lg shadow-md" data-toggle="modal" data-target="#import-kpi-modal">
            <i data-feather="upload" class="w-4 h-4"></i> Import
        </a>
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

    <div class="grid grid-cols-12 gap-6">
        <!-- Form Manual Input KPI -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h2 class="font-bold text-lg mb-4 text-gray-800 flex items-center gap-2">
                    <i data-feather="edit-3" class="w-5 h-5 text-blue-600"></i> Input Manual KPI
                </h2>
                <form action="{{ route('hr.save_kpi') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Periode Bulanan</label>
                        <input type="month" name="periode" value="{{ $selected_periode }}" class="input w-full border rounded-lg p-2 text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Karyawan</label>
                        <select name="id_karyawan" class="input w-full border rounded-lg p-2 text-sm select2" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawan_list as $k)
                                <option value="{{ $k->kry_kode }}">{{ $k->kry_nama }} {{ $k->kry_telp ? '('.$k->kry_telp.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Target Bulanan</label>
                        <textarea name="target_mingguan" class="input w-full border rounded-lg p-2 text-sm" rows="2" placeholder="Target KPI bulan ini..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tugas yang Dilakukan</label>
                        <textarea name="tugas_dilakukan" class="input w-full border rounded-lg p-2 text-sm" rows="3" placeholder="Rincian tugas..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Hasil / Pencapaian</label>
                        <textarea name="hasil" class="input w-full border rounded-lg p-2 text-sm" rows="2" placeholder="Hasil pencapaian..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kendala</label>
                        <textarea name="kendala" class="input w-full border rounded-lg p-2 text-sm" rows="2" placeholder="Kendala yang dihadapi..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Solusi</label>
                        <textarea name="solusi" class="input w-full border rounded-lg p-2 text-sm" rows="2" placeholder="Solusi / perbaikan..."></textarea>
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white w-full py-2.5 rounded-lg font-semibold text-sm shadow">Simpan KPI</button>
                </form>
            </div>
        </div>

        <!-- Tabel Laporan KPI -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-4">
                    <h2 class="font-bold text-gray-800 text-base">Daftar KPI Karyawan (Bulan: {{ \Carbon\Carbon::parse($selected_periode.'-01')->format('F Y') }})</h2>
                    <form method="GET" action="{{ route('hr.kpi') }}" class="flex items-center gap-2">
                        <label class="text-xs font-semibold text-gray-600">Filter Bulan:</label>
                        <input type="month" name="periode" value="{{ $selected_periode }}" class="input border rounded-lg px-3 py-1.5 text-xs">
                        <button type="submit" class="button bg-gray-200 py-1.5 px-3 rounded-lg text-xs font-semibold">Filter</button>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table w-full text-xs">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border-b-2 p-3">NAMA KARYAWAN</th>
                                <th class="border-b-2 text-center p-3">NO HP</th>
                                <th class="border-b-2 p-3">TARGET</th>
                                <th class="border-b-2 p-3">HASIL</th>
                                <th class="border-b-2 p-3">KENDALA</th>
                                <th class="border-b-2 text-center p-3">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan_list as $lap)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="font-bold p-3 text-gray-800">
                                    {{ $lap->nama_karyawan }}
                                    <div class="text-gray-400 font-normal text-xs">{{ $lap->posisi }}</div>
                                </td>
                                <td class="text-center p-3">
                                    @if($lap->karyawan?->kry_telp)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $lap->karyawan->kry_telp)) }}" target="_blank" class="text-blue-600 font-semibold hover:underline flex items-center justify-center gap-1 text-xs" title="Chat via WhatsApp">
                                            📞 {{ $lap->karyawan->kry_telp }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="p-3">{{ Str::limit($lap->target_mingguan, 35) }}</td>
                                <td class="p-3">{{ Str::limit($lap->hasil, 35) }}</td>
                                <td class="p-3">{{ Str::limit($lap->kendala, 35) }}</td>
                                <td class="text-center p-3">
                                    <form action="{{ route('hr.delete_kpi', $lap->laporan_id) }}" method="POST" onsubmit="return confirm('Hapus KPI ini?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline text-xs font-semibold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-gray-500 italic p-6">Data KPI belum tersedia untuk bulan ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL IMPORT EXCEL / CSV KPI -->
<div class="modal" id="import-kpi-modal">
    <div class="modal__content modal__content--lg p-6">
        <div class="flex items-center px-5 py-4 border-b border-gray-200">
            <h2 class="font-bold text-lg text-gray-800 mr-auto flex items-center gap-2">
                <i data-feather="file-text" class="w-5 h-5 text-blue-600"></i> Import File Excel / CSV KPI
            </h2>
            <button type="button" data-dismiss="modal" class="button border text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ route('hr.import_kpi') }}" enctype="multipart/form-data" class="p-5">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Periode Bulan Target</label>
                <input type="month" name="periode" value="{{ $selected_periode }}" class="input w-full border rounded p-2 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih File Excel / CSV *</label>
                <input type="file" name="file_excel" accept=".xlsx,.xls,.csv,.txt" class="input w-full border rounded p-2 text-sm" required>
                <p class="text-xs text-gray-500 mt-1">Format didukung: .xlsx, .xls, .csv, .txt</p>
            </div>

            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg mb-4 text-xs text-blue-900 space-y-1">
                <div class="font-bold flex items-center gap-1"><i data-feather="info" class="w-4 h-4"></i> Petunjuk Import Excel:</div>
                <p>1. Pastikan kolom pertama (A) berisi <b>Nama Karyawan</b> yang sesuai dengan nama di sistem.</p>
                <p>2. Kolom berikutnya: <b>Periode Bulan (e.g. 2026-08), Target Bulanan, Tugas Dilakukan, Hasil, Kendala, Solusi</b>.</p>
                <p>3. Anda dapat mengunduh contoh format file melalui tombol <a href="{{ route('hr.template_kpi') }}" class="underline font-bold text-blue-700">Download Template Excel KPI</a>.</p>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button border text-gray-700 px-4 py-2">Batal</button>
                <button type="submit" class="button bg-theme-1 text-white px-4 py-2 font-semibold flex items-center gap-1 shadow">
                    <i data-feather="upload" class="w-4 h-4"></i> Import Data KPI
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
