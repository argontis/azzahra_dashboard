@extends('layouts.app')
@section('content')
<!-- Header -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="grid" class="w-6 h-6 inline-block mr-2"></i>HR Overview</h1>
        <p>Control Center & Ringkasan Human Resources</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area" style="margin-top: 75px; padding-top: 15px;">
    <!-- Header Banner -->
    <div class="intro-y p-6 rounded-2xl shadow-xl mb-6 border-2 border-blue-600" style="background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%) !important; color: #ffffff !important;">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3.5 bg-blue-800 rounded-2xl border border-blue-400/30 shadow-inner">
                    <i data-feather="grid" class="w-10 h-10 text-yellow-300"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-wide" style="margin: 0; color: #ffffff !important;">HR Control Center & Overview</h1>
                    <div class="flex items-center gap-2 text-xs font-semibold mt-1" style="color: #cbd5e1 !important;">
                        <i data-feather="calendar" class="w-4 h-4 text-yellow-400"></i>
                        <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                        <span class="mx-1">•</span>
                        <span class="px-2.5 py-0.5 rounded-full font-bold text-white" style="background-color: #3b82f6 !important;">Role: Human Resources</span>
                    </div>
                </div>
            </div>

            <!-- Quick Access Nav -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('hr.karyawan') }}" class="button text-xs font-bold py-2 px-3.5 rounded-xl flex items-center gap-2 shadow" style="background-color: #334155 !important; color: #ffffff !important; border: 1px solid #64748b !important;">
                    <i data-feather="users" class="w-4 h-4 text-yellow-400"></i> Data Karyawan
                </a>
                <a href="{{ route('hr.absensi') }}" class="button text-xs font-extrabold py-2 px-3.5 rounded-xl flex items-center gap-2 shadow" style="background-color: #eab308 !important; color: #0f172a !important;">
                    <i data-feather="clock" class="w-4 h-4 text-blue-950"></i> Absensi RFID
                </a>
                <a href="{{ route('hr.kpi') }}" class="button text-xs font-bold py-2 px-3.5 rounded-xl flex items-center gap-2 shadow" style="background-color: #334155 !important; color: #ffffff !important; border: 1px solid #64748b !important;">
                    <i data-feather="bar-chart-2" class="w-4 h-4 text-green-400"></i> KPI Karyawan
                </a>
                <a href="{{ route('hr.rekap') }}" class="button text-xs font-bold py-2 px-3.5 rounded-xl flex items-center gap-2 shadow" style="background-color: #334155 !important; color: #ffffff !important; border: 1px solid #64748b !important;">
                    <i data-feather="file-text" class="w-4 h-4 text-purple-300"></i> Rekap HR
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Cards (4 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Total Karyawan -->
        <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-8 -mt-8 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Karyawan Aktif</span>
                <div class="p-2 bg-blue-100 text-blue-700 rounded-lg">
                    <i data-feather="users" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-gray-800">{{ $stats['total_karyawan'] }}</div>
            <div class="text-xs text-gray-500 font-semibold mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> Staf Tetap & Kontrak
            </div>
        </div>

        <!-- Total Magang / PKL -->
        <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -mr-8 -mt-8 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Magang / PKL</span>
                <div class="p-2 bg-purple-100 text-purple-700 rounded-lg">
                    <i data-feather="user-check" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-gray-800">{{ $stats['total_magang'] }}</div>
            <div class="text-xs text-gray-500 font-semibold mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-purple-500 inline-block"></span> Peserta Magang Aktif
            </div>
        </div>

        <!-- Kehadiran Hari Ini -->
        <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-green-50 rounded-full -mr-8 -mt-8 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Hadir Hari Ini</span>
                <div class="p-2 bg-green-100 text-green-700 rounded-lg">
                    <i data-feather="clock" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-green-600">{{ $stats['hadir'] }}</div>
            <div class="text-xs text-gray-500 font-semibold mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span> Terapi & Absen RFID
            </div>
        </div>

        <!-- KPI Bulan Ini -->
        <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-50 rounded-full -mr-8 -mt-8 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">KPI Bulan Ini</span>
                <div class="p-2 bg-yellow-100 text-yellow-700 rounded-lg">
                    <i data-feather="bar-chart-2" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-gray-800">{{ $stats['kpi_count'] }}</div>
            <div class="text-xs text-gray-500 font-semibold mt-1 flex items-center gap-1">
                <span class="w-2 h-2 rounded-full bg-yellow-500 inline-block"></span> Laporan KPI Terdata
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="box p-4 bg-white rounded-xl border border-gray-100 shadow-sm mb-6">
        <form method="GET" action="{{ route('hr.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider mr-2">Periode Filter:</span>
                <button type="submit" name="periode" value="hari_ini" class="px-4 py-1.5 rounded-lg text-xs font-bold transition {{ $selected_periode == 'hari_ini' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Hari Ini
                </button>
                <button type="submit" name="periode" value="minggu_ini" class="px-4 py-1.5 rounded-lg text-xs font-bold transition {{ $selected_periode == 'minggu_ini' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Minggu Ini
                </button>
                <button type="submit" name="periode" value="bulan_ini" class="px-4 py-1.5 rounded-lg text-xs font-bold transition {{ $selected_periode == 'bulan_ini' ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Bulan Ini
                </button>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" class="input border rounded-lg px-3 py-1.5 text-xs text-gray-700">
                <button type="submit" name="periode" value="tanggal" class="button bg-gray-800 text-white text-xs px-3 py-1.5 font-bold rounded-lg shadow">
                    Filter Tanggal
                </button>
            </div>
        </form>
    </div>

    <!-- Main Overview Grid -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Left Side: Chart & Table (8 Columns) -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <!-- Komposisi Absensi Chart Card -->
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-800 text-base mb-4 flex items-center gap-2">
                    <i data-feather="pie-chart" class="w-5 h-5 text-blue-600"></i> Ringkasan Kehadiran Karyawan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div class="relative w-44 h-44 mx-auto">
                        <canvas id="attendanceChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-3xl font-extrabold text-gray-800">{{ $stats['hadir'] }}</span>
                            <span class="text-xs text-gray-400 font-bold">Hadir</span>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs font-semibold">
                        <div class="flex items-center justify-between p-2.5 bg-green-50 rounded-lg border border-green-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                                <span class="text-gray-700">Hadir Tepat Waktu</span>
                            </div>
                            <span class="font-bold text-green-700 text-sm">{{ $stats['hadir'] }} Personil</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 bg-orange-50 rounded-lg border border-orange-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-orange-500"></span>
                                <span class="text-gray-700">Terlambat</span>
                            </div>
                            <span class="font-bold text-orange-700 text-sm">{{ $stats['telat'] }} Personil</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                                <span class="text-gray-700">Izin / Cuti / Sakit</span>
                            </div>
                            <span class="font-bold text-blue-700 text-sm">{{ $stats['izin'] }} Personil</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                <span class="text-gray-700">Tidak Hadir (Alpha)</span>
                            </div>
                            <span class="font-bold text-red-700 text-sm">{{ $stats['alpa'] }} Personil</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Aktivitas Absensi Terbaru -->
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                        <i data-feather="clock" class="w-5 h-5 text-blue-600"></i> Absensi Hari Ini / Terakhir
                    </h3>
                    <a href="{{ route('hr.absensi') }}" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                        Lihat Semua Absensi <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="table w-full text-xs">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border-b-2 p-2.5">KARYAWAN</th>
                                <th class="border-b-2 text-center p-2.5">MASUK</th>
                                <th class="border-b-2 text-center p-2.5">ISTIRAHAT</th>
                                <th class="border-b-2 text-center p-2.5">KEMBALI</th>
                                <th class="border-b-2 text-center p-2.5">PULANG</th>
                                <th class="border-b-2 text-center p-2.5">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_absensi as $ab)
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="p-2.5 font-bold text-gray-800">
                                    {{ $ab->nama_karyawan }}
                                    <div class="text-gray-400 font-normal text-xs">{{ $ab->posisi }}</div>
                                </td>
                                <td class="p-2.5 text-center font-semibold text-green-700">{{ $ab->jam_masuk ?? '-' }}</td>
                                <td class="p-2.5 text-center font-semibold text-yellow-700">{{ $ab->jam_istirahat ?? '-' }}</td>
                                <td class="p-2.5 text-center font-semibold text-blue-700">{{ $ab->jam_kembali_istirahat ?? '-' }}</td>
                                <td class="p-2.5 text-center font-semibold text-red-700">{{ $ab->jam_pulang ?? '-' }}</td>
                                <td class="p-2.5 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-extrabold 
                                        {{ $ab->status == 'HADIR' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                                        {{ $ab->status == 'TELAT' ? 'bg-orange-100 text-orange-800 border border-orange-200' : '' }}
                                        {{ in_array($ab->status, ['IZIN','SAKIT','CUTI']) ? 'bg-blue-100 text-blue-800 border border-blue-200' : '' }}
                                        {{ $ab->status == 'ALPA' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}">
                                        {{ $ab->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-gray-500 italic p-6">Belum ada aktivitas absensi tercatat pada periode ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side: Quick Actions & Recent KPI (4 Columns) -->
        <div class="col-span-12 lg:col-span-4 space-y-6">
            <!-- Quick Actions Card -->
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-800 text-base mb-4 flex items-center gap-2">
                    <i data-feather="zap" class="w-5 h-5 text-yellow-500"></i> Aksi Cepat HR
                </h3>

                <div class="grid grid-cols-1 gap-2.5">
                    <a href="{{ route('hr.absensi') }}" class="p-3 bg-blue-50 hover:bg-blue-100 text-blue-900 border border-blue-200 rounded-xl flex items-center justify-between transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-600 text-white rounded-lg group-hover:scale-110 transition-transform">
                                <i data-feather="radio" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <div class="font-bold text-xs">Tap Scan RFID Absensi</div>
                                <div class="text-xs text-blue-700">Pencatatan 4 Jam Kerja</div>
                            </div>
                        </div>
                        <i data-feather="chevron-right" class="w-4 h-4 text-blue-500"></i>
                    </a>

                    <a href="{{ route('hr.kpi') }}" class="p-3 bg-green-50 hover:bg-green-100 text-green-900 border border-green-200 rounded-xl flex items-center justify-between transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-green-600 text-white rounded-lg group-hover:scale-110 transition-transform">
                                <i data-feather="upload" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <div class="font-bold text-xs">Import Excel KPI</div>
                                <div class="text-xs text-green-700">Upload & Rekap KPI</div>
                            </div>
                        </div>
                        <i data-feather="chevron-right" class="w-4 h-4 text-green-500"></i>
                    </a>

                    <a href="{{ route('hr.karyawan') }}" class="p-3 bg-purple-50 hover:bg-purple-100 text-purple-900 border border-purple-200 rounded-xl flex items-center justify-between transition group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-purple-600 text-white rounded-lg group-hover:scale-110 transition-transform">
                                <i data-feather="user-plus" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <div class="font-bold text-xs">Kelola Karyawan & Magang</div>
                                <div class="text-xs text-purple-700">Tambah / Edit Staf</div>
                            </div>
                        </div>
                        <i data-feather="chevron-right" class="w-4 h-4 text-purple-500"></i>
                    </a>
                </div>
            </div>

            <!-- Recent KPI Entries Card -->
            <div class="box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800 text-base flex items-center gap-2">
                        <i data-feather="award" class="w-5 h-5 text-purple-600"></i> KPI Terbaru
                    </h3>
                    <a href="{{ route('hr.kpi') }}" class="text-xs font-bold text-purple-600 hover:underline">
                        Lihat KPI
                    </a>
                </div>

                <div class="space-y-3 text-xs">
                    @forelse($recent_kpi as $kp)
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center justify-between font-bold text-gray-800 mb-1">
                            <span>{{ $kp->nama_karyawan }}</span>
                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">{{ $kp->periode }}</span>
                        </div>
                        <p class="text-gray-600 font-normal line-clamp-2">Target: {{ Str::limit($kp->target_mingguan, 50) }}</p>
                    </div>
                    @empty
                    <div class="text-center text-gray-500 italic p-4">Belum ada data KPI diinput.</div>
                    @endforelse
                </div>
            </div>
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
                labels: ['Hadir Tepat Waktu', 'Terlambat', 'Izin/Cuti', 'Alpha'],
                datasets: [{
                    data: [
                        {{ $stats['hadir'] }}, 
                        {{ $stats['telat'] }}, 
                        {{ $stats['izin'] }}, 
                        {{ $stats['alpa'] }}
                    ],
                    backgroundColor: [
                        '#22c55e', // green-500
                        '#f97316', // orange-500
                        '#3b82f6', // blue-500
                        '#ef4444'  // red-500
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
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1f2937',
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection
