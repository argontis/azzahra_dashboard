@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="file-text" class="w-6 h-6 inline-block mr-2"></i>Rekap HR</h1>
        <p>Rekapitulasi Laporan Absensi Harian & KPI Semua Karyawan</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search..." id="rekapSearchInput" onkeyup="filterRekapTable()">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;"><i data-feather="bell"></i><div class="badge-dot"></div></div>
        <div class="header-btn">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">
    <!-- Stat Cards -->
    <div class="grid grid-cols-12 gap-6 mt-4">
        <div class="col-span-12 sm:col-span-4 intro-y box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                    <i data-feather="users" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Total Karyawan</div>
                    <div class="text-2xl font-bold text-gray-800">{{ count($karyawan_list) }} Karyawan</div>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 intro-y box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                    <i data-feather="clock" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Absensi ({{ \Carbon\Carbon::parse($selected_date)->format('d M Y') }})</div>
                    <div class="text-2xl font-bold text-gray-800">
                        {{ count($absensi_harian) }} / {{ count($karyawan_list) }} Terisi
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 intro-y box p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                    <i data-feather="bar-chart-2" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-semibold uppercase tracking-wider">KPI (Periode {{ $selected_periode }})</div>
                    <div class="text-2xl font-bold text-gray-800">
                        {{ count($kpi_periode) }} / {{ count($karyawan_list) }} Terisi
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="intro-y box p-5 mt-6 bg-white rounded-xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('hr.rekap') }}" class="flex flex-col md:flex-row items-end gap-4">
            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Filter Absensi</label>
                <input type="date" name="tanggal" value="{{ $selected_date }}" class="input w-full border rounded-lg px-3 py-2 text-sm text-gray-700">
            </div>

            <div class="w-full md:w-1/3">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Periode Filter KPI (Bulanan)</label>
                <input type="month" name="periode" value="{{ $selected_periode }}" class="input w-full border rounded-lg px-3 py-2 text-sm text-gray-700">
            </div>

            <div class="w-full md:w-1/3 flex gap-2">
                <button type="submit" class="button bg-theme-1 text-white flex-1 py-2 rounded-lg font-semibold text-sm flex items-center justify-center gap-2">
                    <i data-feather="filter" class="w-4 h-4"></i> Terapkan Filter
                </button>
                <a href="{{ route('hr.rekap') }}" class="button border text-gray-700 bg-white py-2 px-4 rounded-lg font-semibold text-sm flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tab Navigation -->
    <div class="intro-y flex flex-col sm:flex-row items-center justify-between border-b border-gray-200 mt-6 pb-2">
        <div class="flex space-x-4">
            <button type="button" onclick="switchRekapTab('absensi')" id="tab-btn-absensi" class="rekap-tab-btn px-4 py-2 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 flex items-center gap-2">
                <i data-feather="clock" class="w-4 h-4"></i> Rekap Absensi Harian
            </button>
            <button type="button" onclick="switchRekapTab('kpi')" id="tab-btn-kpi" class="rekap-tab-btn px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 flex items-center gap-2">
                <i data-feather="bar-chart-2" class="w-4 h-4"></i> Rekap KPI Karyawan
            </button>
            <button type="button" onclick="switchRekapTab('gabungan')" id="tab-btn-gabungan" class="rekap-tab-btn px-4 py-2 text-sm font-semibold border-b-2 border-transparent text-gray-500 hover:text-gray-700 flex items-center gap-2">
                <i data-feather="layers" class="w-4 h-4"></i> Data Master Semua Karyawan
            </button>
        </div>
        <div class="text-xs text-gray-500 mt-2 sm:mt-0 font-medium">
            Menampilkan data untuk <span class="font-bold text-gray-800">{{ count($karyawan_list) }}</span> Karyawan
        </div>
    </div>

    <!-- Tab Content 1: Rekap Absensi -->
    <div id="tab-content-absensi" class="rekap-tab-content mt-4">
        <div class="intro-y box overflow-hidden bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800 text-base">Laporan Absensi Karyawan (Tanggal: {{ \Carbon\Carbon::parse($selected_date)->format('d M Y') }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm" id="table-absensi">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border-b-2 text-center p-3" style="width: 5%;">NO</th>
                                <th class="border-b-2 p-3">NAMA KARYAWAN</th>
                                <th class="border-b-2 p-3">JABATAN</th>
                                <th class="border-b-2 text-center p-3">STATUS</th>
                                <th class="border-b-2 text-center p-3">MASUK</th>
                                <th class="border-b-2 text-center p-3">ISTIRAHAT</th>
                                <th class="border-b-2 text-center p-3">KEMBALI ISTIRAHAT</th>
                                <th class="border-b-2 text-center p-3">PULANG</th>
                                <th class="border-b-2 p-3">KETERANGAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($karyawan_list as $index => $kry)
                            @php
                                $abs = $absensi_harian[$kry->kry_kode] ?? null;
                            @endphp
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="text-center p-3">{{ $index + 1 }}</td>
                                <td class="font-semibold p-3">
                                    <div class="text-gray-900">{{ $kry->kry_nama }}</div>
                                    @if($kry->kry_telp)
                                        <div class="text-xs text-gray-400 font-normal">📞 {{ $kry->kry_telp }}</div>
                                    @endif
                                </td>
                                <td class="p-3 text-gray-600">{{ $kry->kry_level }}</td>
                                <td class="text-center p-3">
                                    @if($abs)
                                        @if($abs->status == 'Hadir' || $abs->status == 'HADIR')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Hadir</span>
                                        @elseif($abs->status == 'Izin' || $abs->status == 'IZIN')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Izin</span>
                                        @elseif($abs->status == 'Sakit' || $abs->status == 'SAKIT')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Sakit</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Alpha</span>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Belum Absen</span>
                                    @endif
                                </td>
                                <td class="text-center p-3 font-mono text-xs font-semibold text-green-700">
                                    {{ $abs?->jam_masuk ?? '-' }}
                                </td>
                                <td class="text-center p-3 font-mono text-xs font-semibold text-yellow-700">
                                    {{ $abs?->jam_istirahat ?? '-' }}
                                </td>
                                <td class="text-center p-3 font-mono text-xs font-semibold text-blue-700">
                                    {{ $abs?->jam_kembali_istirahat ?? '-' }}
                                </td>
                                <td class="text-center p-3 font-mono text-xs font-semibold text-red-700">
                                    {{ $abs?->jam_pulang ?? '-' }}
                                </td>
                                <td class="p-3 text-gray-500 text-xs italic">
                                    {{ $abs?->keterangan ?? '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-8 text-gray-500 italic">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content 2: Rekap KPI -->
    <div id="tab-content-kpi" class="rekap-tab-content mt-4" style="display: none;">
        <div class="intro-y box overflow-hidden bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800 text-base">Laporan KPI Karyawan (Periode: {{ $selected_periode }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm" id="table-kpi">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border-b-2 text-center p-3" style="width: 5%;">NO</th>
                                <th class="border-b-2 p-3">NAMA KARYAWAN</th>
                                <th class="border-b-2 p-3">JABATAN</th>
                                <th class="border-b-2 p-3">TARGET MINGGUAN</th>
                                <th class="border-b-2 p-3">TUGAS DILAKUKAN</th>
                                <th class="border-b-2 p-3">HASIL / PENCAPAIAN</th>
                                <th class="border-b-2 p-3">KENDALA & SOLUSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($karyawan_list as $index => $kry)
                            @php
                                $kpi = $kpi_periode[$kry->kry_kode] ?? null;
                            @endphp
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="text-center p-3">{{ $index + 1 }}</td>
                                <td class="font-semibold p-3">
                                    <div class="text-gray-900">{{ $kry->kry_nama }}</div>
                                    @if($kry->kry_telp)
                                        <div class="text-xs text-gray-400 font-normal">📞 {{ $kry->kry_telp }}</div>
                                    @endif
                                </td>
                                <td class="p-3 text-gray-600">{{ $kry->kry_level }}</td>
                                <td class="p-3">
                                    @if($kpi?->target_mingguan)
                                        <span class="text-gray-800 font-medium">{{ $kpi->target_mingguan }}</span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">Belum diisi</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if($kpi?->tugas_dilakukan)
                                        <span class="text-gray-700 text-xs">{{ $kpi->tugas_dilakukan }}</span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">-</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if($kpi?->hasil)
                                        <span class="text-green-700 font-semibold text-xs">{{ $kpi->hasil }}</span>
                                    @else
                                        <span class="text-gray-400 italic text-xs">-</span>
                                    @endif
                                </td>
                                <td class="p-3 text-xs">
                                    @if($kpi?->kendala || $kpi?->solusi)
                                        @if($kpi->kendala) <div class="text-red-600 font-medium">⚠️ {{ $kpi->kendala }}</div> @endif
                                        @if($kpi->solusi) <div class="text-blue-600 mt-1">💡 {{ $kpi->solusi }}</div> @endif
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-8 text-gray-500 italic">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Content 3: Rekap Master Gabungan Semua Karyawan -->
    <div id="tab-content-gabungan" class="rekap-tab-content mt-4" style="display: none;">
        <div class="intro-y box overflow-hidden bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-800 text-base">Rekap Master (Absensi Tanggal {{ \Carbon\Carbon::parse($selected_date)->format('d M Y') }} & KPI Periode {{ $selected_periode }})</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="table w-full text-sm" id="table-gabungan">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border-b-2 text-center p-3" style="width: 5%;">NO</th>
                                <th class="border-b-2 p-3">NAMA KARYAWAN</th>
                                <th class="border-b-2 p-3">JABATAN</th>
                                <th class="border-b-2 text-center p-3">ABSENSI</th>
                                <th class="border-b-2 text-center p-3">JAM MASUK / PULANG</th>
                                <th class="border-b-2 p-3">KPI TARGET</th>
                                <th class="border-b-2 p-3">KPI HASIL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($karyawan_list as $index => $kry)
                            @php
                                $abs = $absensi_harian[$kry->kry_kode] ?? null;
                                $kpi = $kpi_periode[$kry->kry_kode] ?? null;
                            @endphp
                            <tr class="hover:bg-gray-50 border-b">
                                <td class="text-center p-3">{{ $index + 1 }}</td>
                                <td class="font-semibold p-3">
                                    <div class="text-gray-900">{{ $kry->kry_nama }}</div>
                                    @if($kry->kry_telp)
                                        <div class="text-xs text-gray-400 font-normal">📞 {{ $kry->kry_telp }}</div>
                                    @endif
                                </td>
                                <td class="p-3 text-gray-600">{{ $kry->kry_level }}</td>
                                <td class="text-center p-3">
                                    @if($abs)
                                        @if($abs->status == 'Hadir')
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800">Hadir</span>
                                        @elseif($abs->status == 'Izin')
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-blue-100 text-blue-800">Izin</span>
                                        @elseif($abs->status == 'Sakit')
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">Sakit</span>
                                        @else
                                            <span class="px-2 py-1 rounded text-xs font-semibold bg-red-100 text-red-800">Alpha</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-500">Belum Absen</span>
                                    @endif
                                </td>
                                <td class="text-center p-3 font-mono text-xs">
                                    {{ $abs?->jam_masuk ?? '-' }} | {{ $abs?->jam_istirahat ?? '-' }} | {{ $abs?->jam_kembali_istirahat ?? '-' }} | {{ $abs?->jam_pulang ?? '-' }}
                                </td>
                                <td class="p-3 text-xs">
                                    {{ $kpi?->target_mingguan ? \Illuminate\Support\Str::limit($kpi->target_mingguan, 35) : '-' }}
                                </td>
                                <td class="p-3 text-xs">
                                    {{ $kpi?->hasil ? \Illuminate\Support\Str::limit($kpi->hasil, 35) : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center p-8 text-gray-500 italic">Belum ada data karyawan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-theme-1 { background-color: #1e40af; }
    .bg-theme-1:hover { background-color: #1e3a8a; }
</style>

<script>
    function switchRekapTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.rekap-tab-content').forEach(el => el.style.display = 'none');
        
        // Reset tab button styles
        document.querySelectorAll('.rekap-tab-btn').forEach(btn => {
            btn.classList.remove('border-blue-600', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        // Show selected tab content & highlight button
        const selectedContent = document.getElementById('tab-content-' + tabName);
        const selectedBtn = document.getElementById('tab-btn-' + tabName);

        if (selectedContent) selectedContent.style.display = 'block';
        if (selectedBtn) {
            selectedBtn.classList.remove('border-transparent', 'text-gray-500');
            selectedBtn.classList.add('border-blue-600', 'text-blue-600');
        }
    }

    function filterRekapTable() {
        const input = document.getElementById('rekapSearchInput');
        const filter = input.value.toLowerCase();

        ['table-absensi', 'table-kpi', 'table-gabungan'].forEach(tableId => {
            const table = document.getElementById(tableId);
            if (!table) return;
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let showRow = false;
                const td = tr[i].getElementsByTagName('td');
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            showRow = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = showRow ? '' : 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection