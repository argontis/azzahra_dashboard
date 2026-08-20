@extends('layouts.app')

@section('content')
<div class="content mt-5 p-2">
    <div class="flex justify-between items-start mb-6 mt-2">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Dashboard Teknisi</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola order service yang perlu diperbaiki</p>
        </div>
        <div class="flex items-center">
            <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-blue-500 bg-white border border-blue-200 rounded-full shadow-sm hover:bg-blue-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Order Baru: {{ count($orders_baru) }}
            </button>
        </div>
    </div>

    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded-lg mb-6 shadow-sm">{{ session('sukses') }}</div>
    @endif

    @if(session('gagal'))
    <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded-lg mb-6 shadow-sm">{{ session('gagal') }}</div>
    @endif

    <!-- Filter Bar -->
    <div class="flex flex-col md:flex-row gap-4 mb-6 bg-white p-2 rounded-lg shadow-sm border border-gray-100">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" class="bg-transparent border-0 text-gray-900 text-sm focus:ring-0 w-full pl-10 p-2" placeholder="Cari berdasarkan nama customer, invoice, atau device...">
        </div>
        <div class="flex gap-2 items-center pr-2 border-l border-gray-100 pl-4">
            <select class="bg-transparent border border-gray-200 text-gray-700 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2 min-w-[130px]">
                <option>Order Baru</option>
                <option>Sedang Diproses</option>
                <option>Selesai</option>
            </select>
            <select class="bg-transparent border border-gray-200 text-gray-700 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2 min-w-[110px]">
                <option>Hari Ini</option>
                <option>7 Hari Terakhir</option>
                <option>Bulan Ini</option>
            </select>
            <button class="px-4 py-2 text-sm font-medium text-white bg-slate-400 rounded-md hover:bg-slate-500 transition-colors">
                Reset
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="mb-6">
        @if(count($orders_baru) == 0 && count($orders_repairing) == 0)
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 flex flex-col items-center justify-center text-center min-h-[320px]">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-5 border border-gray-100 shadow-sm">
                    <svg class="w-8 h-8 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Tidak ada order baru</h3>
                <p class="text-gray-500 text-sm max-w-sm leading-relaxed">Semua order sudah diproses. Silakan tunggu order baru dari Customer Service.</p>
            </div>
        @else
            <!-- Order Lists -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kolom Order Baru -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            Order Baru (Menunggu Dikerjakan)
                        </h3>
                        <span class="bg-blue-100 text-blue-700 py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ count($orders_baru) }}</span>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($orders_baru as $order)
                            <div class="p-4 border border-gray-100 rounded-lg hover:shadow-md transition-shadow bg-gray-50">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $order->trans_kode }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $order->cos_tanggal }}</div>
                                    </div>
                                    <a href="{{ route('teknisi.input_tindakan', $order->trans_kode) }}" class="text-xs bg-blue-600 hover:bg-blue-700 text-white py-1.5 px-3 rounded-md transition-colors font-medium">Kerjakan</a>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm mt-3">
                                    <div>
                                        <span class="text-gray-500 block text-xs mb-0.5">Customer</span>
                                        <span class="font-medium text-gray-800">{{ $order->customer->cos_nama ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block text-xs mb-0.5">Device</span>
                                        <span class="font-medium text-gray-800">{{ $order->customer->cos_tipe ?? '-' }} {{ $order->customer->cos_model ?? '-' }}</span>
                                    </div>
                                </div>
                                @if(!empty($order->customer->cos_keluhan))
                                <div class="mt-3 pt-3 border-t border-gray-200 text-sm">
                                    <span class="text-gray-500 block text-xs mb-1">Keluhan</span>
                                    <p class="text-gray-700 italic">{{ $order->customer->cos_keluhan }}</p>
                                </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                Tidak ada order servis baru.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Kolom Sedang Dikerjakan -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Sedang Dikerjakan (Repairing)
                        </h3>
                        <span class="bg-orange-100 text-orange-700 py-0.5 px-2.5 rounded-full text-xs font-semibold">{{ count($orders_repairing) }}</span>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($orders_repairing as $order)
                            <div class="p-4 border border-gray-100 rounded-lg hover:shadow-md transition-shadow bg-blue-50/50">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <div class="font-bold text-gray-800">{{ $order->trans_kode }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">{{ $order->cos_tanggal }}</div>
                                    </div>
                                    <a href="{{ route('teknisi.input_tindakan', $order->trans_kode) }}" class="text-xs bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 py-1.5 px-3 rounded-md transition-colors font-medium">Update Tindakan</a>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-sm mt-3">
                                    <div>
                                        <span class="text-gray-500 block text-xs mb-0.5">Customer</span>
                                        <span class="font-medium text-gray-800">{{ $order->customer->cos_nama ?? '-' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block text-xs mb-0.5">Device</span>
                                        <span class="font-medium text-gray-800">{{ $order->customer->cos_tipe ?? '-' }} {{ $order->customer->cos_model ?? '-' }}</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-3 border-t border-blue-100">
                                    <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-md text-xs font-medium">Diproses</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500 text-sm bg-gray-50 rounded-lg border border-dashed border-gray-200">
                                Tidak ada order yang sedang dikerjakan.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 flex items-center gap-2 text-base">
                <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
                Aktivitas Terbaru
            </h3>
            <a href="#" class="text-sm text-blue-500 hover:text-blue-600 hover:underline">Lihat Semua</a>
        </div>
        
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div class="mt-0.5">
                <h4 class="text-sm font-medium text-gray-800">Belum ada order baru</h4>
                <p class="text-xs text-gray-500 mt-1">Order baru akan muncul di sini</p>
            </div>
        </div>
    </div>
</div>
@endsection
