@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="package" class="w-6 h-6 inline-block mr-2"></i>Pencatatan Barang / Inventaris</h1>
    </div>
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Form Batch Pencatatan -->
        <div class="col-span-12 lg:col-span-5">
            <div class="box p-5">
                <h2 class="font-bold text-lg mb-4">Input Barang Masuk (Batch)</h2>
                <form action="{{ route('hr.save_pencatatan') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $selected_date }}" class="input w-full border mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label>Kategori Global</label>
                        <select name="kategori_global" class="input w-full border mt-1" required>
                            <option value="Operasional">Operasional</option>
                            <option value="Aset">Aset</option>
                            <option value="Sparepart">Sparepart</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Upload Bukti / Nota (Opsional)</label>
                        <input type="file" name="gambar" class="input w-full border mt-1" accept="image/*">
                    </div>
                    
                    <hr class="my-4">
                    <h3 class="font-bold mb-2">Item Barang</h3>
                    
                    <div id="itemsContainer">
                        <div class="item-row grid grid-cols-12 gap-2 mb-2">
                            <div class="col-span-5"><input type="text" name="nama_barang[]" placeholder="Nama Barang" class="input w-full border text-sm" required></div>
                            <div class="col-span-3"><input type="number" name="qty[]" placeholder="Qty" class="input w-full border text-sm" value="1" min="1" required></div>
                            <div class="col-span-4"><input type="number" name="harga_satuan[]" placeholder="Harga Sat" class="input w-full border text-sm" value="0" min="0" required></div>
                        </div>
                    </div>
                    
                    <button type="button" id="btnAddItem" class="text-blue-500 text-sm font-semibold mb-4"><i data-feather="plus" class="w-4 h-4 inline-block"></i> Tambah Item Lain</button>
                    
                    <button type="submit" class="button bg-theme-1 text-white w-full mt-2">Simpan Batch Pencatatan</button>
                </form>
            </div>
        </div>

        <!-- List Pencatatan -->
        <div class="col-span-12 lg:col-span-7">
            <div class="box p-5">
                <form method="GET" action="{{ route('hr.pencatatan') }}" class="flex items-end gap-2 mb-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Filter Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $selected_date }}" class="input border">
                    </div>
                    <button type="submit" class="button bg-gray-200">Filter</button>
                </form>
                
                <div class="space-y-4">
                    @forelse($pencatatan_list as $batch_id => $items)
                        <div class="border rounded p-4 relative">
                            <div class="flex justify-between items-start mb-2 border-b pb-2">
                                <div>
                                    <div class="font-bold text-lg text-theme-1">Batch: {{ $batch_id }}</div>
                                    <div class="text-xs text-gray-600">Kategori: {{ $items->first()->kategori_global }}</div>
                                </div>
                                <form action="{{ route('hr.delete_pencatatan', $batch_id) }}" method="POST" onsubmit="return confirm('Hapus seluruh batch ini?');">
                                    @csrf
                                    <button type="submit" class="button bg-red-100 text-red-700 text-xs py-1 px-2">Hapus Batch</button>
                                </form>
                            </div>
                            
                            <table class="table w-full text-sm mb-2">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th>Nama Barang</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-right">Harga</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td class="text-center">{{ $item->qty }}</td>
                                        <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                        <td class="text-right font-semibold">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 font-bold">
                                        <td colspan="3" class="text-right">GRAND TOTAL</td>
                                        <td class="text-right text-theme-6">Rp {{ number_format($items->sum('total'), 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            
                            @if($items->first()->gambar)
                            <div class="mt-2 text-xs">
                                <a href="{{ asset($items->first()->gambar) }}" target="_blank" class="text-blue-500 hover:underline"><i data-feather="image" class="w-3 h-3 inline-block"></i> Lihat Bukti Nota</a>
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-gray-500 italic p-4 border rounded border-dashed">Belum ada pencatatan barang untuk tanggal ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('btnAddItem').addEventListener('click', function() {
        const container = document.getElementById('itemsContainer');
        const row = document.createElement('div');
        row.className = 'item-row grid grid-cols-12 gap-2 mb-2';
        row.innerHTML = `
            <div class="col-span-5"><input type="text" name="nama_barang[]" placeholder="Nama Barang" class="input w-full border text-sm" required></div>
            <div class="col-span-3"><input type="number" name="qty[]" placeholder="Qty" class="input w-full border text-sm" value="1" min="1" required></div>
            <div class="col-span-4 flex gap-1">
                <input type="number" name="harga_satuan[]" placeholder="Harga Sat" class="input w-full border text-sm" value="0" min="0" required>
                <button type="button" class="text-red-500 hover:text-red-700 btn-remove-item px-1"><i data-feather="x" class="w-4 h-4"></i></button>
            </div>
        `;
        container.appendChild(row);
        if (typeof feather !== 'undefined') feather.replace();
    });

    document.getElementById('itemsContainer').addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>
@endsection
