@extends('layouts.app')

@section('content')
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="box" class="w-5 h-5 inline-block mr-2"></i>{{ $title ?? 'Form Produk' }}</h1>
        <p>Kelola data produk dan stok sparepart</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">
    <div class="intro-y box p-6 bg-white rounded-xl shadow-sm border border-gray-100 max-w-2xl">
        <h2 class="font-semibold text-base mb-5 text-gray-800 border-b pb-3">{{ $title }}</h2>
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
