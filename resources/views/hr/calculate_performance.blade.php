@extends('layouts.app')
@section('content')
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Sistem Performa</h1>
                <p>Hitung Rekap Performa Bulanan</p>
            </div>
            <div class="header-actions">
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;"><i data-feather="bell"></i><div class="badge-dot"></div></div>
                <div class="header-btn">
                    <i data-feather="mail"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            <!-- Breadcrumb / Top Bar -->
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">
                    Kalkulasi Rekap Bulanan
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                    <a href="{{ url('/HR/karyawan') }}" class="button border text-gray-700 bg-white" style="display: inline-flex; align-items: center;">
                        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Box Parameter Pilihan Bulan -->
            <div class="intro-y box mt-5">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Filter Periode Performa
                    </h2>
                </div>
                <div class="p-5">
                    <form action="{{ route('hr.calculate_performance') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="w-full sm:w-auto">
                            <label class="block mb-2 font-semibold">Pilih Bulan & Tahun</label>
                            <input type="month" name="periode" class="input w-full border" value="{{ request('periode', date('Y-m')) }}" required>
                        </div>
                        <div class="w-full sm:w-auto">
                            <button type="submit" class="button text-white bg-theme-1 w-full sm:w-auto" style="display: inline-flex; align-items: center;">
                                <i data-feather="refresh-cw" class="w-4 h-4 mr-2"></i> Hitung & Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Box Hasil Tabel Rekap -->
            <div class="intro-y box mt-5 overflow-hidden">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 justify-between">
                    <h2 class="font-medium text-base mr-auto">
                        Daftar Peringkat Karyawan
                    </h2>
                    <button type="button" class="button border text-gray-700 bg-white" style="display: inline-flex; align-items: center;" onclick="window.print()">
                        <i data-feather="printer" class="w-4 h-4 mr-2"></i> Cetak Laporan
                    </button>
                </div>
                
                <div class="p-5">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border-b-2 text-center whitespace-no-wrap">Ranking</th>
                                    <th class="border-b-2 whitespace-no-wrap">Karyawan</th>
                                    <th class="border-b-2 whitespace-no-wrap">Jabatan</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">Total Poin</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">Persentase</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">Level</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">Periode</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Jika ada data dari Controller, maka akan di-looping di sini --}}
                                @if(isset($rekap_list) && count($rekap_list) > 0)
                                    @foreach($rekap_list as $index => $rekap)
                                    <tr>
                                        <td class="border-b text-center font-bold text-lg text-theme-1">{{ $index + 1 }}</td>
                                        <td class="border-b font-medium">{{ $rekap->nama_karyawan }}</td>
                                        <td class="border-b">{{ $rekap->jabatan }}</td>
                                        <td class="border-b text-center font-bold text-blue-600">{{ $rekap->total_poin }}</td>
                                        <td class="border-b text-center">{{ $rekap->persentase }}%</td>
                                        <td class="border-b text-center">
                                            @if($rekap->level == 'Excellent')
                                                <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-700">Excellent</span>
                                            @elseif($rekap->level == 'Good')
                                                <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-700">Good</span>
                                            @else
                                                <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-700">{{ $rekap->level }}</span>
                                            @endif
                                        </td>
                                        <td class="border-b text-center">{{ request('periode', date('Y-m')) }}</td>
                                    </tr>
                                    @endforeach
                                @else
                                {{-- Tampilan Empty State (Sesuai dengan screenshot Anda) --}}
                                <tr>
                                    <td colspan="7" class="text-center border-b py-16">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i data-feather="bar-chart-2" class="w-16 h-16 mb-4 text-gray-300"></i>
                                            <p class="text-lg">Belum ada data performa bulanan</p>
                                            <p class="text-sm mt-1">Pilih periode bulan di atas lalu klik "Hitung & Tampilkan" untuk memproses peringkat.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<style>
    .bg-theme-1 {
        background-color: #1e40af;
    }
    .bg-theme-1:hover {
        background-color: #1e3a8a;
    }
    .text-theme-1 {
        color: #1e40af;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection