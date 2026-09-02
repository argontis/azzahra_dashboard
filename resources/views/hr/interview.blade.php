@extends('layouts.app')
@section('content')
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Interview</h1>
                <p>Jadwal dan Hasil Interview Kandidat</p>
            </div>
            <div class="header-actions">
                <form action="{{ url('HR/interview') }}" method="GET" class="search-input-wrapper m-0" onsubmit="event.preventDefault(); filterInterviewTable();">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" name="search" id="interviewSearchInput" class="search-input" placeholder="Search..." onkeyup="filterInterviewTable()" value="{{ request('search') }}">
                </form>
                <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
                    <i data-feather="bell"></i>
                    <div class="badge-dot" style="display: none;"></div>
                </div>
                <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
                    <i data-feather="mail"></i>
                    <span class="topbar-mail-badge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 9999px; font-size: 10px; font-weight: bold; min-width: 16px; height: 16px; line-height: 16px; text-align: center; padding: 0 4px;"></span>
                </div>
            </div>
        </header>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Notification Alerts -->
        @if (session('sukses'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200" role="alert">
                <span class="font-medium">Sukses!</span> {{ session('sukses') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
                <span class="font-medium">Error!</span> {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
                <span class="font-medium">Gagal!</span>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Upcoming Interview Reminder Banner -->
        @if(isset($upcoming_interviews) && $upcoming_interviews->count() > 0)
            <div class="mb-6 p-5 rounded-2xl shadow-xl relative overflow-hidden" style="background: linear-gradient(135deg, #b45309 0%, #ea580c 50%, #c2410c 100%) !important; border: 2px solid #f59e0b !important; color: #ffffff !important;">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 rounded-xl shadow-inner animate-pulse" style="background: rgba(255, 255, 255, 0.2) !important; color: #ffffff !important;">
                            <i data-feather="bell" class="w-6 h-6 text-yellow-200"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-extrabold text-base md:text-lg" style="color: #ffffff !important; margin: 0; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                                    ⏰ Pengingat: Ada {{ $upcoming_interviews->count() }} Jadwal Interview Mendekati Jamnya!
                                </h3>
                                <span class="px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider text-xs" style="background-color: #fef08a !important; color: #854d0e !important; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                    Prioritas
                                </span>
                            </div>
                            <p class="text-xs md:text-sm mt-1" style="color: #fef3c7 !important; margin-bottom: 0;">
                                Harap persiapkan berkas, tautan video call / ruang pertemuan, dan materi seleksi kandidat berikut:
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    @foreach($upcoming_interviews as $upcoming)
                    @php
                        $waktuInterview = \Carbon\Carbon::parse($upcoming->tanggal_waktu);
                        $isPast = $waktuInterview->isPast();
                        $diff = $waktuInterview->diffForHumans();
                    @endphp
                    <div class="p-4 rounded-xl shadow-md flex flex-col justify-between" style="background: #ffffff !important; color: #1e293b !important; border: 1px solid #fed7aa !important;">
                        <div>
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="font-bold text-sm truncate" style="color: #0f172a !important; margin: 0;">{{ $upcoming->nama_kandidat }}</h4>
                                <span class="text-xs px-2.5 py-0.5 rounded-full font-bold whitespace-no-wrap" style="{{ $isPast ? 'background-color: #fee2e2 !important; color: #b91c1c !important;' : 'background-color: #fef3c7 !important; color: #b45309 !important;' }}">
                                    {{ $isPast ? 'Sedang / Lewat' : $diff }}
                                </span>
                            </div>
                            <div class="text-xs font-semibold mt-1.5 flex items-center gap-1.5" style="color: #2563eb !important;">
                                <i data-feather="briefcase" class="w-3.5 h-3.5"></i>
                                <span>{{ $upcoming->posisi }}</span>
                            </div>
                        </div>
                        <div class="mt-3 pt-2 text-xs flex items-center justify-between" style="border-top: 1px solid #f1f5f9 !important; color: #64748b !important;">
                            <span class="flex items-center gap-1 font-semibold" style="color: #d97706 !important;">
                                <i data-feather="clock" class="w-3.5 h-3.5 text-amber-600"></i>
                                {{ $waktuInterview->format('d M Y, H:i') }} WIB
                            </span>
                            @if($upcoming->catatan)
                                <span class="text-xs italic" style="color: #94a3b8 !important;" title="{{ $upcoming->catatan }}">{{ \Illuminate\Support\Str::limit($upcoming->catatan, 15) }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Top Bar -->
        <div class="intro-y flex flex-col sm:flex-row items-center mt-4">
            <h2 class="text-lg font-medium mr-auto">
                Daftar Jadwal Interview
            </h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0 items-center">
                <a role="button" class="btn-tambah-custom mr-2" data-toggle="modal" data-target="#interviewModal" onclick="bukaModal()">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i> Tambah Jadwal
                </a>
                
                <a href="{{ url('/HR/karyawan') }}" class="button border text-gray-700 bg-white inline-flex items-center" style="padding: 10px 20px; height: 100%;">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Box Tabel Interview -->
        <div class="intro-y box mt-5 overflow-hidden">
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="table w-full" id="interviewTable">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border-b-2 text-center whitespace-no-wrap" style="width: 5%;">No</th>
                                <th class="border-b-2 whitespace-no-wrap">Nama Kandidat</th>
                                <th class="border-b-2 whitespace-no-wrap">Posisi Dilamar</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">Tanggal & Waktu</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">Status</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">Aksi / Catatan</th>
                            </tr>
                        </thead>
                        <tbody id="interviewTableBody">
                            @if(isset($interview_list) && count($interview_list) > 0)
                                @foreach($interview_list as $index => $item)
                                @php
                                    $itemTime = \Carbon\Carbon::parse($item->tanggal_waktu);
                                    $isApproaching = $item->status == 'Menunggu' && $itemTime->isBetween(\Carbon\Carbon::now()->subHours(2), \Carbon\Carbon::now()->addHours(24));
                                @endphp
                                <tr class="interview-row {{ $isApproaching ? 'bg-amber-50/60' : '' }}">
                                    <td class="text-center border-b py-3 row-number">{{ $index + 1 }}</td>
                                    <td class="font-medium border-b py-3">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $item->nama_kandidat }}</span>
                                            @if($isApproaching)
                                                <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-amber-500 text-white animate-pulse" title="Mendekati jam interview">
                                                    ⏰ Hari ini / Segera
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-gray-600 border-b py-3">{{ $item->posisi }}</td>
                                    <td class="text-center border-b py-3">
                                        <span class="{{ $isApproaching ? 'font-bold text-amber-700' : '' }}">
                                            {{ $itemTime->format('d M Y, H:i') }}
                                        </span>
                                    </td>
                                    <td class="text-center border-b py-3">
                                        @if($item->status == 'Lulus')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Lulus</span>
                                        @elseif($item->status == 'Gagal')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Gagal</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="text-center border-b py-3">
                                        @if($item->catatan)
                                            <span class="text-gray-500 text-xs italic" title="{{ $item->catatan }}">{{ \Illuminate\Support\Str::limit($item->catatan, 30) }}</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr id="emptyInitialRow">
                                <td colspan="6" class="text-center border-b py-16">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i data-feather="inbox" class="w-16 h-16 mb-4 text-gray-300"></i>
                                        <p class="text-lg">Belum ada jadwal interview</p>
                                        <p class="text-sm mt-1">Klik tombol "Tambah Jadwal" di atas untuk menjadwalkan interview baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <tr id="noSearchResultRow" style="display: none;">
                                <td colspan="6" class="text-center border-b py-12 text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-feather="search" class="w-10 h-10 mb-2 text-gray-300"></i>
                                        <p class="text-base font-medium">Tidak ada hasil yang sesuai dengan pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL TAMBAH JADWAL INTERVIEW              -->
    <!-- ========================================== -->
    <div class="modal" id="interviewModal">
        <div class="modal__content modal__content--xl p-10">
            <div class="flex items-center px-5 py-5 sm:py-3 border-b border-gray-200">
                <h2 class="font-medium text-base mr-auto">Tambah Jadwal Interview Baru</h2>
                <button type="button" data-dismiss="modal" class="button border text-gray-700" onclick="tutupModal()">&times;</button>
            </div>
            <form action="{{ route('hr.interview.save') }}" method="POST">
                @csrf
                <div class="p-5 grid grid-cols-12 gap-4 row-gap-3">
                    <div class="col-span-12">
                        <label class="font-bold text-gray-700">Nama Kandidat *</label>
                        <input type="text" name="nama_kandidat" class="input w-full border mt-2 flex-1" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="col-span-12">
                        <label class="font-bold text-gray-700">Posisi yang Dilamar *</label>
                        <input type="text" name="posisi" class="input w-full border mt-2 flex-1" placeholder="Contoh: Frontend Developer" required>
                    </div>

                    <div class="col-span-12">
                        <label class="font-bold text-gray-700">Tanggal & Waktu Interview *</label>
                        <input type="datetime-local" name="tanggal_waktu" class="input w-full border mt-2 flex-1" required>
                    </div>

                    <div class="col-span-12">
                        <label class="font-bold text-gray-700">Catatan / Keterangan</label>
                        <textarea name="catatan" class="input w-full border mt-2 flex-1" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>

                <div class="px-5 py-3 text-right border-t border-gray-200">
                    <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1" onclick="tutupModal()">Batal</button>
                    <button type="submit" class="button w-32 bg-theme-1 text-white">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

<style>
    .bg-theme-1 { background-color: #1e40af; }
    .bg-theme-1:hover { background-color: #1e3a8a; }

    .btn-tambah-custom {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        padding: 10px 20px !important;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        border: none !important;
        border-radius: 8px !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
        text-decoration: none !important;
    }
    .btn-tambah-custom:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
        color: white !important;
    }
</style>

<script>
    function filterInterviewTable() {
        const input = document.getElementById('interviewSearchInput');
        if (!input) return;
        const filter = input.value.toLowerCase().trim();
        const table = document.getElementById('interviewTable');
        if (!table) return;
        
        const rows = table.querySelectorAll('tbody tr.interview-row');
        let visibleCount = 0;

        rows.forEach(function(row) {
            const text = row.textContent || row.innerText;
            if (filter === '' || text.toLowerCase().indexOf(filter) > -1) {
                row.style.display = '';
                visibleCount++;
                const numCell = row.querySelector('.row-number');
                if (numCell) numCell.textContent = visibleCount;
            } else {
                row.style.display = 'none';
            }
        });

        const noResultRow = document.getElementById('noSearchResultRow');
        const emptyInitialRow = document.getElementById('emptyInitialRow');

        if (rows.length > 0) {
            if (visibleCount === 0) {
                if (noResultRow) noResultRow.style.display = '';
            } else {
                if (noResultRow) noResultRow.style.display = 'none';
            }
        }
    }

    window.bukaModal = function() {
        if (typeof $ !== 'undefined' && typeof $('#interviewModal').modal === 'function') {
            $('#interviewModal').modal('show');
        } else {
            const modal = document.getElementById('interviewModal');
            if (modal) {
                modal.classList.add('show');
                modal.style.display = 'block';
            }
        }
    };

    window.tutupModal = function() {
        if (typeof $ !== 'undefined' && typeof $('#interviewModal').modal === 'function') {
            $('#interviewModal').modal('hide');
        } else {
            const modal = document.getElementById('interviewModal');
            if (modal) {
                modal.classList.remove('show');
                modal.style.display = 'none';
            }
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        const searchInput = document.getElementById('interviewSearchInput');
        if (searchInput && searchInput.value) {
            filterInterviewTable();
        }
    });
</script>
@endsection