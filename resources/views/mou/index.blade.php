@extends('layouts.app')
@section('content')
        <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="file-text" class="w-6 h-6 inline-block mr-2"></i>Mou</h1>
                <p>Riwayat Pembuatan Mou</p>
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
            @php
                $tableExists = true;
                try {
                    \Illuminate\Support\Facades\Schema::hasTable('mou');
                } catch (\Exception $e) {
                    $tableExists = false;
                }
            @endphp

            @if(!$tableExists)
            <div class="intro-y box overflow-hidden mt-5">
                <div class="px-5 py-10 text-center">
                    <div class="text-red-600 mb-4">
                        <i data-feather="alert-circle" class="w-16 h-16 mx-auto"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">Tabel Database Belum Dibuat</h3>
                    <p class="text-gray-600 mb-4">
                        Silakan buat tabel `mou` dan `mou_items` di database Anda.
                    </p>
                </div>
            </div>
            @else
            <div class="intro-y flex flex-col sm:flex-row items-center justify-between mt-8">
                <h2 class="text-lg font-medium">
                    Daftar Mou
                </h2>
                <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                    <!-- Menggunakan event listener langsung di script bawah agar aman dari konflik -->
                    <button type="button" id="triggerOpenModal" class="btn-buat-mou" onclick="openCreateModal();">
    Buat Mou
</button>
                </div>
            </div>

            <div class="intro-y box overflow-hidden mt-5">
                <div class="overflow-x-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="border-b-2 whitespace-no-wrap">No</th>
                                <th class="border-b-2 whitespace-no-wrap">Nama File</th>
                                <th class="border-b-2 whitespace-no-wrap">Customer</th>
                                <th class="border-b-2 whitespace-no-wrap">Lokasi</th>
                                <th class="border-b-2 whitespace-no-wrap">Tanggal</th>
                                <th class="border-b-2 whitespace-no-wrap">Grand Total</th>
                                <th class="border-b-2 whitespace-no-wrap">Dibuat Oleh</th>
                                <th class="border-b-2 whitespace-no-wrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @if(isset($mou_list) && count($mou_list) > 0)
                                @foreach ($mou_list as $mou)
                                <tr>
                                    <td class="border-b">{{ $no++ }}</td>
                                    <td class="border-b">{{ e($mou->file_name) }}</td>
                                    <td class="border-b">{{ e($mou->customer) }}</td>
                                    <td class="border-b">{{ e($mou->lokasi) }}</td>
                                    <td class="border-b">{{ date('d/m/Y', strtotime($mou->tanggal)) }}</td>
                                    <td class="border-b">Rp. {{ number_format($mou->grand_total, 0, ',', '.') }},-</td>
                                    <td class="border-b">{{ e($mou->kry_nama ?? '-') }}</td>
                                    <td class="border-b">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.mou.download', $mou->mou_id) }}" class="button button--sm text-white bg-theme-1" style="display: inline-block; white-space: nowrap;" target="_blank">
                                                <i data-feather="download" class="w-4 h-4" style="display: inline; margin-right: 4px; vertical-align: middle;"></i><span style="vertical-align: middle;">Download</span>
                                            </a>
                                            <a href="{{ route('admin.mou.edit_form', $mou->mou_id) }}" class="button button--sm text-white bg-blue-500" style="display: inline-block; white-space: nowrap;">
                                                <i data-feather="edit" class="w-4 h-4" style="display: inline; margin-right: 4px; vertical-align: middle;"></i><span style="vertical-align: middle;">Edit</span>
                                            </a>
                                            <button type="button" onclick="deleteMou({{ $mou->mou_id }})" class="button button--sm text-white bg-red-500" style="display: inline-block; white-space: nowrap;">
                                                <i data-feather="trash-2" class="w-4 h-4" style="display: inline; margin-right: 4px; vertical-align: middle;"></i><span style="vertical-align: middle;">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="8" class="text-center border-b py-8">
                                    <div class="text-gray-500">
                                        <i data-feather="inbox" class="w-12 h-12 mx-auto mb-2"></i>
                                        <p>Tidak ada data Mou</p>
                                        <p class="text-sm mt-2">Klik tombol "Buat Mou" untuk membuat Mou baru</p>
                                    </div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>

<!-- Modal Create Mou (Menggunakan class active untuk menampilkan) -->
<div class="modal" id="createMouModal">
    <div class="modal-content" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 class="text-lg font-medium">Buat Mou Baru</h2>
            <button type="button" class="close" id="btnCloseModal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="mouForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama File / Transaksi *</label>
                    <input type="text" name="file_name" id="file_name" class="input w-full border" placeholder="Contoh: Servis Laptop Asus 12-12-2025" required>
                    <small class="text-gray-500">Nama ini akan digunakan sebagai nama file PDF yang diunduh</small>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi *</label>
                    <select name="lokasi" id="lokasi" class="input w-full border" required>
                        <option value="">Pilih Lokasi</option>
                        <option value="Tegal">Tegal</option>
                        <option value="Cibubur">Cibubur</option>
                        <option value="Kampus Saintek">Kampus Saintek</option>
                        <option value="Kampus PKTJ">Kampus PKTJ</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal *</label>
                    <input type="date" name="tanggal" id="tanggal" class="input w-full border" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Customer *</label>
                    <input type="text" name="customer" id="customer" class="input w-full border" placeholder="Masukkan nama customer" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Surat Penawaran</label>
                    <div class="overflow-x-auto">
                        <table class="table" id="itemsTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Spesifikasi</th>
                                    <th>Qty</th>
                                    <th>Harga (IDR)</th>
                                    <th>Total (IDR)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-bold">Grand Total:</td>
                                    <td class="font-bold" id="grandTotal">Rp. 0,-</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <button type="button" id="btnTambahItem" class="button text-white bg-theme-1 mt-2">
                        <i data-feather="plus" class="w-4 h-4 mr-2"></i> Tambah Item
                    </button>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="button" id="btnBatalMou" class="button border mr-2">Batal</button>
                    <button type="submit" class="button text-white bg-theme-1">Simpan & Download PDF</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const getElem = (id) => document.getElementById(id);

// ==========================================
// 1. FUNGSI GLOBAL (Bisa dipanggil dari mana saja)
// ==========================================
function openCreateModal() {
    const modal = getElem('createMouModal');
    if (!modal) {
        console.error("Modal element tidak ditemukan!");
        return false;
    }
    
    // Tambahkan class agar modal tampil
    modal.classList.add('modal-show');
    document.body.style.overflow = 'hidden';

    // Reset form dan set nilai default
    const form = getElem('mouForm');
    if (form) form.reset();
    
    const tgl = getElem('tanggal'); 
    if (tgl) tgl.value = '{{ date("Y-m-d") }}';
    
    const body = getElem('itemsTableBody'); 
    if (body) body.innerHTML = '';
    
    const gt = getElem('grandTotal'); 
    if (gt) gt.textContent = 'Rp. 0,-';

    // Tambah baris item pertama secara otomatis
    addItem();

    // Render ulang Feather Icons jika ada
    if (typeof feather !== 'undefined') {
        setTimeout(() => feather.replace(), 50);
    }
    return false;
}

function closeCreateModal() {
    const modal = getElem('createMouModal');
    if (modal) {
        modal.classList.remove('modal-show');
    }
    document.body.style.overflow = '';
}

// ---------- ITEMS DYNAMIC FUNCTIONS ----------
function addItem() {
    const tbody = getElem('itemsTableBody');
    if (!tbody) return;
    const n = tbody.querySelectorAll('tr').length + 1;
    const row = document.createElement('tr');
    row.id = 'itemRow' + n;
    row.innerHTML = `
        <td>${n}</td>
        <td><input type="text" class="input w-full border" name="spesifikasi[]" required></td>
        <td><input type="number" class="input w-full border qty-input" step="0.01" min="0" name="qty[]" required onchange="calculateTotal(${n})"></td>
        <td><input type="text" class="input w-full border harga-input" name="harga[]" placeholder="0" required onchange="calculateTotal(${n})" onkeyup="formatCurrency(this)"></td>
        <td class="total-cell" id="total${n}">Rp. 0,-</td>
        <td><button type="button" class="button border text-red-600" onclick="removeItem(${n})"><i data-feather="trash-2" class="w-4 h-4"></i></button></td>
    `;
    tbody.appendChild(row);
    if (typeof feather !== 'undefined') feather.replace();
    updateItemNumbers();
}

function removeItem(id) {
    const row = getElem('itemRow' + id);
    if (row) row.remove();
    updateItemNumbers();
    calculateGrandTotal();
}

function updateItemNumbers() {
    const rows = getElem('itemsTableBody')?.querySelectorAll('tr') || [];
    rows.forEach((row, idx) => {
        const n = idx + 1;
        row.id = 'itemRow' + n;
        const first = row.querySelector('td:first-child'); if (first) first.textContent = n;
        const qty = row.querySelector('.qty-input'); if (qty) qty.setAttribute('onchange', `calculateTotal(${n})`);
        const harga = row.querySelector('.harga-input'); if (harga) harga.setAttribute('onchange', `calculateTotal(${n})`);
        const total = row.querySelector('.total-cell'); if (total) total.id = 'total' + n;
    });
}

function formatCurrency(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) input.value = parseInt(value, 10).toLocaleString('id-ID');
}

function calculateTotal(id) {
    const row = getElem('itemRow' + id);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.qty-input')?.value || '0') || 0;
    const hargaStr = (row.querySelector('.harga-input')?.value || '').replace(/[^\d]/g, '');
    const harga = parseFloat(hargaStr || '0') || 0;
    const total = qty * harga;
    const cell = row.querySelector('.total-cell');
    if (cell) cell.textContent = 'Rp. ' + total.toLocaleString('id-ID') + ',-';
    calculateGrandTotal();
}

function calculateGrandTotal() {
    const rows = getElem('itemsTableBody')?.querySelectorAll('tr') || [];
    let grand = 0;
    rows.forEach(row => {
        const cell = row.querySelector('.total-cell');
        if (cell) {
            const v = parseFloat(cell.textContent.replace(/[^\d]/g, '')) || 0;
            grand += v;
        }
    });
    const el = getElem('grandTotal'); if (el) el.textContent = 'Rp. ' + grand.toLocaleString('id-ID') + ',-';
}

// ==========================================
// 2. EVENT BINDINGS
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const modal = getElem('createMouModal');
    const btnOpen = getElem('triggerOpenModal');
    const btnClose = getElem('btnCloseModal');
    const btnBatal = getElem('btnBatalMou');
    const btnTambah = getElem('btnTambahItem');
    const form = getElem('mouForm');

    // Hubungkan tombol Buka Modal lewat JS secara aman DAN tambahkan atribut onclick langsung sebagai backup
    if (btnOpen) {
        btnOpen.setAttribute('onclick', 'openCreateModal(); return false;');
        btnOpen.addEventListener('click', (e) => {
            e.preventDefault();
            openCreateModal();
        });
    }

    if (btnClose) btnClose.addEventListener('click', closeCreateModal);
    if (btnBatal) btnBatal.addEventListener('click', closeCreateModal);

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeCreateModal();
        }
    });

    if (btnTambah) {
        btnTambah.addEventListener('click', (e) => {
            e.preventDefault();
            addItem();
        });
    }

    // Submit Form AJAX
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const items = [];
            const rows = getElem('itemsTableBody')?.querySelectorAll('tr') || [];
            rows.forEach(row => {
                const spesifikasi = row.querySelector('input[name="spesifikasi[]"]')?.value;
                const qty = row.querySelector('input[name="qty[]"]')?.value;
                const harga = row.querySelector('input[name="harga[]"]')?.value;
                if (spesifikasi && qty && harga) items.push({ spesifikasi, qty, harga });
            });
            if (!items.length) { alert('Minimal harus ada 1 item'); return; }
            formData.append('items', JSON.stringify(items));

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Memproses...';

            fetch('{{ route("admin.mou.create") }}', { 
                method: 'POST', 
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.ok ? r.json() : r.text().then(t => { throw new Error(t || 'Gagal'); }))
            .then(data => {
                if (data.status === 'success') {
                    const done = () => { if (data.pdf_url) window.open(data.pdf_url, '_blank'); closeCreateModal(); location.reload(); };
                    if (typeof Swal !== 'undefined') Swal.fire({icon:'success',title:'Berhasil!',text:'Mou berhasil dibuat!',confirmButtonColor:'#1e40af'}).then(done);
                    else { alert('Mou berhasil dibuat!'); done(); }
                } else {
                    const msg = data.message || 'Gagal membuat Mou';
                    if (typeof Swal !== 'undefined') Swal.fire({icon:'error',title:'Error!',text:msg,confirmButtonColor:'#dc2626'}); else alert(msg);
                }
            })
            .catch(err => {
                const msg = err.message || 'Terjadi kesalahan saat membuat Mou';
                if (typeof Swal !== 'undefined') Swal.fire({icon:'error',title:'Error!',text:msg,confirmButtonColor:'#dc2626'}); else alert(msg);
            })
            .finally(() => { submitBtn.disabled = false; submitBtn.innerHTML = originalText; });
        });
    }
});

// Delete Mou function
function deleteMou(mouId) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data Mou ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ url("Admin/mou/delete") }}/' + mouId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Mou berhasil dihapus',
                            confirmButtonColor: '#1e40af'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Gagal menghapus Mou: ' + data.message,
                            confirmButtonColor: '#dc2626'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menghapus Mou',
                        confirmButtonColor: '#dc2626'
                    });
                });
            }
        });
    } else {
        if (confirm('Apakah Anda yakin ingin menghapus Mou ini?')) {
            fetch('{{ url("Admin/mou/delete") }}/' + mouId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Mou berhasil dihapus');
                    location.reload();
                } else {
                    alert('Gagal menghapus Mou: ' + data.message);
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan saat menghapus Mou');
            });
        }
    }
}
</script>

<style>
#sidebarOverlay {
    display: none !important;
    pointer-events: none !important;
    opacity: 0 !important;
}

.btn-buat-mou {
    display: inline-block !important;
    width: auto !important;
    min-width: 150px !important;
    padding: 12px 24px !important;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    text-align: center !important;
    border: none !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3) !important;
    white-space: nowrap !important;
    line-height: 1.5 !important;
    position: relative !important;
    z-index: 999 !important;
    pointer-events: auto !important;
    user-select: none !important;
}

.btn-buat-mou:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
}

/* Modal Custom Styles agar pasti tampil saat class modal-show aktif */
.modal {
    display: none;
    position: fixed;
    z-index: 99999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

.modal.modal-show {
    display: block !important;
}

.modal-content {
    background-color: #fefefe;
    margin: 3% auto;
    padding: 0;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    max-width: 900px;
    width: 90%;
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

.modal-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #1e293b;
}

.close {
    color: #64748b;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.close:hover {
    color: #1e293b;
    background-color: #e2e8f0;
}

.modal-body {
    padding: 24px;
    max-height: calc(90vh - 120px);
    overflow-y: auto;
}
</style>
@endsection