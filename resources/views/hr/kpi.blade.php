@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="bar-chart-2" class="w-6 h-6 inline-block mr-2"></i>Evaluasi Kinerja (KPI)</h1>
        <p>Penilaian & Monitoring Performa Karyawan</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn" id="topbar-mail-btn" title="Kotak Pesan">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Form KPI -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5">
                <h2 class="font-bold text-lg mb-4">Input Penilaian Harian</h2>
                <form action="{{ route('hr.save_kpi') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Tanggal / Periode Harian</label>
                        <input type="date" name="periode" value="{{ $selected_periode }}" class="input w-full border mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label>Karyawan</label>
                        <select name="id_karyawan" class="input w-full border mt-1 select2" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawan_list as $k)
                                <option value="{{ $k->kry_kode }}">{{ $k->kry_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="border p-3 bg-gray-50 rounded mb-4">
                        <p class="text-xs text-gray-500 mb-2 italic">Rentang Nilai: 0 - 5 (Sangat Kurang - Sangat Baik)</p>
                        <div class="mb-2">
                            <label class="text-sm">Kedisiplinan</label>
                            <input type="number" name="kedisiplinan" class="input w-full border p-1" step="0.5" min="0" max="5" required>
                        </div>
                        <div class="mb-2">
                            <label class="text-sm">Kualitas Kerja</label>
                            <input type="number" name="kualitas_kerja" class="input w-full border p-1" step="0.5" min="0" max="5" required>
                        </div>
                        <div class="mb-2">
                            <label class="text-sm">Produktivitas</label>
                            <input type="number" name="produktivitas" class="input w-full border p-1" step="0.5" min="0" max="5" required>
                        </div>
                        <div class="mb-2">
                            <label class="text-sm">Kerja Tim / Kolaborasi</label>
                            <input type="number" name="kerja_tim" class="input w-full border p-1" step="0.5" min="0" max="5" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label>Catatan (Opsional)</label>
                        <textarea name="catatan" class="input w-full border mt-1" rows="2"></textarea>
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white w-full">Simpan Penilaian</button>
                </form>
            </div>
        </div>

        <!-- Tabel KPI -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5">
                <form method="GET" action="{{ route('hr.kpi') }}" class="flex items-end gap-2 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Filter Tanggal (Harian)</label>
                        <input type="date" name="periode" value="{{ $selected_periode }}" class="input border">
                    </div>
                    <button type="submit" class="button bg-gray-200">Filter</button>
                </form>
                
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>NAMA</th>
                                <th>DIS</th>
                                <th>KUA</th>
                                <th>PRO</th>
                                <th>TIM</th>
                                <th>RATA²</th>
                                <th>KATEGORI</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kpi_list as $kpi)
                            <tr>
                                <td class="font-bold">{{ $kpi->nama_karyawan }}</td>
                                <td>{{ $kpi->kedisiplinan }}</td>
                                <td>{{ $kpi->kualitas_kerja }}</td>
                                <td>{{ $kpi->produktivitas }}</td>
                                <td>{{ $kpi->kerja_tim }}</td>
                                <td class="font-bold">{{ $kpi->rata_rata }}</td>
                                <td>
                                    <span class="px-2 py-1 rounded text-xs text-white 
                                        {{ $kpi->kategori == 'Sangat Baik' ? 'bg-green-600' : ($kpi->kategori == 'Baik' ? 'bg-blue-500' : 'bg-yellow-500') }}">
                                        {{ $kpi->kategori }}
                                    </span>
                                </td>
                                <td>
                                    <form action="{{ route('hr.delete_kpi', $kpi->kpi_id) }}" method="POST" onsubmit="return confirm('Hapus penilaian ini?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center text-gray-500 italic p-4">Data penilaian kinerja belum tersedia untuk tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
