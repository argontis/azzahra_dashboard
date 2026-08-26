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
                    @php
                        $customer = $row->customer;
                        $isPriority = $customer && (($customer->cos_tier === 'prioritas') || ($customer->cos_score >= 5) || ($customer->total_transaksi >= 5));
                        $isLoyal = $customer && (($customer->cos_tier === 'loyal') || ($customer->cos_score >= 3 && !$isPriority));
                    @endphp
                    <tr class="intro-x {{ $isPriority ? 'bg-amber-50/50' : '' }}">
                        <td>
                            <div class="font-medium whitespace-no-wrap flex items-center gap-1.5">
                                <span>{{ $customer->cos_nama ?? '-' }}</span>
                                @if($isPriority)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        👑 VIP
                                    </span>
                                @elseif($isLoyal)
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                        ⭐ Loyal
                                    </span>
                                @endif
                            </div>
                            <div class="text-gray-600 text-xs">{{ $customer->cos_hp ?? '-' }}</div>
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
