@extends('layouts.app')

@section('content')
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="award" class="w-6 h-6 inline-block mr-2"></i>Generator Sertifikat</h1>
        <p>Kelola dan cetak sertifikat karyawan</p>
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
        <h2 class="text-base font-semibold text-gray-800 mb-3">Manajemen Cetak Sertifikat</h2>
        <p class="text-sm text-gray-600">Ini adalah halaman untuk mengelola dan mencetak sertifikat karyawan. Form atau sistem cetak sertifikat bisa Anda buat di sini.</p>
    </div>
</div>
@endsection