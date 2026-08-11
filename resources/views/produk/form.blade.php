@extends('layouts.app')

@section('content')
<div class="content mt-5">
    <div class="intro-y box p-5">
        <h2 class="font-medium text-base mb-5">{{ $title }}</h2>
        <form action="{{ $produk ? route('Produk.update', $kode_barang) : route('Produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($produk)
                @method('PUT')
            @endif
            
            <div class="mb-4">
                <label>Kode Barang</label>
                <input type="text" name="kode_barang" value="{{ $kode_barang }}" class="input w-full border" {{ $produk ? 'readonly' : 'readonly' }}>
            </div>
            
            <div class="mb-4">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ $produk->nama_produk ?? '' }}" class="input w-full border" required>
            </div>
            
            <div class="mb-4">
                <label>Harga</label>
                <input type="number" name="harga" value="{{ $produk->harga ?? '' }}" class="input w-full border" required>
            </div>
            
            <div class="mb-4">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="input w-full border">{{ $produk->deskripsi ?? '' }}</textarea>
            </div>
            
            <div class="mt-5">
                <button type="submit" class="button bg-theme-1 text-white">Simpan</button>
                <a href="{{ route('Produk.index') }}" class="button border text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
