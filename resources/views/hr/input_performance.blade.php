@extends('layouts.app')
@section('content')
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Sistem Performa</h1>
                <p>Input Poin Karyawan Mingguan</p>
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

        <div class="content-area">
            <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
                <h2 class="text-lg font-medium mr-auto">
                    Form Input Poin Performa
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                    <a href="{{ url('/HR/karyawan') }}" class="button border text-gray-700 bg-white" style="display: inline-flex; align-items: center;">
                        <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <form action="{{ route('hr.save_performance') }}" method="POST">
                @csrf
                <div class="intro-y box mt-5">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Parameter Periode
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block mb-2 font-semibold">Bulan *</label>
                                <input type="month" name="bulan" class="input w-full border" value="{{ date('Y-m') }}" required>
                            </div>
                            <div>
                                <label class="block mb-2 font-semibold">Minggu Ke- *</label>
                                <select name="minggu_ke" class="input w-full border" required>
                                    <option value="1">Minggu 1</option>
                                    <option value="2">Minggu 2</option>
                                    <option value="3">Minggu 3</option>
                                    <option value="4">Minggu 4</option>
                                    <option value="5">Minggu 5</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="intro-y box mt-5 overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200 justify-between">
                        <h2 class="font-medium text-base mr-auto">
                            Daftar Nilai Karyawan
                        </h2>
                        <button type="button" onclick="addRow()" class="button text-white bg-theme-1" style="display: inline-flex; align-items: center;">
                            <i data-feather="plus" class="w-4 h-4 mr-2"></i> Tambah Baris
                        </button>
                    </div>
                    
                    <div class="p-5">
                        <div class="overflow-x-auto">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <th class="border-b-2 text-center whitespace-no-wrap" style="width: 5%;">No</th>
                                        <th class="border-b-2 whitespace-no-wrap" style="width: 35%;">Nama Karyawan</th>
                                        <th class="border-b-2 text-center whitespace-no-wrap">Poin Kehadiran</th>
                                        <th class="border-b-2 text-center whitespace-no-wrap">Poin Kedisiplinan</th>
                                        <th class="border-b-2 text-center whitespace-no-wrap">Poin Target</th>
                                        <th class="border-b-2 text-center whitespace-no-wrap" style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    </tbody>
                            </table>
                        </div>
                        
                        <div class="flex justify-end mt-5 space-x-2">
                            <button type="button" onclick="window.history.back()" class="button border text-gray-700 bg-white mr-2">Batal</button>
                            <button type="submit" class="button text-white bg-theme-1" style="display: inline-flex; align-items: center;">
                                <i data-feather="save" class="w-4 h-4 mr-2"></i> Simpan Poin
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<script>
    // Mengambil data karyawan dari controller
    const karyawanList = @json($karyawan_list);
    let rowCount = 0;

    function addRow() {
        rowCount++;
        const tbody = document.getElementById('tableBody');
        const tr = document.createElement('tr');
        tr.id = `row_${rowCount}`;
        
        // Loop opsi pilihan karyawan
        let options = '<option value="">-- Pilih Karyawan --</option>';
        karyawanList.forEach(k => {
            options += `<option value="${k.kry_kode}">${k.kry_nama} (${k.kry_level})</option>`;
        });

        // Struktur baris tabel menggunakan class "input w-full border" sesuai tema template
        tr.innerHTML = `
            <td class="border-b text-center index-cell" style="vertical-align: middle;">${rowCount}</td>
            <td class="border-b">
                <select name="id_karyawan[]" class="input w-full border" required>
                    ${options}
                </select>
            </td>
            <td class="border-b">
                <input type="number" name="poin_kehadiran[]" min="0" max="100" class="input w-full border text-center" placeholder="0 - 100" required>
            </td>
            <td class="border-b">
                <input type="number" name="poin_kedisiplinan[]" min="0" max="100" class="input w-full border text-center" placeholder="0 - 100" required>
            </td>
            <td class="border-b">
                <input type="number" name="poin_target[]" min="0" max="100" class="input w-full border text-center" placeholder="0 - 100" required>
            </td>
            <td class="border-b text-center" style="vertical-align: middle;">
                <button type="button" onclick="removeRow(${rowCount})" class="button border text-red-600 bg-white px-2 py-1" title="Hapus Baris">
                    <i data-feather="trash-2" class="w-4 h-4"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(tr);
        
        // Render ulang icon Feather setelah baris ditambahkan
        if (typeof feather !== 'undefined') feather.replace();
        updateNumbers();
    }

    function removeRow(id) {
        const row = document.getElementById(`row_${id}`);
        if(row) {
            row.remove();
            updateNumbers();
        }
    }

    function updateNumbers() {
        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach((row, index) => {
            row.querySelector('.index-cell').textContent = index + 1;
        });
    }

    // Tambahkan 1 baris kosong otomatis saat halaman pertama dimuat
    document.addEventListener('DOMContentLoaded', () => {
        addRow();
    });
</script>

<style>
    /* Styling ekstra untuk merapikan tombol-tombol icon */
    .button {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .bg-theme-1 {
        background-color: #1e40af; /* Warna dasar biru tua */
    }
    .bg-theme-1:hover {
        background-color: #1e3a8a;
    }
</style>
@endsection