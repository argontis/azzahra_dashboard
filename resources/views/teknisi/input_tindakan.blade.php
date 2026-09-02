@extends('layouts.app')

@section('content')
<style>
    .tindakan-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .btn-action-back {
        background-color: #475569 !important;
        color: #ffffff !important;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    .btn-action-back:hover {
        background-color: #334155 !important;
        color: #ffffff !important;
    }
    .btn-add-action {
        background-color: #2563eb !important;
        color: #ffffff !important;
        border: 1px solid #1d4ed8 !important;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-add-action:hover {
        background-color: #1d4ed8 !important;
    }
    .btn-order-part {
        background-color: #d97706 !important;
        color: #ffffff !important;
        border: 1px solid #b45309 !important;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-order-part:hover {
        background-color: #b45309 !important;
    }
    .btn-save-action {
        background-color: #16a34a !important;
        color: #ffffff !important;
        border: 1px solid #15803d !important;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(22, 163, 74, 0.25);
        transition: all 0.2s;
    }
    .btn-save-action:hover {
        background-color: #15803d !important;
        box-shadow: 0 4px 8px rgba(22, 163, 74, 0.35);
    }
    .btn-trash-row {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-trash-row:hover {
        background-color: #dc2626 !important;
    }
    .input-custom {
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        outline: none;
        transition: border-color 0.2s;
    }
    .input-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.15);
    }
</style>

<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="tool" class="w-5 h-5 inline-block mr-2"></i>Input Tindakan Perbaikan</h1>
        <p>Catat semua langkah perbaikan yang dilakukan</p>
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

<div class="content-area">
    <div class="mb-4 flex justify-end">
        <a href="{{ route('teknisi.index') }}" class="btn-action-back" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f1f5f9;border-radius:8px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;border:1px solid #e2e8f0;">
            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-3.5 rounded-lg mb-6 shadow-sm border border-green-200 text-xs flex items-center gap-2">
        <i data-feather="check-circle" style="width: 16px; height: 16px;" class="text-green-600"></i>
        <span>{{ session('sukses') }}</span>
    </div>
    @endif

    @if(session('gagal'))
    <div class="alert alert-danger bg-red-100 text-red-800 p-3.5 rounded-lg mb-6 shadow-sm border border-red-200 text-xs flex items-center gap-2">
        <i data-feather="alert-circle" style="width: 16px; height: 16px;" class="text-red-600"></i>
        <span>{{ session('gagal') }}</span>
    </div>
    @endif

    <!-- Top Card: Customer & Device Info -->
    <div class="tindakan-card p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
            <!-- Left: Customer Info (Col 4) -->
            <div class="col-span-12 md:col-span-4 flex items-center gap-4">
                <div style="width: 44px; height: 44px; border-radius: 50%; background-color: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-feather="user" style="width: 20px; height: 20px;"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm leading-tight">{{ $transaksi->customer->cos_nama ?? 'Customer' }}</h3>
                    <div class="text-xs text-gray-400 mt-1">Invoice: {{ $transaksi->trans_kode }}</div>
                    @php
                        $hp = $transaksi->customer->cos_hp ?? '-';
                        $masked_hp = strlen($hp) > 4 ? substr($hp, 0, -4) . 'XXXX' : $hp;
                    @endphp
                    <div class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                        <i data-feather="phone" style="width: 12px; height: 12px;"></i>
                        <span>(+62){{ ltrim($masked_hp, '+0') }}</span>
                    </div>
                </div>
            </div>

            <!-- Middle: Device Info (Col 4) -->
            <div class="col-span-12 md:col-span-4 flex items-center gap-4">
                <div style="width: 44px; height: 44px; border-radius: 12px; background-color: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-feather="smartphone" style="width: 20px; height: 20px;"></i>
                </div>
                <div class="space-y-0.5 text-xs text-gray-500">
                    <div class="font-bold text-gray-800 text-xs">{{ $transaksi->customer->cos_tipe ?? '-' }} {{ $transaksi->customer->cos_model ?? '' }}</div>
                    <div>Status: <span class="text-gray-700 font-semibold">{{ $transaksi->trans_status }}</span></div>
                    <div>SN: <span class="font-mono text-gray-700">{{ $transaksi->customer->cos_no_seri ?? '-' }}</span></div>
                    <div>Password Device: <span class="text-gray-700">{{ $transaksi->customer->cos_pswd ?? '-' }}</span></div>
                </div>
            </div>

            <!-- Right: Keluhan Customer (Col 4) -->
            <div class="col-span-12 md:col-span-4">
                <div class="text-xs text-gray-700 font-medium mb-1.5">Keluhan Customer:</div>
                <div style="border: 1px solid #fecaca; background-color: #fff5f5; border-radius: 8px; padding: 10px 14px; color: #ef4444; font-size: 12px; line-height: 1.4; min-height: 42px; display: flex; align-items: center;">
                    {{ $transaksi->customer->cos_keluhan ?? 'Tidak ada catatan keluhan.' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Card: Catat Tindakan Perbaikan -->
    <div class="tindakan-card p-6 mb-8">
        <!-- Title -->
        <div class="flex items-center gap-2 mb-6">
            <div style="width: 22px; height: 22px; border-radius: 50%; background-color: #3b82f6; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: bold;">
                +
            </div>
            <h3 class="font-bold text-gray-800 text-sm">Catat Tindakan Perbaikan</h3>
        </div>

        <!-- Form Input Row -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end mb-8">
            <!-- Tindakan Dropdown (Col 4) -->
            <div class="col-span-12 md:col-span-4">
                <label class="block text-xs text-gray-600 mb-1.5">Tindakan Perbaikan</label>
                <select id="selectTindakan" class="input-custom w-full" style="background-color: #f8fafc;">
                    <option value="">Pilih Tindakan</option>
                    <option value="Mengganti LCD">Mengganti LCD</option>
                    <option value="Mengganti Keyboard">Mengganti Keyboard</option>
                    <option value="Mengganti Baterai">Mengganti Baterai</option>
                    <option value="Install Ulang OS & Driver">Install Ulang OS & Driver</option>
                    <option value="Pembersihan Kipas & Thermal Paste">Pembersihan Kipas & Thermal Paste</option>
                    <option value="Perbaikan Engsel / Casing">Perbaikan Engsel / Casing</option>
                    <option value="Ganti SSD / Upgrade RAM">Ganti SSD / Upgrade RAM</option>
                    <option value="Service Motherboard / Reball IC">Service Motherboard / Reball IC</option>
                    <option value="Perbaikan Jack DC / Power Connector">Perbaikan Jack DC / Power Connector</option>
                    <option value="Service Adaptor / Charger">Service Adaptor / Charger</option>
                    <option value="Flash BIOS / EEPROM">Flash BIOS / EEPROM</option>
                    <option value="Lainnya">Lainnya (Kustom)</option>
                </select>
            </div>

            <!-- Keterangan Detail (Col 4) -->
            <div class="col-span-12 md:col-span-4">
                <label class="block text-xs text-gray-600 mb-1.5">Keterangan Detail</label>
                <input type="text" id="inputKeterangan" class="input-custom w-full" style="background-color: #ffffff;" placeholder="Detail perbaikan yang dilakukan">
            </div>

            <!-- Qty (Col 1) -->
            <div class="col-span-12 md:col-span-1">
                <label class="block text-xs text-gray-600 mb-1.5">Qty</label>
                <input type="number" id="inputQty" value="1" min="1" class="input-custom w-full text-center" style="background-color: #ffffff;">
            </div>

            <!-- Action Buttons (Col 3) -->
            <div class="col-span-12 md:col-span-3 flex items-center gap-2">
                <button type="button" id="btnAddTindakan" class="btn-add-action flex-1">
                    <i data-feather="plus" style="width: 14px; height: 14px;"></i>
                    <span>Tambah Tindakan</span>
                </button>
                <button type="button" data-toggle="modal" data-target="#orderSparepartModal" class="btn-order-part">
                    <i data-feather="box" style="width: 14px; height: 14px;"></i>
                    <span>Order Sparepart</span>
                </button>
            </div>
        </div>

        <!-- Table Form Tindakan Baru -->
        <form method="POST" action="{{ route('teknisi.save_tindakan') }}" id="formSimpanTindakan" onsubmit="return validateTindakanForm()">
            @csrf
            <input type="hidden" name="trans_kode" value="{{ $transaksi->trans_kode }}">

            <div class="mb-3 flex items-center gap-2 text-gray-700 font-bold text-xs">
                <i data-feather="list" style="width: 15px; height: 15px; color: #3b82f6;"></i>
                <span>Tindakan Baru</span>
            </div>

            <div class="overflow-x-auto mb-6" style="border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
                <table class="table w-full text-xs">
                    <thead>
                        <tr style="border-bottom: 1px solid #f1f5f9; color: #94a3b8; font-weight: 600;">
                            <th class="w-12 text-center py-2.5">#</th>
                            <th class="py-2.5 text-left">TINDAKAN</th>
                            <th class="w-16 text-center py-2.5">QTY</th>
                            <th class="py-2.5 text-left">KETERANGAN</th>
                            <th class="w-24 text-right py-2.5 pr-4">BIAYA</th>
                            <th class="w-16 text-center py-2.5">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tindakanTableBody">
                        @if(isset($transaksi->tindakan) && count($transaksi->tindakan) > 0)
                            @foreach($transaksi->tindakan as $idx => $t)
                                <tr style="border-bottom: 1px solid #f8fafc;">
                                    <td class="text-center text-gray-500 py-3 row-number">{{ $idx + 1 }}</td>
                                    <td class="font-medium text-gray-800 py-3">
                                        {{ $t->tdkn_barang }}
                                        <input type="hidden" name="tindakan[]" value="{{ $t->tdkn_barang }}">
                                    </td>
                                    <td class="text-center font-semibold text-gray-700 py-3">
                                        {{ $t->tdkn_qty }}
                                        <input type="hidden" name="qty[]" value="{{ $t->tdkn_qty }}">
                                    </td>
                                    <td class="text-gray-500 py-3">
                                        {{ $t->tdkn_barang }}
                                        <input type="hidden" name="ket[]" value="{{ $t->tdkn_barang }}">
                                    </td>
                                    <td class="text-right font-mono text-gray-700 pr-4 py-3">
                                        Rp{{ number_format($t->tdkn_subtot ?? 0, 0, ',', '.') }}
                                        <input type="hidden" name="subtot[]" value="{{ $t->tdkn_subtot ?? 0 }}">
                                    </td>
                                    <td class="text-center py-3">
                                        <button type="button" class="btn-trash-row btn-remove-row">
                                            <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center py-6 text-gray-400 italic">Belum ada tindakan perbaikan yang ditambahkan. Gunakan form di atas untuk menambahkan tindakan.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Submit Button (Green) -->
            <div class="flex justify-end pt-2">
                <button type="submit" class="btn-save-action">
                    <i data-feather="check" style="width: 16px; height: 16px;"></i>
                    <span>Simpan Tindakan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Order Sparepart -->
<div class="modal" id="orderSparepartModal">
    <div class="modal__content modal__content--lg p-6">
        <div class="flex items-center px-2 py-2 border-b border-gray-200 mb-5">
            <h2 class="font-bold text-base text-gray-800 mr-auto flex items-center gap-2">
                <i data-feather="box" style="width: 18px; height: 18px; color: #d97706;"></i>
                Order Sparepart Tambahan
            </h2>
            <button type="button" data-dismiss="modal" class="button border text-gray-700">&times;</button>
        </div>

        <form action="{{ route('teknisi.order_sparepart') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="trans_kode" value="{{ $transaksi->trans_kode }}">

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Barang / Sparepart *</label>
                <input type="text" name="barang_nama" class="input w-full border rounded-lg p-2 text-xs" placeholder="Contoh: LCD Asus A416 / Keyboard Acer Nitro 5..." required>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Status Ketersediaan di Teknisi *</label>
                <select name="ketersediaan" class="input w-full border rounded-lg p-2 text-xs" required>
                    <option value="tersedia">Tersedia (Bisa langsung dipasang)</option>
                    <option value="tidak_ada">Tidak Ada (Minta ke Admin / Gudang)</option>
                </select>
            </div>

            <div style="background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; padding: 12px; font-size: 12px; color: #92400e;">
                <span class="font-semibold">* Catatan:</span> Jika stok tidak tersedia di teknisi, pesanan sparepart akan diteruskan ke Admin / Gudang dan status order akan masuk ke Konfirmasi.
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button border text-gray-700 px-4 py-2 text-xs rounded-lg">Batal</button>
                <button type="submit" class="btn-order-part px-4 py-2 text-xs font-semibold rounded-lg">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnAddTindakan = document.getElementById('btnAddTindakan');
    const selectTindakan = document.getElementById('selectTindakan');
    const inputKeterangan = document.getElementById('inputKeterangan');
    const inputQty = document.getElementById('inputQty');
    const tbody = document.getElementById('tindakanTableBody');

    btnAddTindakan.addEventListener('click', function() {
        let tindakanName = selectTindakan.value.trim();
        if (!tindakanName) {
            Swal.fire({ icon: 'warning', title: 'Pilih Tindakan', text: 'Silakan pilih tindakan perbaikan terlebih dahulu.' });
            return;
        }

        if (tindakanName === 'Lainnya') {
            const customTindakan = prompt('Masukkan nama tindakan perbaikan:');
            if (!customTindakan || !customTindakan.trim()) return;
            tindakanName = customTindakan.trim();
        }

        const ket = inputKeterangan.value.trim() || tindakanName;
        const qty = parseInt(inputQty.value) || 1;

        // Remove empty row if exists
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        const rowCount = tbody.querySelectorAll('tr').length + 1;
        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid #f8fafc';
        tr.innerHTML = `
            <td class="text-center text-gray-500 py-3 row-number">${rowCount}</td>
            <td class="font-medium text-gray-800 py-3">
                ${tindakanName}
                <input type="hidden" name="tindakan[]" value="${tindakanName}">
            </td>
            <td class="text-center font-semibold text-gray-700 py-3">
                ${qty}
                <input type="hidden" name="qty[]" value="${qty}">
            </td>
            <td class="text-gray-500 py-3">
                ${ket}
                <input type="hidden" name="ket[]" value="${ket}">
            </td>
            <td class="text-right font-mono text-gray-700 pr-4 py-3">
                Rp0
                <input type="hidden" name="subtot[]" value="0">
            </td>
            <td class="text-center py-3">
                <button type="button" class="btn-trash-row btn-remove-row">
                    <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);

        // Reset inputs
        selectTindakan.value = '';
        inputKeterangan.value = '';
        inputQty.value = '1';

        if (typeof feather !== 'undefined') feather.replace();
    });

    tbody.addEventListener('click', function(e) {
        const btnRemove = e.target.closest('.btn-remove-row');
        if (btnRemove) {
            btnRemove.closest('tr').remove();
            reindexRows();
        }
    });

    function reindexRows() {
        const rows = tbody.querySelectorAll('tr:not(#emptyRow)');
        if (rows.length === 0) {
            tbody.innerHTML = `<tr id="emptyRow"><td colspan="6" class="text-center py-6 text-gray-400 italic">Belum ada tindakan perbaikan yang ditambahkan. Gunakan form di atas untuk menambahkan tindakan.</td></tr>`;
            return;
        }
        rows.forEach((row, i) => {
            const numCell = row.querySelector('.row-number');
            if (numCell) numCell.innerText = i + 1;
        });
    }
});

function validateTindakanForm() {
    const tbody = document.getElementById('tindakanTableBody');
    const rows = tbody.querySelectorAll('tr:not(#emptyRow)');
    if (rows.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Tindakan Kosong',
            text: 'Mohon tambahkan minimal 1 tindakan perbaikan sebelum menyimpan.'
        });
        return false;
    }
    return true;
}
</script>
@endsection
