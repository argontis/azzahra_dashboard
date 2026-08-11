@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="bar-chart-2" class="w-6 h-6 inline-block mr-2"></i>Laporan Kasir</h1>                
        <p>Ringkasan Pendapatan Hari Ini: {{ $date }}</p>
    </div>            
</header>

<div class="content mt-5">   
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Stat DP -->
        <div class="box p-5">
            <div class="flex items-center">
                <div class="w-2/4">
                    <div class="text-gray-600 text-sm">Total DP (Hari Ini)</div>
                    <div class="text-xl font-bold mt-1">Rp {{ number_format($dp_total, 0, ',', '.') }}</div>
                </div>
                <div class="w-2/4 flex justify-end">
                    <div class="bg-blue-100 text-blue-600 rounded-full p-2"><i data-feather="dollar-sign"></i></div>
                </div>
            </div>
        </div>
        
        <!-- Stat Pelunasan -->
        <div class="box p-5">
            <div class="flex items-center">
                <div class="w-2/4">
                    <div class="text-gray-600 text-sm">Total Lunas (Hari Ini)</div>
                    <div class="text-xl font-bold mt-1">Rp {{ number_format($lunas_total, 0, ',', '.') }}</div>
                </div>
                <div class="w-2/4 flex justify-end">
                    <div class="bg-green-100 text-green-600 rounded-full p-2"><i data-feather="credit-card"></i></div>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="box p-5 bg-theme-1 text-white">
            <div class="flex items-center">
                <div class="w-2/4">
                    <div class="text-blue-100 text-sm">TOTAL PENDAPATAN</div>
                    <div class="text-2xl font-bold mt-1">Rp {{ number_format($total_all, 0, ',', '.') }}</div>
                </div>
                <div class="w-2/4 flex justify-end">
                    <div class="bg-white text-theme-1 rounded-full p-2"><i data-feather="pie-chart"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="intro-y box p-5 mt-5">
        <h2 class="font-bold text-lg border-b pb-2 mb-4">Rincian Transaksi Hari Ini</h2>
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
                    @forelse($payments ?? [] as $row)
                    <tr class="intro-x">
                        <td>
                            <div class="font-medium">{{ $row->dtl_tanggal }}</div>
                            <div class="text-gray-600 text-xs">{{ $row->dtl_jam }}</div>
                        </td>
                        <td>
                            <div class="font-medium">{{ $row->transaksi->customer->cos_nama ?? '-' }}</div>
                        </td>
                        <td>
                            <span class="px-2 py-1 rounded {{ $row->dtl_status == 'DP' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">{{ $row->dtl_status }}</span>
                        </td>
                        <td>
                            <div class="font-medium">{{ $row->dtl_jenis_bayar }}</div>
                            <div class="text-gray-600 text-xs">{{ $row->dtl_bank != '-' ? $row->dtl_bank : '' }}</div>
                        </td>
                        <td class="text-right font-medium">Rp {{ number_format($row->dtl_jml_bayar, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-5 text-gray-600">Tidak ada transaksi hari ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
