@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="calendar" class="w-6 h-6 inline-block mr-2"></i>Pencatatan Absensi</h1>
    </div>
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Form Absensi -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5">
                <h2 class="font-bold text-lg mb-4">Input Kehadiran</h2>
                <form action="{{ route('hr.save_absensi') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $selected_date }}" class="input w-full border mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label>Karyawan</label>
                        <select name="id_karyawan" class="input w-full border mt-1 select2" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawan_list as $k)
                                <option value="{{ $k->kry_kode }}">{{ $k->kry_nama }} {{ $k->kry_telp ? '('.$k->kry_telp.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status Kehadiran</label>
                        <select name="status" class="input w-full border mt-1" required>
                            <option value="HADIR">HADIR</option>
                            <option value="TELAT">TELAT</option>
                            <option value="IZIN">IZIN</option>
                            <option value="SAKIT">SAKIT</option>
                            <option value="CUTI">CUTI</option>
                            <option value="ALPA">ALPA</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-3">
                        <div>
                            <label>Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="input w-full border mt-1">
                        </div>
                        <div>
                            <label>Jam Pulang</label>
                            <input type="time" name="jam_pulang" class="input w-full border mt-1">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="input w-full border mt-1" placeholder="Opsional">
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white w-full">Simpan Absensi</button>
                </form>
            </div>
        </div>

        <!-- Tabel Absensi -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5">
                <form method="GET" action="{{ route('hr.absensi') }}" class="flex items-end gap-2 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Filter Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $selected_date }}" class="input border">
                    </div>
                    <button type="submit" class="button bg-gray-200">Filter</button>
                </form>
                
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>NAMA</th>
                                <th>NO HP</th>
                                <th>STATUS</th>
                                <th>MASUK</th>
                                <th>PULANG</th>
                                <th>KET</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi_list as $a)
                            <tr>
                                <td class="font-bold">{{ $a->nama_karyawan }}</td>
                                <td>
                                    @if($a->karyawan?->kry_telp)
                                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $a->karyawan->kry_telp)) }}" target="_blank" class="text-blue-600 font-semibold hover:underline flex items-center gap-1 text-xs" title="Chat via WhatsApp">
                                            📞 {{ $a->karyawan->kry_telp }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="px-2 py-1 rounded text-xs text-white bg-{{ $a->status == 'HADIR' ? 'green' : ($a->status == 'ALPA' ? 'red' : 'yellow') }}-500">
                                        {{ $a->status }}
                                    </span>
                                </td>
                                <td>{{ $a->jam_masuk ?? '-' }}</td>
                                <td>{{ $a->jam_pulang ?? '-' }}</td>
                                <td>{{ $a->keterangan }}</td>
                                <td>
                                    <form action="{{ route('hr.delete_absensi', $a->absensi_id) }}" method="POST" onsubmit="return confirm('Hapus absen ini?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-gray-500 italic p-4">Data absensi belum tersedia untuk tanggal ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
