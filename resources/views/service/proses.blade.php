@extends('layouts.app')

@section('content')
<!-- Header Area -->
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="users" class="w-6 h-6 inline-block mr-2"></i>Customer</h1>
        <p>Antrean Transaksi Service Customer</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem &amp; Maintenance" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">
    <div class="sukses" data-sukses="{{ session('sukses') }}"></div>
    <div class="gagal" data-gagal="{{ session('gagal') }}"></div>
    
    <div class="intro-y flex flex-col sm:flex-row items-center mt-2 mb-3">
        <h2 class="text-xl font-bold mr-auto text-gray-800">
            Data Costomer
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <button type="button" onclick="openEditModal()" class="button text-white bg-theme-1 shadow-md px-4 py-2 rounded-md font-medium text-xs flex items-center gap-1">
                <i data-feather="edit" class="w-3.5 h-3.5"></i> Edit Data
            </button>
        </div>
    </div>

    <div class="intro-y grid grid-cols-12 gap-5 mt-3">
        <!-- Sidebar Filter -->
        <div class="col-span-12 lg:col-span-3 xxl:col-span-2">
            <div class="intro-y box p-3 lg:p-5">
                <div class="flex flex-row lg:flex-col overflow-x-auto lg:overflow-visible gap-2 pb-1 lg:pb-0 filter-pill-bar">
                    <a href="{{ route('service.antrean', 'baru') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'baru' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}">
                        <i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
                    </a>
                    <a href="{{ route('service.antrean', 'proses') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'proses' || $current_status == 'diproses' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi diproses 
                    </a>
                    <a href="{{ route('service.antrean', 'konfirmasi') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'konfirmasi' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}">
                        <i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfirmasi
                    </a>
                    <a href="{{ route('service.antrean', 'pelunasan') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'pelunasan' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Pelunasan 
                    </a>
                    <a href="{{ route('service.antrean', 'lunas') }}" class="flex items-center px-3 py-2 rounded-md shrink-0 whitespace-nowrap text-xs lg:text-sm {{ $current_status == 'lunas' ? 'bg-theme-1 text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="users"></i> Customer
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10 space-y-5">
            @php
                $isPriority = $customer && (($customer->cos_tier === 'prioritas') || ($customer->cos_score >= 5) || ($customer->total_transaksi >= 5));
                $isLoyal = $customer && (($customer->cos_tier === 'loyal') || ($customer->cos_score >= 3 && !$isPriority));
                $score = $customer->cos_score ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
                $totalTx = $customer->total_transaksi ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
            @endphp

            @if($isPriority)
                <div class="intro-y box p-4 bg-gradient-to-r from-amber-500 via-amber-600 to-yellow-600 rounded-xl shadow-md text-white flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-xl flex-shrink-0">
                            👑
                        </div>
                        <div>
                            <div class="font-bold text-sm md:text-base flex items-center gap-2">
                                <span>PELANGGAN PRIORITAS (VIP) - Skor {{ $score }}</span>
                                <span class="bg-white text-amber-800 text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider">Prioritas Utama</span>
                            </div>
                            <p class="text-xs text-amber-100 mt-0.5">Customer memiliki {{ $totalTx }} riwayat transaksi dan berhak atas seluruh keuntungan VIP.</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                        <span class="bg-black/20 px-2.5 py-1 rounded-md flex items-center gap-1 border border-white/10">
                            <i data-feather="percent" class="w-3 h-3"></i> Diskon s/d 50%
                        </span>
                        <span class="bg-black/20 px-2.5 py-1 rounded-md flex items-center gap-1 border border-white/10">
                            <i data-feather="zap" class="w-3 h-3"></i> Antrean Cepat
                        </span>
                        <span class="bg-black/20 px-2.5 py-1 rounded-md flex items-center gap-1 border border-white/10">
                            <i data-feather="truck" class="w-3 h-3"></i> Pickup / Onsite
                        </span>
                    </div>
                </div>
            @endif

            <!-- Customer Profile Info Box -->
            <div class="intro-y box p-5 bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <!-- Photo & Name -->
                    <div class="col-span-12 md:col-span-4 flex items-center gap-4">
                        <div class="relative w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-xs font-semibold overflow-hidden flex-shrink-0">
                            <span>200x200</span>
                            <div class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full p-1 shadow cursor-pointer" onclick="openEditModal()">
                                <i data-feather="camera" class="w-3 h-3"></i>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <h3 class="text-lg font-bold text-gray-800">{{ $customer->cos_nama ?? 'N/A' }}</h3>
                                @if($isPriority)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        👑 VIP (Skor 5)
                                    </span>
                                @elseif($isLoyal)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                        ⭐ Loyal (Skor {{ $score }})
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-600">
                                        Reguler
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $customer->id_costomer ?? $transaksi->cos_kode }}</p>
                        </div>
                    </div>

                    <!-- Contact & Device Info -->
                    <div class="col-span-12 md:col-span-4 border-l border-r border-gray-200 px-4 space-y-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <i data-feather="phone" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $customer->cos_hp ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-feather="eye" class="w-4 h-4 text-gray-400"></i>
                            <span class="capitalize">{{ $transaksi->trans_status ?? 'Diproses' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-feather="laptop" class="w-4 h-4 text-gray-400"></i>
                            <span>{{ $customer->cos_tipe ?? $customer->cos_device ?? 'Laptop' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-feather="key" class="w-4 h-4 text-gray-400"></i>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span>{{ ($customer->cos_pswd_type ?? 'text') == 'pattern_desc' ? 'Pola' : (($customer->cos_pswd_type ?? 'text') == 'pin' ? 'PIN' : 'Pass') }}: <strong class="text-gray-800">{{ $customer->cos_pswd ?? '-' }}</strong></span>
                                @if(!empty($customer->cos_pswd_canvas))
                                    <button type="button" onclick="showPatternPreview('{{ $customer->cos_pswd_canvas }}', '{{ addslashes($customer->cos_pswd ?? '') }}')" class="inline-flex items-center text-[10px] font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200 shadow-xs" title="Lihat Gambar Pola">
                                        <i data-feather="grid" class="w-3 h-3 mr-0.5"></i> Lihat Pola
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Keluhan Box -->
                    <div class="col-span-12 md:col-span-4 pl-2">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Keluhan</span>
                            <i data-feather="edit-2" class="w-3.5 h-3.5 text-gray-400 hover:text-blue-600 cursor-pointer" onclick="openEditModal()" title="Edit Keluhan & Customer Data"></i>
                        </div>
                        <p class="text-sm font-medium text-red-500 leading-snug cursor-pointer" onclick="openEditModal()">
                            {{ $customer->cos_keluhan ?? 'Mati total setelah kena air' }}
                        </p>
                    </div>
                </div>

                <!-- Input Proses Tab Sub-nav -->
                <div class="border-b border-gray-200 mt-6 flex gap-6 text-sm font-semibold">
                    <a href="#" class="pb-2 border-b-2 border-blue-600 text-blue-600">Input Proses</a>
                </div>
            </div>

            <!-- Single Unified Box Card Container -->
            <div class="intro-y box p-6 bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Section 1: Catat Tindakan Perbaikan Header -->
                <div class="flex items-center text-gray-800 font-bold text-base mb-4">
                    <span class="w-7 h-7 rounded-full text-white flex items-center justify-center mr-3 shadow-sm flex-shrink-0" style="background-color: #2563eb;">
                        <i data-feather="plus" class="w-4 h-4"></i>
                    </span>
                    Catat Tindakan Perbaikan
                </div>

                <!-- Form Area -->
                <form action="{{ route('service.save_tindakan', $transaksi->trans_kode) }}" method="POST" class="mb-8">
                    @csrf
                    <div class="flex flex-col lg:flex-row items-end gap-3">
                        <!-- Dropdown Tindakan -->
                        <div class="w-full lg:w-3/12">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tindakan Perbaikan</label>
                            <select name="tindakan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white text-gray-800 focus:outline-none focus:border-blue-500" style="height: 42px;" required>
                                <option value="">Pilih Tindakan</option>
                                <option value="Mengganti Part">Mengganti Part</option>
                                <option value="Memperbaiki Part">Memperbaiki Part</option>
                                <option value="Custom">Custom</option>
                            </select>
                        </div>

                        <!-- Keterangan Detail -->
                        <div class="w-full lg:w-4/12">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan Detail</label>
                            <textarea name="keterangan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:border-blue-500 resize-y" placeholder="Detail perbaikan yang dilakukan" rows="1" style="height: 42px; min-height: 42px;"></textarea>
                        </div>

                        <!-- Qty -->
                        <div class="w-full lg:w-2/12">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Qty</label>
                            <input type="number" name="qty" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 text-center focus:outline-none focus:border-blue-500" value="1" min="1" style="height: 42px;">
                        </div>

                        <!-- Stacked Action Buttons (Right side) -->
                        <div class="w-full lg:w-3/12 flex flex-col gap-2">
                            <button type="submit" class="w-full text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm transition-all" style="background-color: #2563eb; height: 38px;">
                                <i data-feather="plus" class="w-4 h-4"></i> Tambah Tindakan
                            </button>
                            <a href="{{ route('admin.order.index') }}" class="w-full text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm transition-all" style="background-color: #d97706; height: 38px;">
                                <i data-feather="box" class="w-4 h-4"></i> Order Sparepart
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Section 2: Daftar Tindakan Perbaikan Header -->
                <div class="flex items-center text-gray-700 font-bold text-sm mb-4 pt-4 border-t border-gray-200">
                    <i data-feather="list" class="w-4 h-4 mr-2 text-blue-500"></i>
                    Daftar Tindakan Perbaikan
                </div>

                <!-- Table Container -->
                <div class="border border-gray-200 rounded-lg overflow-hidden mb-6">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-gray-400 uppercase text-xs font-bold border-b border-gray-200">
                                <th class="py-3 px-4 w-12 text-center">#</th>
                                <th class="py-3 px-4">TINDAKAN</th>
                                <th class="py-3 px-4 text-center w-20">QTY</th>
                                <th class="py-3 px-4">KETERANGAN</th>
                                <th class="py-3 px-4 text-right">BIAYA</th>
                                <th class="py-3 px-4 text-center w-20">AKSI</th>
                            </tr>
                        </thead>
                        @if($tindakans->isEmpty())
                            <tbody>
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-14 h-14 text-gray-300 flex items-center justify-center mb-3">
                                                <i data-feather="tool" class="w-10 h-10 stroke-1"></i>
                                            </div>
                                            <h4 class="text-sm font-semibold text-gray-500">Belum ada tindakan yang ditambahkan</h4>
                                            <p class="text-xs text-gray-400 mt-1">Gunakan form di atas untuk menambah tindakan perbaikan</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        @else
                            <tbody class="divide-y divide-gray-200">
                                @foreach($tindakans as $index => $tdk)
                                    <tr>
                                        <td class="py-3 px-4 text-center text-gray-500">{{ $index + 1 }}</td>
                                        <td class="py-3 px-4 font-medium text-gray-800">{{ $tdk->tdkn_barang }}</td>
                                        <td class="py-3 px-4 text-center text-gray-700">{{ $tdk->tdkn_qty }}</td>
                                        <td class="py-3 px-4 text-gray-600">{{ $tdk->keterangan ?? '-' }}</td>
                                        <td class="py-3 px-4 text-right font-semibold text-gray-700">Rp {{ number_format($tdk->tdkn_subtot, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4 text-center">
                                            <form action="{{ route('service.delete_tindakan', $tdk->tdkn_kode) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus tindakan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 p-1">
                                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                    <tr id="noSearchResultRow" style="display: none;">
                                        <td colspan="6" class="py-6 text-center text-gray-500">
                                            <i data-feather="search" class="w-6 h-6 mx-auto mb-1 opacity-40"></i>
                                            <p>Tidak ada tindakan yang cocok dengan pencarian.</p>
                                        </td>
                                    </tr>
                            </tbody>
                        @endif
                    </table>
                </div>

                <!-- Simpan Tindakan Button (Bottom Right) -->
                <div class="flex justify-end pt-2">
                    <form action="{{ route('service.simpan_proses', $transaksi->trans_kode) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white px-6 py-2.5 rounded-lg font-bold text-sm flex items-center gap-2 shadow-md hover:shadow-lg transition-all" style="background-color: #16a34a;">
                            <i data-feather="save" class="w-4 h-4"></i> Simpan Tindakan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Customer Data (Matching 3-Step Wizard Layout) -->
<div id="editCustomerModal" class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 hidden" style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(2px);" onclick="if(event.target === this) closeEditModal()">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-3xl p-6 sm:p-8 relative my-8 mx-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-gray-200 pb-3 mb-6">
            <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i data-feather="edit-3" class="w-5 h-5 text-blue-600"></i> Edit Transaksi Customer
            </h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold leading-none">&times;</button>
        </div>

        <!-- Wizard Step Indicators -->
        <div class="nav-tabs wizard flex flex-col lg:flex-row justify-center px-5 mb-8 relative">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                <button type="button" id="edit_step_custom" onclick="switchEditTab('custom')" class="w-10 h-10 rounded-full button text-white bg-theme-1 edit-wizard-step font-bold text-sm shadow">1</button>
                <div class="lg:w-32 font-medium text-sm lg:mt-2 ml-3 lg:mx-auto text-gray-700">Data Customer</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-3 lg:mt-0 lg:block flex-1 z-10">
                <button type="button" id="edit_step_unit" onclick="switchEditTab('unit')" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200 edit-wizard-step font-bold text-sm">2</button>
                <div class="lg:w-32 font-medium text-sm lg:mt-2 ml-3 lg:mx-auto text-gray-700">Data Unit</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-3 lg:mt-0 lg:block flex-1 z-10">
                <button type="button" id="edit_step_kelket" onclick="switchEditTab('kelket')" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200 edit-wizard-step font-bold text-sm">3</button>
                <div class="lg:w-32 font-medium text-sm lg:mt-2 ml-3 lg:mx-auto text-gray-700">Keluhan & Keterangan</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-4 z-0"></div>
        </div>

        <!-- Form -->
        <form action="{{ route('service.update_customer', $customer->id_costomer ?? $transaksi->cos_kode) }}" method="POST">
            @csrf

            <!-- Step 1: Data Customer -->
            <div class="edit-tab-pane block" id="edit_pane_custom">
                <div class="pt-4 border-t border-gray-200">
                    <div class="font-bold text-base text-gray-800 mb-4">1. Data Customer</div>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Customer <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ $customer->cos_nama ?? '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" required placeholder="Masukan nama customer">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">No Telepon <span class="text-red-500">*</span></label>
                            <input type="text" name="tlp" value="{{ $customer->cos_hp ?? '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" required placeholder="Masukan no tlep customer">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Alamat</label>
                            <textarea name="alamat" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="2">{{ $customer->cos_alamat ?? '' }}</textarea>
                        </div>
                        <div class="col-span-12 sm:col-span-6 space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Cabang</label>
                                <select name="cabang" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="Tegal" {{ ($customer->cos_cabang ?? '') == 'Tegal' ? 'selected' : '' }}>Tegal</option>
                                    <option value="Cibubur" {{ ($customer->cos_cabang ?? '') == 'Cibubur' ? 'selected' : '' }}>Cibubur</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Lahir</label>
                                <input type="date" name="cos_tgl_lahir" value="{{ $customer->cos_tgl_lahir ?? '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="switchEditTab('unit')" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-md transition-colors">Lanjut ke Data Unit &rarr;</button>
                </div>
            </div>

            <!-- Step 2: Data Unit -->
            <div class="edit-tab-pane hidden" id="edit_pane_unit">
                <div class="pt-4 border-t border-gray-200">
                    <div class="font-bold text-base text-gray-800 mb-4">2. Data Unit</div>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Status Garansi</label>
                            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <option value="">-</option>
                                <option value="CID" {{ ($customer->cos_status ?? '') == 'CID' ? 'selected' : '' }}>CID</option>
                                <option value="IW" {{ ($customer->cos_status ?? '') == 'IW' ? 'selected' : '' }}>IW</option>
                                <option value="OOW" {{ ($customer->cos_status ?? '') == 'OOW' ? 'selected' : '' }}>OOW</option>
                            </select>
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Device</label>
                            <input type="text" name="device" value="{{ $customer->cos_device ?? '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukan device">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Merk / Type <span class="text-red-500">*</span></label>
                            <input type="text" name="type" value="{{ $customer->cos_tipe ?? '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" required placeholder="Masukan type unit">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Model</label>
                            <input type="text" name="model" value="{{ $customer->cos_model ?? '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukan model unit">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">No Seri</label>
                            <input type="text" name="seri" value="{{ $customer->cos_no_seri ?? '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukan no seri">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tipe Password</label>
                            <div class="flex items-center space-x-4 mt-2">
                                <label class="flex items-center text-xs font-medium text-gray-700 cursor-pointer">
                                    <input type="radio" name="pswd_type" value="text" {{ ($customer->cos_pswd_type ?? 'text') == 'text' ? 'checked' : '' }} class="mr-1.5" onchange="toggleEditPswd('text')"> Text
                                </label>
                                <label class="flex items-center text-xs font-medium text-gray-700 cursor-pointer">
                                    <input type="radio" name="pswd_type" value="pin" {{ ($customer->cos_pswd_type ?? '') == 'pin' ? 'checked' : '' }} class="mr-1.5" onchange="toggleEditPswd('pin')"> PIN
                                </label>
                                <label class="flex items-center text-xs font-medium text-gray-700 cursor-pointer">
                                    <input type="radio" name="pswd_type" value="pattern_desc" {{ ($customer->cos_pswd_type ?? '') == 'pattern_desc' ? 'checked' : '' }} class="mr-1.5" onchange="toggleEditPswd('desc')"> Pola
                                </label>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6" id="edit_pswd_text_div" style="{{ ($customer->cos_pswd_type ?? 'text') == 'pattern_desc' ? 'display:none;' : '' }}">
                            <label class="block text-xs font-semibold text-gray-600 mb-1" id="edit_pswd_label">{{ ($customer->cos_pswd_type ?? 'text') == 'pin' ? 'Password PIN' : 'Password Text' }}</label>
                            <input type="text" name="pswd" id="edit_pswd_input" value="{{ in_array($customer->cos_pswd_type ?? 'text', ['text', 'pin']) ? ($customer->cos_pswd ?? '') : '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="{{ ($customer->cos_pswd_type ?? 'text') == 'pin' ? 'Masukan PIN angka (misal: 1234 / 123456)' : 'Masukan password text' }}">
                        </div>
                        <!-- Bagian Edit Pola: Gambar Pola di Kiri & Keterangan Pola di Kanan -->
                        <div class="col-span-12" id="edit_pswd_desc_div" style="{{ ($customer->cos_pswd_type ?? 'text') == 'pattern_desc' ? '' : 'display:none;' }}">
                            <div class="grid grid-cols-12 gap-4 p-4 border rounded-lg bg-gray-50 dark:bg-dark-1">
                                <!-- Kolom Kiri: Gambar Pola Interaktif -->
                                <div class="col-span-12 sm:col-span-6 flex flex-col items-center justify-center p-4 bg-[#111317] rounded-xl border border-gray-800 shadow-lg">
                                    <div class="flex items-center justify-between w-full mb-3 px-1">
                                        <span class="font-semibold text-xs text-gray-200 flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                                            Gambar Pola (9 Titik)
                                        </span>
                                        <button type="button" id="btnClearEditPattern" class="text-xs text-red-400 hover:text-red-300 font-semibold flex items-center gap-1 bg-red-500/15 hover:bg-red-500/25 px-2.5 py-1 rounded-md transition-all">
                                            <svg class="w-3.5 h-3.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Reset Pola
                                        </button>
                                    </div>
                                    <div class="relative flex justify-center">
                                        <canvas id="editPatternCanvas" width="220" height="220" style="touch-action: none; background: #181b1f; cursor: crosshair; border-radius: 14px;" class="border border-gray-700/80 shadow-inner"></canvas>
                                    </div>
                                    <p class="text-[11px] text-gray-400 mt-3 text-center">Tarik garis menghubungkan titik-titik untuk membentuk pola</p>
                                    <input type="hidden" name="pswd_canvas" id="edit_pswd_canvas" value="{{ $customer->cos_pswd_canvas ?? '' }}">
                                </div>

                                <!-- Kolom Kanan: Keterangan Pola -->
                                <div class="col-span-12 sm:col-span-6 flex flex-col justify-between">
                                    <div>
                                        <label class="block font-semibold text-xs text-gray-700 mb-1">Keterangan Pola</label>
                                        <p class="text-[11px] text-gray-500 mb-2">Tuliskan deskripsi pola (contoh: huruf L, segitiga, atau urutan nomor)</p>
                                        <textarea name="pswd_desc" id="edit_pswd_desc" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="5" placeholder="Misal: Bentuk huruf L dari kiri atas turun ke bawah lalu ke kanan">{{ $customer->cos_pswd_type == 'pattern_desc' ? ($customer->cos_pswd ?? '') : '' }}</textarea>
                                    </div>
                                    <div class="mt-2 text-[11px] text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-2.5">
                                        <div class="font-semibold text-blue-800 mb-0.5">ℹ️ Petunjuk:</div>
                                        Saat menggambar pola pada canvas di sebelah kiri, gambar pola otomatis terekam dan urutan nomor titik akan otomatis terisi pada keterangan ini.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Asesoris</label>
                            <textarea name="asesoris" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="2">{{ $customer->cos_asesoris ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="switchEditTab('custom')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-semibold transition-colors">&larr; Kembali</button>
                    <button type="button" onclick="switchEditTab('kelket')" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold shadow-md transition-colors">Lanjut ke Keluhan & Keterangan &rarr;</button>
                </div>
            </div>

            <!-- Step 3: Keluhan & Keterangan -->
            <div class="edit-tab-pane hidden" id="edit_pane_kelket">
                <div class="pt-4 border-t border-gray-200">
                    <div class="font-bold text-base text-gray-800 mb-4">3. Keluhan dan Keterangan</div>
                    <div class="grid grid-cols-12 gap-4">
                        <div class="col-span-12">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Keluhan <span class="text-red-500">*</span></label>
                            <textarea name="keluhan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-red-600 font-semibold focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="3" required>{{ $customer->cos_keluhan ?? '' }}</textarea>
                        </div>
                        <div class="col-span-12">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Keterangan Tambahan</label>
                            <textarea name="ket" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" rows="2">{{ $customer->cos_keterangan ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="switchEditTab('unit')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-semibold transition-colors">&larr; Kembali</button>
                    <div class="flex gap-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-semibold transition-colors">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-theme-1 text-white rounded-lg text-xs font-bold shadow-md transition-colors">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview Gambar Pola -->
<div id="patternPreviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-xs hidden">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl border border-gray-100 animate-in fade-in zoom-in duration-150">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
            <h4 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                <i data-feather="lock" class="w-4 h-4 text-blue-600"></i> Pola Kunci Customer
            </h4>
            <button type="button" onclick="closePatternPreview()" class="text-gray-400 hover:text-gray-600 p-1 rounded-lg">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
        <div class="flex flex-col items-center justify-center p-3 bg-slate-50 rounded-xl border border-dashed border-gray-200 mb-4">
            <img id="patternPreviewImg" src="" alt="Pola Kunci" class="w-[200px] h-[200px] object-contain rounded-lg shadow-sm">
        </div>
        <div class="bg-blue-50 rounded-xl p-3 border border-blue-100 text-xs text-blue-900 mb-4">
            <span class="font-semibold text-blue-800 block mb-0.5">Keterangan:</span>
            <span id="patternPreviewDesc" class="font-mono text-gray-800 font-medium"></span>
        </div>
        <button type="button" onclick="closePatternPreview()" class="w-full py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-xs transition-colors">
            Tutup
        </button>
    </div>
</div>

<script>
    class PatternLock {
        constructor(canvasId, hiddenInputId, descInputId = null, clearBtnId = null) {
            this.canvas = document.getElementById(canvasId);
            if (!this.canvas) return;
            this.ctx = this.canvas.getContext('2d');
            this.hiddenInput = document.getElementById(hiddenInputId);
            this.descInput = descInputId ? document.getElementById(descInputId) : null;
            this.clearBtn = clearBtnId ? document.getElementById(clearBtnId) : null;
            
            this.rows = 3;
            this.cols = 3;
            this.dots = [];
            this.selectedDots = [];
            this.isDrawing = false;
            this.currentPos = null;
            
            this.init();
        }
        
        init() {
            this.calculateDots();
            this.draw();
            this.bindEvents();
            
            if (this.clearBtn) {
                this.clearBtn.onclick = (e) => {
                    e.preventDefault();
                    this.reset();
                };
            }
        }
        
        calculateDots() {
            this.dots = [];
            const width = this.canvas.width;
            const height = this.canvas.height;
            const xStep = width / (this.cols + 1);
            const yStep = height / (this.rows + 1);
            
            let id = 1;
            for (let r = 0; r < this.rows; r++) {
                for (let c = 0; c < this.cols; c++) {
                    this.dots.push({
                        id: id++,
                        x: (c + 1) * xStep,
                        y: (r + 1) * yStep,
                        radius: 8
                    });
                }
            }
        }
        
        draw() {
            const ctx = this.ctx;
            // Dark sleek background matching user screenshot
            ctx.fillStyle = '#181b1f';
            ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
            
            // Draw connecting lines
            if (this.selectedDots.length > 0) {
                ctx.beginPath();
                ctx.strokeStyle = '#38bdf8'; // Glowing neon cyan
                ctx.lineWidth = 4.5;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                
                ctx.moveTo(this.selectedDots[0].x, this.selectedDots[0].y);
                for (let i = 1; i < this.selectedDots.length; i++) {
                    ctx.lineTo(this.selectedDots[i].x, this.selectedDots[i].y);
                }
                
                if (this.isDrawing && this.currentPos) {
                    ctx.lineTo(this.currentPos.x, this.currentPos.y);
                }
                ctx.stroke();
            }
            
            // Draw 9 dots (3x3)
            this.dots.forEach(dot => {
                const isSelected = this.selectedDots.some(d => d.id === dot.id);
                
                if (isSelected) {
                    // Glowing cyan ring
                    ctx.beginPath();
                    ctx.arc(dot.x, dot.y, 22, 0, Math.PI * 2);
                    ctx.fillStyle = 'rgba(56, 189, 248, 0.25)';
                    ctx.fill();
                    
                    ctx.beginPath();
                    ctx.arc(dot.x, dot.y, 22, 0, Math.PI * 2);
                    ctx.strokeStyle = '#38bdf8';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                    
                    // Order number indicator
                    const order = this.selectedDots.findIndex(d => d.id === dot.id) + 1;
                    ctx.font = 'bold 11px sans-serif';
                    ctx.fillStyle = '#bae6fd';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(order, dot.x + 15, dot.y - 15);
                }
                
                // Dot center (white dot like screenshot 2)
                ctx.beginPath();
                ctx.arc(dot.x, dot.y, isSelected ? 8 : 6.5, 0, Math.PI * 2);
                ctx.fillStyle = isSelected ? '#38bdf8' : '#ffffff';
                ctx.fill();
            });
        }
        
        getCanvasPoint(e) {
            const rect = this.canvas.getBoundingClientRect();
            const clientX = (e.touches && e.touches.length > 0) ? e.touches[0].clientX : e.clientX;
            const clientY = (e.touches && e.touches.length > 0) ? e.touches[0].clientY : e.clientY;
            const scaleX = this.canvas.width / rect.width;
            const scaleY = this.canvas.height / rect.height;
            return {
                x: (clientX - rect.left) * scaleX,
                y: (clientY - rect.top) * scaleY
            };
        }
        
        checkDot(pos) {
            const hitRadius = 24;
            for (const dot of this.dots) {
                const dist = Math.hypot(dot.x - pos.x, dot.y - pos.y);
                if (dist <= hitRadius) {
                    if (!this.selectedDots.some(d => d.id === dot.id)) {
                        this.selectedDots.push(dot);
                        return true;
                    }
                }
            }
            return false;
        }
        
        bindEvents() {
            const start = (e) => {
                e.preventDefault();
                this.isDrawing = true;
                this.selectedDots = [];
                const pos = this.getCanvasPoint(e);
                this.currentPos = pos;
                this.checkDot(pos);
                this.draw();
            };
            
            const move = (e) => {
                if (!this.isDrawing) return;
                e.preventDefault();
                const pos = this.getCanvasPoint(e);
                this.currentPos = pos;
                this.checkDot(pos);
                this.draw();
            };
            
            const end = (e) => {
                if (!this.isDrawing) return;
                this.isDrawing = false;
                this.currentPos = null;
                this.draw();
                this.save();
            };
            
            this.canvas.addEventListener('mousedown', start);
            window.addEventListener('mousemove', move);
            window.addEventListener('mouseup', end);
            
            this.canvas.addEventListener('touchstart', start, { passive: false });
            window.addEventListener('touchmove', move, { passive: false });
            window.addEventListener('touchend', end);
        }
        
        save() {
            if (this.selectedDots.length > 0) {
                const dataUrl = this.canvas.toDataURL('image/png');
                if (this.hiddenInput) {
                    this.hiddenInput.value = dataUrl;
                }
                if (this.descInput && (!this.descInput.value.trim() || this.descInput.value.startsWith('Pola: '))) {
                    const sequence = this.selectedDots.map(d => d.id).join(' -> ');
                    this.descInput.value = 'Pola: ' + sequence;
                }
            }
        }
        
        reset() {
            this.selectedDots = [];
            this.isDrawing = false;
            this.currentPos = null;
            if (this.hiddenInput) this.hiddenInput.value = '';
            if (this.descInput && this.descInput.value.startsWith('Pola: ')) {
                this.descInput.value = '';
            }
            this.draw();
        }

        loadExisting(dataUrl) {
            if (!dataUrl || !dataUrl.startsWith('data:image')) return;
            const img = new Image();
            img.onload = () => {
                this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                this.ctx.drawImage(img, 0, 0, this.canvas.width, this.canvas.height);
            };
            img.src = dataUrl;
        }
    }

    let editPatternLock = null;

    function initEditPatternLock() {
        if (!editPatternLock) {
            editPatternLock = new PatternLock('editPatternCanvas', 'edit_pswd_canvas', 'edit_pswd_desc', 'btnClearEditPattern');
            const existingCanvas = document.getElementById('edit_pswd_canvas')?.value;
            if (existingCanvas) {
                editPatternLock.loadExisting(existingCanvas);
            }
        } else {
            editPatternLock.draw();
        }
    }

    function showPatternPreview(dataUrl, desc) {
        document.getElementById('patternPreviewImg').src = dataUrl;
        document.getElementById('patternPreviewDesc').textContent = desc || '-';
        document.getElementById('patternPreviewModal').classList.remove('hidden');
        if (typeof feather !== 'undefined') feather.replace();
    }

    function closePatternPreview() {
        document.getElementById('patternPreviewModal').classList.add('hidden');
    }

    function openEditModal() {
        document.getElementById('editCustomerModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        switchEditTab('custom');
    }
    function closeEditModal() {
        document.getElementById('editCustomerModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    function switchEditTab(tabName) {
        document.querySelectorAll('.edit-tab-pane').forEach(el => {
            el.classList.remove('block');
            el.classList.add('hidden');
        });
        document.querySelectorAll('.edit-wizard-step').forEach(el => {
            el.classList.remove('text-white', 'bg-theme-1', 'active');
            el.classList.add('text-gray-600', 'bg-gray-200');
        });

        const targetPane = document.getElementById('edit_pane_' + tabName);
        if (targetPane) {
            targetPane.classList.remove('hidden');
            targetPane.classList.add('block');
        }

        const targetBtn = document.getElementById('edit_step_' + tabName);
        if (targetBtn) {
            targetBtn.classList.remove('text-gray-600', 'bg-gray-200');
            targetBtn.classList.add('text-white', 'bg-theme-1', 'active');
        }

        if (tabName === 'unit') {
            const pswdType = document.querySelector('input[name="pswd_type"]:checked')?.value;
            if (pswdType === 'pattern_desc') {
                setTimeout(initEditPatternLock, 50);
            }
        }
    }
    function toggleEditPswd(type) {
        const textDiv = document.getElementById('edit_pswd_text_div');
        const descDiv = document.getElementById('edit_pswd_desc_div');
        const labelElem = document.getElementById('edit_pswd_label');
        const inputElem = document.getElementById('edit_pswd_input');

        if (type === 'text') {
            textDiv.style.display = 'block';
            descDiv.style.display = 'none';
            if (labelElem) labelElem.innerText = 'Password Text';
            if (inputElem) {
                inputElem.placeholder = 'Masukan password text';
                inputElem.type = 'text';
                inputElem.removeAttribute('inputmode');
            }
        } else if (type === 'pin') {
            textDiv.style.display = 'block';
            descDiv.style.display = 'none';
            if (labelElem) labelElem.innerText = 'Password PIN';
            if (inputElem) {
                inputElem.placeholder = 'Masukan PIN angka (misal: 1234 / 123456)';
                inputElem.type = 'text';
                inputElem.setAttribute('inputmode', 'numeric');
            }
        } else {
            textDiv.style.display = 'none';
            descDiv.style.display = 'block';
            setTimeout(initEditPatternLock, 50);
        }
    }
</script>
@endsection

