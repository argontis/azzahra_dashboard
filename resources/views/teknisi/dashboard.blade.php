@extends('layouts.app')

@section('content')
<div class="content mt-5 p-2">
    <!-- Header -->
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
                Order Baru: {{ $total_order_baru }}
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
    <form method="GET" action="{{ route('teknisi.index') }}" class="flex flex-col md:flex-row gap-4 mb-6 bg-white p-2 rounded-lg shadow-sm border border-gray-100 items-stretch md:items-center">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" name="search" value="{{ $search }}" class="bg-transparent border-0 text-gray-900 text-sm focus:ring-0 w-full pl-10 p-2 placeholder-gray-400" placeholder="Cari berdasarkan nama customer, invoice, atau device...">
        </div>
        <div class="flex gap-2 items-center pr-2 border-l border-gray-100 pl-4">
            <select name="status" onchange="this.form.submit()" class="bg-transparent border border-gray-200 text-gray-700 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2 min-w-[130px]">
                <option value="Semua Status" {{ $status_filter == 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
                <option value="Order Baru" {{ $status_filter == 'Order Baru' ? 'selected' : '' }}>Order Baru</option>
                <option value="Sedang Dikerjakan" {{ $status_filter == 'Sedang Dikerjakan' ? 'selected' : '' }}>Sedang Dikerjakan</option>
                <option value="Selesai" {{ $status_filter == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            <select name="periode" onchange="this.form.submit()" class="bg-transparent border border-gray-200 text-gray-700 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block p-2 min-w-[110px]">
                <option value="Semua Waktu" {{ $periode_filter == 'Semua Waktu' ? 'selected' : '' }}>Semua Waktu</option>
                <option value="Hari Ini" {{ $periode_filter == 'Hari Ini' ? 'selected' : '' }}>Hari Ini</option>
                <option value="7 Hari Terakhir" {{ $periode_filter == '7 Hari Terakhir' ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="Bulan Ini" {{ $periode_filter == 'Bulan Ini' ? 'selected' : '' }}>Bulan Ini</option>
            </select>
            <a href="{{ route('teknisi.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-slate-400 rounded-md hover:bg-slate-500 transition-colors">
                Reset
            </a>
        </div>
    </form>

    <!-- 3-Column Service Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($orders as $order)
            <div onclick="window.location='{{ route('teknisi.input_tindakan', $order->trans_kode) }}'" class="bg-white rounded-2xl shadow-sm border border-gray-100/90 p-5 flex flex-col justify-between hover:shadow-md hover:border-blue-200 transition-all cursor-pointer group">
                <div>
                    <!-- Header Card: Avatar + Customer Name + Invoice + Date + Status Pill -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200/60 flex items-center justify-center text-slate-500 shrink-0 mt-0.5 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                <i data-feather="user" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <a href="{{ route('teknisi.input_tindakan', $order->trans_kode) }}" class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors text-sm leading-snug block">
                                    {{ $order->customer->cos_nama ?? 'Customer' }}
                                </a>
                                <div class="text-[11px] text-gray-400 font-medium mt-0.5">Invoice: <span class="text-gray-600 font-mono">{{ $order->trans_kode }}</span></div>
                                <div class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-1">
                                    <i data-feather="calendar" class="w-3 h-3 text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($order->cos_tanggal ?? $order->trans_tanggal)->translatedFormat('l, d - F - Y') }}
                                </div>
                            </div>
                        </div>
                        @if($order->trans_status == 'Baru')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-600 border border-amber-200 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Baru
                            </span>
                        @elseif($order->trans_status == 'Diproses')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-600 border border-blue-200 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Sedang Dikerjakan
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200 shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $order->trans_status }}
                            </span>
                        @endif
                    </div>

                    <!-- Device Info List -->
                    <div class="space-y-1.5 text-xs text-gray-600 mb-3.5 pt-2 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <i data-feather="monitor" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                            <span class="font-semibold text-gray-800">{{ $order->customer->cos_tipe ?? '-' }} {{ $order->customer->cos_model ?? '' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-feather="hash" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                            <span class="font-mono text-gray-600">SN: {{ $order->customer->cos_no_seri ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-feather="phone" class="w-3.5 h-3.5 text-gray-400 shrink-0"></i>
                            @php
                                $hp = $order->customer->cos_hp ?? '-';
                                $masked_hp = strlen($hp) > 4 ? substr($hp, 0, -4) . 'XXXX' : $hp;
                            @endphp
                            <span>{{ $masked_hp }}</span>
                        </div>
                    </div>

                    <!-- Keluhan Section -->
                    <div class="mb-3">
                        <div class="text-[11px] font-bold text-gray-700 mb-1">Keluhan:</div>
                        <div class="bg-gray-50 border border-gray-200/80 rounded-lg p-2.5 text-xs text-gray-700 leading-relaxed min-h-[38px]">
                            {{ $order->customer->cos_keluhan ?? 'Tidak ada catatan keluhan.' }}
                        </div>
                    </div>

                    <!-- Alamat Section -->
                    <div class="mb-4">
                        <div class="text-[11px] font-bold text-gray-700 mb-1">Alamat:</div>
                        <div class="text-xs text-gray-500 flex items-start gap-1.5">
                            <i data-feather="map-pin" class="w-3.5 h-3.5 text-gray-400 shrink-0 mt-0.5"></i>
                            <span class="line-clamp-2">{{ $order->customer->cos_alamat ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Bottom Button -->
                <div class="pt-2">
                    @if($order->trans_status == 'Baru')
                        <a href="{{ route('teknisi.input_tindakan', $order->trans_kode) }}" class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs tracking-wide shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                            Mulai Perbaikan
                        </a>
                    @else
                        <a href="{{ route('teknisi.input_tindakan', $order->trans_kode) }}" class="w-full py-2.5 px-4 rounded-xl bg-blue-800 hover:bg-blue-900 text-white font-semibold text-xs tracking-wide shadow-sm flex items-center justify-center gap-1.5 transition-colors">
                            Lanjutkan Perbaikan
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-12 bg-white rounded-2xl p-12 text-center text-gray-500 border border-gray-100 shadow-sm">
                <i data-feather="inbox" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                <h3 class="font-bold text-gray-800 text-base mb-1">Tidak ada order servis yang sesuai</h3>
                <p class="text-xs text-gray-400">Order baru yang diinput Kasir / CS akan langsung muncul di sini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 mb-8">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection
