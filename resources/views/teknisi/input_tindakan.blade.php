@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="edit-3" class="w-6 h-6 inline-block mr-2"></i>Input Tindakan & Sparepart</h1>
        <p>Kode Transaksi: {{ $transaksi->trans_kode }}</p>
    </div>
    <div class="header-section mt-5 flex gap-2">
        <a href="{{ route('teknisi.index') }}" class="button bg-gray-500 text-white">Kembali</a>
    </div>
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('sukses') }}</div>
    @endif
    
    @if(session('gagal'))
    <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('gagal') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Data Customer -->
        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 mb-5">
                <h2 class="font-bold text-lg border-b pb-2 mb-4">Informasi Servis</h2>
                <table class="table w-full mb-4">
                    <tr><td class="w-1/3 font-semibold">Nama</td><td>: {{ $transaksi->customer->cos_nama ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">Device</td><td>: {{ $transaksi->customer->cos_tipe ?? '-' }} {{ $transaksi->customer->cos_model ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">No. Seri</td><td>: {{ $transaksi->customer->cos_no_seri ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">Keluhan</td><td class="text-theme-6 font-bold">: {{ $transaksi->customer->cos_keluhan ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">Keterangan</td><td>: {{ $transaksi->customer->cos_keterangan ?? '-' }}</td></tr>
                </table>

                <hr class="mb-4">

                <h3 class="font-bold mb-2">Order Sparepart Tambahan</h3>
                <form action="{{ route('teknisi.order_sparepart') }}" method="POST">
                    @csrf
                    <input type="hidden" name="trans_kode" value="{{ $transaksi->trans_kode }}">
                    <div class="mb-3">
                        <label>Nama Barang / Sparepart</label>
                        <input type="text" name="barang_nama" class="input w-full border mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label>Status Ketersediaan (di Teknisi)</label>
                        <select name="ketersediaan" class="input w-full border mt-1" required>
                            <option value="tersedia">Tersedia (Bisa langsung dipasang)</option>
                            <option value="tidak_ada">Tidak Ada (Minta ke Admin/Gudang)</option>
                        </select>
                    </div>
                    <button type="submit" class="button bg-blue-500 text-white w-full">Kirim Permintaan Barang</button>
                    <p class="text-xs text-gray-600 mt-2 italic">* Jika stok kosong, transaksi akan beralih status ke Konfirmasi dan menunggu part tiba.</p>
                </form>
            </div>
        </div>

        <!-- Form Tindakan -->
        <div class="col-span-12 lg:col-span-8">
            <div class="box p-5">
                <h2 class="font-bold text-lg border-b pb-2 mb-4">Catat Perbaikan & Jasa</h2>
                <form action="{{ route('teknisi.save_tindakan') }}" method="POST">
                    @csrf
                    <input type="hidden" name="trans_kode" value="{{ $transaksi->trans_kode }}">
                    
                    <div class="overflow-x-auto mb-4">
                        <table class="table w-full" id="tindakanTable">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th>NAMA JASA / SPAREPART</th>
                                    <th>KETERANGAN</th>
                                    <th class="w-24 text-center">QTY</th>
                                    <th class="w-40 text-right">SUBTOTAL (RP)</th>
                                    <th class="w-16 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody id="tindakanBody">
                                <tr>
                                    <td><input type="text" name="tindakan[]" class="input w-full border" placeholder="Contoh: Install Ulang / LCD..." required></td>
                                    <td><input type="text" name="ket[]" class="input w-full border" placeholder="-"></td>
                                    <td><input type="number" name="qty[]" class="input w-full border text-center" value="1" min="1" required></td>
                                    <td><input type="number" name="subtot[]" class="input w-full border text-right" value="0" min="0" required></td>
                                    <td class="text-center"><button type="button" class="button bg-red-500 text-white p-1 rounded btn-remove"><i data-feather="trash-2" class="w-4 h-4"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <button type="button" id="btnAddRow" class="button border text-gray-700 bg-gray-100"><i data-feather="plus" class="w-4 h-4 inline-block mr-1"></i> Tambah Baris</button>
                        <button type="submit" class="button bg-theme-1 text-white px-8" onclick="return confirm('Simpan tindakan perbaikan? Status order akan diubah menjadi Diproses.')">Simpan Perbaikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAddRow = document.getElementById('btnAddRow');
    const tbody = document.getElementById('tindakanBody');

    btnAddRow.addEventListener('click', function() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="tindakan[]" class="input w-full border" placeholder="Contoh: Install Ulang / LCD..." required></td>
            <td><input type="text" name="ket[]" class="input w-full border" placeholder="-"></td>
            <td><input type="number" name="qty[]" class="input w-full border text-center" value="1" min="1" required></td>
            <td><input type="number" name="subtot[]" class="input w-full border text-right" value="0" min="0" required></td>
            <td class="text-center"><button type="button" class="button bg-red-500 text-white p-1 rounded btn-remove"><i data-feather="trash-2" class="w-4 h-4"></i></button></td>
        `;
        tbody.appendChild(tr);
        if (typeof feather !== 'undefined') feather.replace();
    });

    tbody.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove')) {
            const rows = tbody.querySelectorAll('tr');
            if (rows.length > 1) {
                e.target.closest('tr').remove();
            } else {
                alert('Minimal harus ada satu tindakan perbaikan.');
            }
        }
    });
});
</script>
@endsection
