@extends('layouts.app')

@section('content')
<div class="content" style="margin-top: 40px; padding: 2rem;">
    <!-- Header Section -->
    <div class="intro-y flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 pb-4 border-b border-gray-200">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <div class="p-2.5 bg-blue-600 text-white rounded-xl shadow-sm mr-3">
                    <i data-feather="activity" class="w-6 h-6"></i>
                </div>
                Laporan Customer Service
            </h1>
            <p class="text-gray-600 text-sm mt-1 ml-14">Ringkasan aktivitas dan rekapitulasi data layanan customer.</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center space-x-3 mt-4 sm:mt-0">
            <button type="button" class="flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-all">
                <i data-feather="filter" class="w-4 h-4 mr-2 text-gray-500"></i> Filter Tanggal
            </button>
            <button type="button" class="flex items-center px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium shadow-sm hover:bg-blue-700 transition-all">
                <i data-feather="download" class="w-4 h-4 mr-2"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Statistik Cards (Grid) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 intro-y">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Layanan</p>
                <h3 class="text-3xl font-extrabold text-gray-900 mt-2">0</h3>
            </div>
            <div class="p-4 bg-blue-50 text-blue-600 rounded-2xl">
                <i data-feather="layers" class="w-7 h-7"></i>
            </div>
        </div>
        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Selesai Diproses</p>
                <h3 class="text-3xl font-extrabold text-green-600 mt-2">0</h3>
            </div>
            <div class="p-4 bg-green-50 text-green-600 rounded-2xl">
                <i data-feather="check-circle" class="w-7 h-7"></i>
            </div>
        </div>
        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider">Dalam Antrean</p>
                <h3 class="text-3xl font-extrabold text-amber-500 mt-2">0</h3>
            </div>
            <div class="p-4 bg-amber-50 text-amber-600 rounded-2xl">
                <i data-feather="clock" class="w-7 h-7"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Box -->
    <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm intro-y">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pb-5 border-b border-gray-100 mb-6">
            <div>
                <h3 class="font-bold text-lg text-gray-900">Rekapitulasi Laporan Harian</h3>
                <p class="text-gray-500 text-xs mt-0.5">Data akan diperbarui secara otomatis berdasarkan aktivitas sistem.</p>
            </div>
        </div>
        
        <!-- Empty State -->
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                <i data-feather="file-text" class="w-10 h-10 text-blue-500"></i>
            </div>
            <h4 class="text-gray-800 font-bold text-lg">Belum Ada Data Laporan</h4>
            <p class="text-gray-500 text-sm max-w-md mx-auto mt-1">Saat ini belum ada data transaksi atau layanan yang tercatat untuk ditampilkan pada rekapitulasi laporan ini.</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection