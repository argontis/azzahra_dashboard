@extends('layouts.app')

@section('content')
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="package" class="w-6 h-6 inline-block mr-2"></i>Produk</h1>                
        <p>Management Data Produk & Sparepart</p>
    </div>            
    <div class="header-actions">
        <form action="{{ route('Produk.index') }}" method="GET" class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" name="search" class="search-input" placeholder="Cari nama, kode, harga..." value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('Produk.index') }}" class="text-xs text-gray-400 hover:text-gray-600 mr-2" title="Reset">✕</a>
            @endif
        </form>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot" style="display: none;"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
            <span class="topbar-mail-badge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 9999px; font-size: 10px; font-weight: bold; min-width: 16px; height: 16px; line-height: 16px; text-align: center; padding: 0 4px;"></span>
        </div>
    </div>
</header>

<div class="content-area">   
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
    <div class="intro-y mb-4">
        <div class="alert alert-danger show mb-2" role="alert">
            <div class="flex items-center">
                <div class="font-medium text-lg">{{ session('error') }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
        <div class="text-sm font-semibold text-gray-700">
            Total Data: <span class="text-blue-600 font-bold">{{ $produks->total() }}</span> Produk
            @if(request('search'))
                <span class="text-xs text-gray-500 font-normal ml-2">(Hasil filter: "{{ request('search') }}")</span>
            @endif
        </div>
        <a href="{{ route('Produk.create') }}" class="button inline-flex items-center gap-2 text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all px-4 py-2 rounded-lg font-semibold text-xs shrink-0">            
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Produk
        </a>
    </div>

    <!-- Data List -->
    <div class="intro-y datatable-wrapper box p-4 sm:p-5 bg-white rounded-xl shadow-sm border border-gray-100 w-full">
        <div class="table-responsive-container overflow-x-auto w-full">
            <table class="table table-report table-report--bordered display w-full">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase">
                        <th class="whitespace-no-wrap w-16 text-center">GAMBAR</th>
                        <th class="whitespace-no-wrap">KODE BARANG</th>
                        <th class="whitespace-no-wrap">NAMA PRODUK</th>
                        <th class="whitespace-no-wrap">DESKRIPSI</th>
                        <th class="text-right whitespace-no-wrap">HARGA</th>
                        <th class="text-center whitespace-no-wrap w-44">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($produks ?? [] as $row)
                    @php
                        $firstImg = null;
                        if (!empty($row->gambar)) {
                            $imgs = explode(',', $row->gambar);
                            $firstImg = trim($imgs[0] ?? '');
                        }
                    @endphp
                    <tr class="intro-x hover:bg-gray-50/80 transition-colors">
                        <td class="text-center py-3">
                            <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex items-center justify-center mx-auto shadow-xs">
                                @if($firstImg)
                                    <img src="{{ asset('uploads/produk/' . $firstImg) }}" alt="{{ $row->nama_produk }}" class="object-cover w-full h-full" onerror="this.onerror=null; this.parentElement.innerHTML='<span class=\'text-gray-400 text-xs font-semibold\'>PRD</span>';">
                                @else
                                    <span class="text-gray-400 text-xs font-semibold">PRD</span>
                                @endif
                            </div>
                        </td>
                        <td class="font-mono text-xs font-bold text-gray-700">{{ $row->kode_barang }}</td>
                        <td class="font-semibold text-gray-900">{{ $row->nama_produk }}</td>
                        <td class="text-gray-500 text-xs max-w-xs truncate" title="{{ $row->deskripsi }}">{{ $row->deskripsi ?: '-' }}</td>
                        <td class="text-right font-bold text-gray-800">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                        <td class="table-report__action">
                            <div class="flex justify-center items-center gap-2">
                                <a class="flex items-center text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-md transition-colors" href="{{ route('Produk.edit', $row->kode_barang) }}">
                                    <i data-feather="edit-2" class="w-3.5 h-3.5 mr-1"></i> Edit
                                </a>
                                <form action="{{ route('Produk.destroy', $row->kode_barang) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk {{ $row->nama_produk }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-md transition-colors">
                                        <i data-feather="trash-2" class="w-3.5 h-3.5 mr-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i data-feather="package" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
                            <p class="font-medium text-gray-600">Tidak ada data produk yang ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($produks instanceof \Illuminate\Pagination\LengthAwarePaginator && $produks->hasPages())
        <div class="mt-5 pt-4 border-t border-gray-100">
            {{ $produks->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
