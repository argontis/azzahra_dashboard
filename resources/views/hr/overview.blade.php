@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    corePlugins: {
      preflight: false,
    }
  }
</script>

<div class="content mt-5" style="padding-top: 20px;">
    <!-- Header Area -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 pb-4 border-b border-gray-100" style="margin-top: 10px;">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg" style="background-color: #eff6ff; color: #2563eb;">
                    <i data-feather="home" class="w-6 h-6"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 tracking-tight" style="font-size: 1.875rem; color: #1f2937;">HR Overview</h1>
            </div>
            <div class="flex items-center gap-2 mt-2 text-gray-500 font-medium" style="color: #6b7280;">
                <i data-feather="calendar" class="w-4 h-4"></i>
                <span>{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
            </div>
        </div>
        <div class="flex items-center gap-3 mt-4 md:mt-0">
            <a href="{{ route('hr.absensi') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 transition" style="background-color: #fff; border: 1px solid #e5e7eb; color: #374151;">
                <i data-feather="clock" class="w-4 h-4 text-blue-500"></i> Absensi
            </a>
            <a href="{{ route('hr.kpi') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 transition" style="background-color: #fff; border: 1px solid #e5e7eb; color: #374151;">
                <i data-feather="bar-chart-2" class="w-4 h-4 text-purple-500"></i> KPI
            </a>
            <a href="{{ route('hr.pencatatan') }}" class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-semibold text-gray-700 hover:bg-gray-50 transition" style="background-color: #fff; border: 1px solid #e5e7eb; color: #374151;">
                <i data-feather="package" class="w-4 h-4 text-indigo-500"></i> Pencatatan
            </a>
        </div>
    </div>

<div class="space-y-6">
    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-gray-700 font-bold mb-4">Filter Periode</h3>
        <form method="GET" action="{{ route('hr.index') }}">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <button type="submit" name="periode" value="hari_ini" class="px-5 py-2 rounded-full text-sm font-semibold transition {{ $selected_periode == 'hari_ini' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-blue-600 border border-blue-600 hover:bg-blue-50' }}">
                    Hari Ini
                </button>
                <button type="submit" name="periode" value="minggu_ini" class="px-5 py-2 rounded-full text-sm font-semibold transition {{ $selected_periode == 'minggu_ini' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-blue-600 border border-blue-600 hover:bg-blue-50' }}">
                    Minggu Ini
                </button>
                <button type="submit" name="periode" value="bulan_ini" class="px-5 py-2 rounded-full text-sm font-semibold transition {{ $selected_periode == 'bulan_ini' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-blue-600 border border-blue-600 hover:bg-blue-50' }}">
                    Bulan Ini
                </button>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="relative">
                    <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" class="pl-3 pr-10 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:ring-blue-500 focus:border-blue-500">
                    <i data-feather="calendar" class="w-4 h-4 text-gray-400 absolute right-3 top-2.5 pointer-events-none"></i>
                </div>
                <button type="submit" name="periode" value="tanggal" class="px-4 py-2 border border-blue-600 text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-50 transition">
                    Filter Tanggal
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Hadir Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative group hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                        <i data-feather="users" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full">HARI INI</span>
                </div>
                <h4 class="text-gray-500 text-sm font-medium">Total Kehadiran</h4>
                <div class="mt-2 mb-1 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-800">{{ $stats['hadir'] }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-6">Karyawan hadir tepat waktu</p>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-1 text-green-500 text-xs font-bold">
                        <i data-feather="trending-up" class="w-3 h-3"></i> Active
                    </div>
                    <a href="{{ route('hr.absensi') }}" class="text-blue-500 text-xs font-semibold hover:text-blue-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                        View Details <i data-feather="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Izin Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative group hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-orange-400"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-orange-500">
                        <i data-feather="clock" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-orange-50 text-orange-600 text-xs font-bold rounded-full">WARNING</span>
                </div>
                <h4 class="text-gray-500 text-sm font-medium">Izin</h4>
                <div class="mt-2 mb-1 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-800">{{ $stats['izin'] }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-6">Perlu review dan tindak lanjut</p>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-1 text-orange-500 text-xs font-bold">
                        <i data-feather="alert-circle" class="w-3 h-3"></i> Review
                    </div>
                    <a href="{{ route('hr.absensi') }}" class="text-blue-500 text-xs font-semibold hover:text-blue-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                        View Details <i data-feather="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Alpa Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative group hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-red-400"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center text-red-500">
                        <i data-feather="user-x" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-red-50 text-red-600 text-xs font-bold rounded-full">ALPHA</span>
                </div>
                <h4 class="text-gray-500 text-sm font-medium">Tidak Hadir</h4>
                <div class="mt-2 mb-1 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-800">{{ $stats['alpa'] }}</span>
                </div>
                <p class="text-xs text-gray-400 mb-6">Tanpa keterangan</p>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-1 text-red-500 text-xs font-bold">
                        <i data-feather="alert-triangle" class="w-3 h-3"></i> Critical
                    </div>
                    <a href="{{ route('hr.absensi') }}" class="text-blue-500 text-xs font-semibold hover:text-blue-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                        View Details <i data-feather="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- KPI Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative group hover:shadow-md transition">
            <div class="absolute top-0 left-0 w-full h-1 bg-indigo-400"></div>
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                        <i data-feather="bar-chart" class="w-5 h-5"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-full">BULANAN</span>
                </div>
                <h4 class="text-gray-500 text-sm font-medium">Rata-rata KPI</h4>
                <div class="mt-2 mb-1 flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-800">0</span>
                </div>
                <p class="text-xs text-gray-400 mb-6">Performa tim bulan ini</p>
                
                <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-1 text-green-500 text-xs font-bold">
                        <i data-feather="trending-up" class="w-3 h-3"></i> Good
                    </div>
                    <a href="{{ route('hr.kpi') }}" class="text-blue-500 text-xs font-semibold hover:text-blue-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                        View Details <i data-feather="arrow-right" class="w-3 h-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Kehadiran -->
        <div class="col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-gray-800 font-bold mb-6">Komposisi Kehadiran Hari Ini</h3>
            <div class="flex flex-col md:flex-row items-center justify-center gap-8">
                <!-- Placeholder for Chart -->
                <div class="relative w-48 h-48">
                    <canvas id="attendanceChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-3xl font-bold text-gray-800">{{ $stats['hadir'] }}</span>
                        <span class="text-xs text-gray-500">Hadir</span>
                    </div>
                </div>
                
                <!-- Legend -->
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        <span class="text-sm font-medium text-gray-700 w-24">Hadir</span>
                        <span class="text-sm font-bold text-gray-900">{{ $stats['hadir'] }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-blue-400"></div>
                        <span class="text-sm font-medium text-gray-700 w-24">Izin/Cuti</span>
                        <span class="text-sm font-bold text-gray-900">{{ $stats['izin'] }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-orange-400"></div>
                        <span class="text-sm font-medium text-gray-700 w-24">Terlambat</span>
                        <span class="text-sm font-bold text-gray-900">{{ $stats['telat'] }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-red-400"></div>
                        <span class="text-sm font-medium text-gray-700 w-24">Alpha</span>
                        <span class="text-sm font-bold text-gray-900">{{ $stats['alpa'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Absensi Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-gray-800 font-bold mb-6">Detail Absensi</h3>
            
            <div class="space-y-6">
                @if($stats['alpa'] == 0 && $stats['telat'] == 0 && $stats['izin'] == 0)
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-green-50 text-green-500 rounded-full shrink-0">
                        <i data-feather="check-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-gray-800 text-sm">Semua Hadir!</h4>
                            <span class="text-xs text-gray-400">Today</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Tidak ada masalah kehadiran dalam periode ini</p>
                    </div>
                </div>
                @endif

                @if($stats['alpa'] > 0)
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-red-50 text-red-500 rounded-full shrink-0">
                        <i data-feather="x-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $stats['alpa'] }} Karyawan Alpha</h4>
                            <span class="text-xs text-gray-400">Today</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Harap segera tindak lanjuti ketidakhadiran tanpa keterangan.</p>
                    </div>
                </div>
                @endif
                
                @if($stats['telat'] > 0)
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-orange-50 text-orange-500 rounded-full shrink-0">
                        <i data-feather="clock" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <h4 class="font-bold text-gray-800 text-sm">{{ $stats['telat'] }} Karyawan Terlambat</h4>
                            <span class="text-xs text-gray-400">Today</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Beberapa staf tercatat hadir melewati batas waktu.</p>
                    </div>
                </div>
                @endif
            </div>
            
            <a href="{{ route('hr.absensi') }}" class="block mt-6 text-center w-full py-2 border border-gray-200 text-sm font-semibold text-gray-700 rounded-lg hover:bg-gray-50 transition">
                View All Activity
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('attendanceChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Izin/Cuti', 'Terlambat', 'Alpha'],
                datasets: [{
                    data: [
                        {{ $stats['hadir'] }}, 
                        {{ $stats['izin'] }}, 
                        {{ $stats['telat'] }}, 
                        {{ $stats['alpa'] }}
                    ],
                    backgroundColor: [
                        '#4ade80', // green-400
                        '#60a5fa', // blue-400
                        '#fb923c', // orange-400
                        '#f87171'  // red-400
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false // We use our custom legend
                    },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { size: 14, family: "'Inter', sans-serif" },
                        bodyFont: { size: 13, family: "'Inter', sans-serif" }
                    }
                }
            }
        });
    }
});
</script>
@endsection
