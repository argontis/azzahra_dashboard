@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Transaksi Kasir</h1>                
        <p>Daftar Customer (Transaksi Aktif)</p>
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

    <div class="intro-y box p-5 mt-5">
        <form method="GET" action="{{ route('kasir.index') }}" class="mb-4 flex gap-2">
            <input type="text" name="search" class="input w-full sm:w-64 box border" placeholder="Cari customer/transaksi..." value="{{ request('search') }}">
            <button type="submit" class="button bg-theme-1 text-white">Cari</button>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-report -mt-2 w-full">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">NAMA</th>
                        <th class="whitespace-no-wrap">STATUS</th>
                        <th class="whitespace-no-wrap">TANGGAL</th>
                        <th class="text-center whitespace-no-wrap">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis ?? [] as $row)
                    <tr class="intro-x">
                        <td>
                            <div class="font-medium whitespace-no-wrap">{{ $row->customer->cos_nama ?? '-' }}</div>
                            <div class="text-gray-600 text-xs">{{ $row->customer->cos_hp ?? '-' }}</div>
                        </td>
                        <td>
                            <div class="font-medium text-theme-1">{{ $row->trans_status }}</div>
                        </td>
                        <td>
                            <div class="font-medium">{{ $row->cos_tanggal }}</div>
                            <div class="text-gray-600 text-xs">{{ $row->cos_jam }}</div>
                        </td>
                        <td class="table-report__action w-56 text-center">
                            @if($row->trans_status == 'Return')
                                <a href="#" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                                    <i data-feather="credit-card" class="w-4 h-4 mr-2"></i> Return
                                </a>
                            @else
                                <a href="{{ route('kasir.cari', $row->trans_kode) }}" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-6 text-white">
                                    <i data-feather="credit-card" class="w-4 h-4 mr-2"></i> Bayar
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center p-5 text-gray-600">Tidak ada transaksi aktif.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $transaksis->links() ?? '' }}
        </div>
    </div>
</div>
@endsection
