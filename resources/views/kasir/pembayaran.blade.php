@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="check-circle" class="w-6 h-6 inline-block mr-2"></i>Daftar Pembayaran</h1>                
        <p>Riwayat Pembayaran Masuk (DP & Lunas)</p>
    </div>            
</header>

<div class="content mt-5">   
    <div class="intro-y box p-5 mt-5">
        <form method="GET" action="{{ route('kasir.pembayaran', $filter) }}" class="mb-4 flex gap-2">
            <input type="text" name="search" class="input w-full sm:w-64 box border" placeholder="Cari nama/kode..." value="{{ request('search') }}">
            <button type="submit" class="button bg-theme-1 text-white">Cari</button>
        </form>

        <div class="mb-4">
            <a href="{{ route('kasir.pembayaran') }}" class="button border {{ !$filter ? 'bg-theme-1 text-white' : '' }}">Semua</a>
            <a href="{{ route('kasir.pembayaran', 'dp') }}" class="button border {{ $filter == 'dp' ? 'bg-theme-1 text-white' : '' }}">DP</a>
            <a href="{{ route('kasir.pembayaran', 'lunas') }}" class="button border {{ $filter == 'lunas' ? 'bg-theme-1 text-white' : '' }}">Lunas</a>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-report -mt-2 w-full">
                <thead>
                    <tr>
                        <th class="whitespace-no-wrap">WAKTU</th>
                        <th class="whitespace-no-wrap">CUSTOMER</th>
                        <th class="whitespace-no-wrap">STATUS</th>
                        <th class="whitespace-no-wrap">METODE</th>
                        <th class="text-right whitespace-no-wrap">JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembayarans ?? [] as $row)
                    <tr class="intro-x cursor-pointer hover:bg-gray-100" onclick="window.location='{{ route('kasir.cari', $row->trans_kode) }}'">
                        <td>
                            <div class="font-medium">{{ $row->dtl_tanggal }}</div>
                            <div class="text-gray-600 text-xs">{{ $row->dtl_jam }}</div>
                        </td>
                        <td>
                            <div class="font-medium">{{ $row->transaksi->customer->cos_nama ?? '-' }}</div>
                            <div class="text-gray-600 text-xs text-theme-1">{{ $row->trans_kode }}</div>
                        </td>
                        <td>
                            <div class="font-medium text-theme-9">{{ $row->dtl_status }}</div>
                        </td>
                        <td>
                            <div class="font-medium">{{ $row->dtl_jenis_bayar }}</div>
                            <div class="text-gray-600 text-xs">{{ $row->dtl_bank != '-' ? $row->dtl_bank : '' }}</div>
                        </td>
                        <td class="text-right font-medium">Rp {{ number_format($row->dtl_jml_bayar, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-5 text-gray-600">Tidak ada data pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $pembayarans->links() ?? '' }}
        </div>
    </div>
</div>
@endsection
