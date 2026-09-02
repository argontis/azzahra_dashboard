@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="package" class="w-6 h-6 inline-block mr-2"></i>Produk</h1>                
        <p>Management Data Produk</p>
    </div>            
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot" style="display: none;"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
            <span class="topbar-mail-badge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 9999px; font-size: 10px; font-weight: bold; min-width: 16px; height: 16px; line-height: 16px; text-align: center; padding: 0 4px;"></span>
        </div>
        <a href="{{ route('Produk.create') }}" class="button text-white bg-theme-1 shadow-md hover:bg-theme-2 transition-all">            
            Tambah Produk
        </a>    
    </div>
</header>

<div class="content mt-5">   
    @if(session('sukses'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('sukses') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#10b981'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <div class="intro-y mt-5">
        <div class="alert alert-danger show mb-2" role="alert">
            <div class="flex items-center">
                <div class="font-medium text-lg">{{ session('error') }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Data List -->
    <div class="intro-y box p-5 mt-5">
        <div class="overflow-x-auto">
            <table class="table table-report -mt-2 w-full">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">KODE BARANG</th>
                        <th class="whitespace-no-wrap">NAMA PRODUK</th>
                        <th class="text-center whitespace-no-wrap">HARGA</th>
                        <th class="text-center whitespace-no-wrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks ?? [] as $row)
                    <tr class="intro-x">
                        <td>{{ $row->kode_barang }}</td>
                        <td>{{ $row->nama_produk }}</td>
                        <td class="text-center">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                        <td class="table-report__action w-56">
                            <div class="flex justify-center items-center">
                                <a class="flex items-center mr-3" href="{{ route('Produk.edit', $row->kode_barang) }}">
                                    <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit
                                </a>
                                <form action="{{ route('Produk.destroy', $row->kode_barang) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center text-theme-6">
                                        <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-5 text-gray-600">Tidak ada data produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
