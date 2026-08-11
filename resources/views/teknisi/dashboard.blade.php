@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="tool" class="w-6 h-6 inline-block mr-2"></i>Dashboard Teknisi</h1>                
        <p>Manajemen Perbaikan & Servis</p>
    </div>            
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('sukses') }}</div>
    @endif
    
    @if(session('gagal'))
    <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('gagal') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <!-- Kolom Order Baru -->
        <div class="col-span-12 lg:col-span-6">
            <div class="box p-5">
                <div class="flex items-center border-b pb-3 mb-3">
                    <h2 class="font-bold text-lg"><i data-feather="inbox" class="w-5 h-5 inline-block mr-1"></i> Order Baru (Menunggu Dikerjakan)</h2>
                    <span class="ml-auto bg-theme-6 text-white px-2 py-1 rounded text-xs font-bold">{{ count($orders_baru) }}</span>
                </div>
                
                @forelse($orders_baru as $order)
                <div class="bg-gray-100 p-4 rounded mb-3 border-l-4 border-theme-6">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-bold text-base">{{ $order->trans_kode }}</div>
                            <div class="text-xs text-gray-600">{{ $order->trans_tanggal }}</div>
                        </div>
                        <a href="{{ route('teknisi.input_tindakan', $order->trans_kode) }}" class="button bg-theme-1 text-white text-xs py-1 px-3">Kerjakan</a>
                    </div>
                    <div class="mb-1"><span class="font-semibold text-xs">Customer:</span> {{ $order->customer->cos_nama ?? '-' }}</div>
                    <div class="mb-1"><span class="font-semibold text-xs">Device:</span> {{ $order->customer->cos_tipe ?? '-' }} {{ $order->customer->cos_model ?? '-' }}</div>
                    <div class="text-sm text-theme-6 italic mt-2 border-t pt-2 border-gray-300">
                        <i data-feather="alert-circle" class="w-4 h-4 inline-block"></i> Keluhan: {{ $order->customer->cos_keluhan ?? '-' }}
                    </div>
                </div>
                @empty
                <div class="text-center p-5 text-gray-500 italic border rounded border-dashed">
                    Tidak ada order servis baru.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Kolom Sedang Dikerjakan (Repairing) -->
        <div class="col-span-12 lg:col-span-6">
            <div class="box p-5">
                <div class="flex items-center border-b pb-3 mb-3">
                    <h2 class="font-bold text-lg"><i data-feather="settings" class="w-5 h-5 inline-block mr-1"></i> Sedang Dikerjakan (Repairing)</h2>
                    <span class="ml-auto bg-theme-1 text-white px-2 py-1 rounded text-xs font-bold">{{ count($orders_repairing) }}</span>
                </div>
                
                @forelse($orders_repairing as $order)
                <div class="bg-blue-50 p-4 rounded mb-3 border-l-4 border-theme-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-bold text-base">{{ $order->trans_kode }}</div>
                            <div class="text-xs text-gray-600">{{ $order->trans_tanggal }}</div>
                        </div>
                        <a href="{{ route('teknisi.input_tindakan', $order->trans_kode) }}" class="button border text-xs py-1 px-3">Update Tindakan</a>
                    </div>
                    <div class="mb-1"><span class="font-semibold text-xs">Customer:</span> {{ $order->customer->cos_nama ?? '-' }}</div>
                    <div class="mb-1"><span class="font-semibold text-xs">Device:</span> {{ $order->customer->cos_tipe ?? '-' }} {{ $order->customer->cos_model ?? '-' }}</div>
                    <div class="text-sm mt-2">
                        <span class="bg-theme-1 text-white px-2 py-1 rounded text-xs">Diproses</span>
                    </div>
                </div>
                @empty
                <div class="text-center p-5 text-gray-500 italic border rounded border-dashed">
                    Tidak ada order yang sedang dikerjakan.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
