@extends('layouts.app')

@section('content')
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="edit" class="w-5 h-5 inline-block mr-2"></i>{{ $title ?? 'Edit Customer' }}</h1>
        <p>Perbarui informasi pelanggan dan perangkat</p>
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
    <div class="intro-y box p-6 bg-white rounded-xl shadow-sm border border-gray-100">
        <h2 class="font-bold text-lg mb-5 border-b pb-3 text-gray-800">{{ $title }}</h2>
        <form action="{{ route('Customer.update', $customer->id_costomer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 md:col-span-6 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">Nama Customer</label>
                    <input type="text" name="nama" value="{{ $customer->cos_nama }}" class="input w-full border rounded-lg px-3 py-2 text-sm" required>
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">No HP</label>
                    <input type="text" name="tlp" value="{{ $customer->cos_hp }}" class="input w-full border rounded-lg px-3 py-2 text-sm" required>
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">Tipe / Device</label>
                    <input type="text" name="type" value="{{ $customer->cos_tipe }}" class="input w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">Model</label>
                    <input type="text" name="model" value="{{ $customer->cos_model }}" class="input w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">No Seri</label>
                    <input type="text" name="seri" value="{{ $customer->cos_no_seri }}" class="input w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="col-span-12 md:col-span-6 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">Keluhan</label>
                    <input type="text" name="keluhan" value="{{ $customer->cos_keluhan }}" class="input w-full border rounded-lg px-3 py-2 text-sm text-red-600 font-semibold">
                </div>

                <div class="col-span-12 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">Alamat</label>
                    <input type="text" name="alamat" value="{{ $customer->cos_alamat }}" class="input w-full border rounded-lg px-3 py-2 text-sm">
                </div>

                <div class="col-span-12 mb-3">
                    <label class="font-semibold text-xs text-gray-600 block mb-1">Keterangan</label>
                    <input type="text" name="ket" value="{{ $customer->cos_keterangan }}" class="input w-full border rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            
            <div class="mt-6 flex gap-2 border-t pt-4">
                <button type="submit" class="button bg-blue-600 text-white px-6 py-2 rounded-lg font-bold text-xs">Simpan Perubahan</button>
                <a href="{{ url()->previous() }}" class="button border text-gray-700 px-4 py-2 rounded-lg text-xs">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
