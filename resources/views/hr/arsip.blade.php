@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="archive" class="w-6 h-6 inline-block mr-2"></i>Arsip Dokumen Servis (Dreame & Laptop)</h1>
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
                <h2 class="font-bold text-lg mb-4">Tambah Arsip</h2>
                <form action="{{ route('hr.save_arsip') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Tipe Perangkat</label>
                        <select name="tipe" class="input w-full border mt-1" required>
                            <option value="Dreame">Dreame</option>
                            <option value="Laptop">Laptop</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Nama Customer</label>
                        <input type="text" name="nama" class="input w-full border mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="input w-full border mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label>No HP</label>
                        <input type="text" name="no_hp" class="input w-full border mt-1">
                    </div>
                    <div class="mb-3">
                        <label>Tipe Detail (Model)</label>
                        <input type="text" name="tipe_detail" class="input w-full border mt-1">
                    </div>
                    <div class="mb-3">
                        <label>Kerusakan / Keluhan</label>
                        <textarea name="kerusakan" class="input w-full border mt-1"></textarea>
                    </div>
                    <div class="mb-4">
                        <label>Alamat</label>
                        <textarea name="alamat" class="input w-full border mt-1"></textarea>
                    </div>
                    <button type="submit" class="button bg-theme-1 text-white w-full">Simpan Arsip</button>
                </form>
            </div>
        </div>

        <!-- Tabel Dreame & Laptop -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5 mb-5">
                <h3 class="font-bold text-lg mb-3">Arsip Dreame</h3>
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>TANGGAL</th>
                                <th>NAMA</th>
                                <th>NO HP</th>
                                <th>MODEL</th>
                                <th>KERUSAKAN</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arsip_dreame as $a)
                            <tr>
                                <td>{{ $a->tanggal }}</td>
                                <td class="font-bold">{{ $a->nama }}</td>
                                <td>{{ $a->no_hp }}</td>
                                <td>{{ $a->tipe_detail }}</td>
                                <td>{{ $a->kerusakan }}</td>
                                <td>
                                    <form action="{{ route('hr.delete_arsip', $a->arsip_id) }}" method="POST" onsubmit="return confirm('Hapus arsip ini?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-gray-500 italic p-4">Belum ada arsip Dreame.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="box p-5">
                <h3 class="font-bold text-lg mb-3">Arsip Laptop</h3>
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm">
                        <thead>
                            <tr class="bg-gray-200">
                                <th>TANGGAL</th>
                                <th>NAMA</th>
                                <th>NO HP</th>
                                <th>MODEL</th>
                                <th>KERUSAKAN</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($arsip_laptop as $a)
                            <tr>
                                <td>{{ $a->tanggal }}</td>
                                <td class="font-bold">{{ $a->nama }}</td>
                                <td>{{ $a->no_hp }}</td>
                                <td>{{ $a->tipe_detail }}</td>
                                <td>{{ $a->kerusakan }}</td>
                                <td>
                                    <form action="{{ route('hr.delete_arsip', $a->arsip_id) }}" method="POST" onsubmit="return confirm('Hapus arsip ini?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-gray-500 italic p-4">Belum ada arsip Laptop.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
