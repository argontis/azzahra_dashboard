@extends('layouts.app')

@section('content')
<!-- Header Area -->
<div class="page-header">
    <div class="flex items-center gap-2 text-white">
        <i data-feather="users" class="w-5 h-5"></i>
        <h1 class="text-xl font-bold tracking-wide">Customer</h1>
        <span class="hidden md:inline-block text-white text-xs opacity-90 ml-4 border-l border-white/20 pl-4">Antrean Transaksi Service Customer</span>
    </div>
</div>

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
            <div class="intro-y box p-5">
                <div class="mt-1">
                    <a href="{{ route('service.antrean', 'baru') }}" class="flex items-center px-3 py-2 rounded-md {{ $current_status == 'baru' ? 'bg-theme-1 text-white font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi baru
                    </a>
                    <a href="{{ route('service.antrean', 'proses') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'proses' || $current_status == 'diproses' ? 'bg-theme-1 text-white font-medium' : 'text-gray-700 hover:bg-gray-100' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi diproses 
                    </a>
                    <a href="{{ route('service.antrean', 'konfirmasi') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'konfirmasi' ? 'bg-theme-1 text-white font-medium' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfirmasi
                    </a>
                    <a href="{{ route('service.antrean', 'pelunasan') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'pelunasan' ? 'bg-theme-1 text-white font-medium' : 'text-gray-700 hover:bg-gray-100' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Pelunasan 
                    </a>
                    <a href="{{ route('service.antrean', 'lunas') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'lunas' ? 'bg-theme-1 text-white font-medium' : 'text-gray-700 hover:bg-gray-100' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="users"></i> Customer
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10 space-y-5">
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
                            <h3 class="text-lg font-bold text-gray-800">{{ $customer->cos_nama ?? 'N/A' }}</h3>
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
                                    <input type="radio" name="pswd_type" value="pattern_desc" {{ ($customer->cos_pswd_type ?? '') == 'pattern_desc' ? 'checked' : '' }} class="mr-1.5" onchange="toggleEditPswd('desc')"> Pola
                                </label>
                            </div>
                        </div>
                        <div class="col-span-12 sm:col-span-6" id="edit_pswd_text_div" style="{{ ($customer->cos_pswd_type ?? 'text') == 'pattern_desc' ? 'display:none;' : '' }}">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Password Text</label>
                            <input type="text" name="pswd" value="{{ $customer->cos_pswd_type == 'text' ? ($customer->cos_pswd ?? '') : '' }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Masukan password text">
                        </div>
                        <div class="col-span-12 sm:col-span-6" id="edit_pswd_desc_div" style="{{ ($customer->cos_pswd_type ?? 'text') == 'pattern_desc' ? '' : 'display:none;' }}">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi Pola Password</label>
                            <textarea name="pswd_desc" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Misal: L ke kanan bawah">{{ $customer->cos_pswd_type == 'pattern_desc' ? ($customer->cos_pswd ?? '') : '' }}</textarea>
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

<script>
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
    }
    function toggleEditPswd(type) {
        if (type === 'text') {
            document.getElementById('edit_pswd_text_div').style.display = 'block';
            document.getElementById('edit_pswd_desc_div').style.display = 'none';
        } else {
            document.getElementById('edit_pswd_text_div').style.display = 'none';
            document.getElementById('edit_pswd_desc_div').style.display = 'block';
        }
    }
</script>
@endsection
