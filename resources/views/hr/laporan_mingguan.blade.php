@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="file-text" class="w-6 h-6 inline-block mr-2"></i>Laporan Mingguan Karyawan</h1>
    </div>
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Form -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5">
                <h2 class="font-bold text-lg mb-4">Input Laporan Mingguan</h2>
                <form action="{{ route('hr.save_laporan_mingguan') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Periode Mingguan (e.g. 2026-W32)</label>
                        <input type="text" name="periode" value="{{ $selected_periode }}" class="input w-full border mt-1" required>
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
                    <div class="mb-3">
                        <label>Target Mingguan</label>
                        <textarea name="target_mingguan" class="input w-full border mt-1" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Tugas yang Dilakukan</label>
                        <textarea name="tugas_dilakukan" class="input w-full border mt-1" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Hasil / Pencapaian</label>
                        <textarea name="hasil" class="input w-full border mt-1" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Kendala</label>
                        <textarea name="kendala" class="input w-full border mt-1" rows="2"></textarea>
                    </div>
                    <div class="mb-4">
                        <label>Solusi</label>
                        <textarea name="solusi" class="input w-full border mt-1" rows="2"></textarea>
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white w-full">Simpan Laporan</button>
                </form>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5">
                <form method="GET" action="{{ route('hr.laporan_mingguan') }}" class="flex items-end gap-2 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Filter Periode (Mingguan)</label>
                        <input type="text" name="periode" value="{{ $selected_periode }}" class="input border">
                    </div>
                    <button type="submit" class="button bg-gray-200">Filter</button>
                </form>
                
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>NAMA</th>
                                <th>TARGET</th>
                                <th>HASIL</th>
                                <th>KENDALA</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan_list as $lap)
                            <tr>
                                <td class="font-bold">{{ $lap->nama_karyawan }}</td>
                                <td>{{ Str::limit($lap->target_mingguan, 30) }}</td>
                                <td>{{ Str::limit($lap->hasil, 30) }}</td>
                                <td>{{ Str::limit($lap->kendala, 30) }}</td>
                                <td>
                                    <form action="{{ route('hr.delete_laporan_mingguan', $lap->laporan_id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-gray-500 italic p-4">Laporan belum tersedia untuk minggu ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
