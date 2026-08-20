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
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn">
                    <i data-feather="bell"></i>
                    <div class="badge-dot"></div>
                </div>
                <div class="header-btn">
                    <i data-feather="mail"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content-area">
            <!-- Top Bar -->
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">
                    Daftar Jadwal Interview
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0 items-center">
                    <!-- Tombol Tambah Jadwal dengan ID khusus agar pasti terbaca JS -->
                    <button type="button" id="btnBukaModalInterview" class="btn-tambah-custom mr-2" style="display: inline-flex; align-items: center;">
                        <i data-feather="plus" class="w-4 h-4 mr-2"></i> Tambah Jadwal
                    </button>
                    
                    <a href="{{ url('/HR/karyawan') }}" class="button border text-gray-700 bg-white" style="display: inline-flex; align-items: center; padding: 10px 20px; height: 100%;">
                        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Box Tabel Interview -->
            <div class="intro-y box mt-5 overflow-hidden">
                <div class="p-5">
                    <div class="overflow-x-auto">
                        <table class="table w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border-b-2 text-center whitespace-no-wrap" style="width: 5%;">No</th>
                                    <th class="border-b-2 whitespace-no-wrap">Nama Kandidat</th>
                                    <th class="border-b-2 whitespace-no-wrap">Posisi Dilamar</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">Tanggal & Waktu</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">Status</th>
                                    <th class="border-b-2 text-center whitespace-no-wrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($interview_list) && count($interview_list) > 0)
                                    @foreach($interview_list as $index => $item)
                                    <tr>
                                        <!-- Data akan di-render di sini -->
                                    </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="text-center border-b py-16">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i data-feather="inbox" class="w-16 h-16 mb-4 text-gray-300"></i>
                                            <p class="text-lg">Belum ada jadwal interview</p>
                                            <p class="text-sm mt-1">Klik tombol "Tambah Jadwal" di atas untuk menjadwalkan interview baru.</p>
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

<!-- ========================================== -->
<!-- MODAL TAMBAH JADWAL INTERVIEW              -->
<!-- ========================================== -->
<div class="modal" id="interviewModal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="text-lg font-medium">Tambah Jadwal Interview Baru</h2>
            <button type="button" class="close" id="btnCloseModal">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('hr.interview.save') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kandidat *</label>
                    <input type="text" name="nama_kandidat" class="input w-full border" placeholder="Contoh: Budi Santoso" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Posisi yang Dilamar *</label>
                    <input type="text" name="posisi" class="input w-full border" placeholder="Contoh: Frontend Developer" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal & Waktu Interview *</label>
                    <input type="datetime-local" name="tanggal_waktu" class="input w-full border" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Catatan / Keterangan</label>
                    <textarea name="catatan" class="input w-full border" rows="3" placeholder="Catatan tambahan (opsional)"></textarea>
                </div>

                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" id="btnBatalModal" class="button border mr-2" style="padding: 10px 20px;">Batal</button>
                    <button type="submit" class="button text-white bg-theme-1" style="padding: 10px 20px;">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<style>
    .bg-theme-1 { background-color: #1e40af; }
    .bg-theme-1:hover { background-color: #1e3a8a; }

    /* Styling Tombol Tambah Custom */
    .btn-tambah-custom {
        display: inline-block !important;
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
    }
    .btn-tambah-custom:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
    }

    /* Styling Modal Pop-up (Dibuat Super Prioritas) */
    .modal {
        display: none;
        position: fixed;
        z-index: 999999 !important; /* Z-index diperbesar agar tidak tertutup elemen lain */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.6);
    }
    .modal.modal-show {
        display: block !important;
    }
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 0;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        width: 90%;
        position: relative;
        z-index: 9999999 !important;
    }
    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px 12px 0 0;
    }
    .close {
        color: #64748b;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        border: none;
        background: none;
    }
    .modal-body {
        padding: 24px;
    }
</style>

<script>
    // Pastikan seluruh elemen HTML sudah dimuat sebelum menjalankan JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen-elemen yang dibutuhkan
        const modal = document.getElementById('interviewModal');
        const btnBuka = document.getElementById('btnBukaModalInterview');
        const btnTutup = document.getElementById('btnCloseModal');
        const btnBatal = document.getElementById('btnBatalModal');

        // Fungsi Buka Modal
        if(btnBuka) {
            btnBuka.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah reload halaman
                if(modal) {
                    modal.classList.add('modal-show');
                    document.body.style.overflow = 'hidden'; // Matikan scroll layar belakang
                }
            });
        }

        // Fungsi Tutup Modal
        function tutupModal() {
            if(modal) {
                modal.classList.remove('modal-show');
                document.body.style.overflow = ''; // Nyalakan scroll kembali
            }
        }

        // Hubungkan tombol X dan tombol Batal ke fungsi tutupModal
        if(btnTutup) btnTutup.addEventListener('click', tutupModal);
        if(btnBatal) btnBatal.addEventListener('click', tutupModal);

        // Tutup modal jika mengklik area gelap di luarnya
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                tutupModal();
            }
        });

        // Merender icon feather (jika ada library-nya)
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection