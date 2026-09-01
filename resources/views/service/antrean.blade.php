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
        <div class="header-btn" title="Notifikasi">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn" id="topbar-mail-btn" title="Kotak Pesan">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">
    <div class="sukses" data-sukses="{{ session('sukses') }}"></div>
    <div class="gagal" data-gagal="{{ session('gagal') }}"></div>
    
    <div class="intro-y flex flex-col sm:flex-row items-center mt-2 mb-3">
        <h2 class="text-lg font-medium mr-auto">
            Antrean Transaksi: {{ ucfirst($current_status) }}
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            @if($current_status == 'baru')
            <a role="button" class="button text-white bg-theme-1 shadow-md mr-2" data-toggle="modal" data-target="#add-new-costom">
                Buat Transaksi Baru
            </a>
            @endif
        </div>
    </div>
    
    <div class="intro-y chat grid grid-cols-12 gap-5 mt-3">
        <div class="col-span-12 lg:col-span-3 xxl:col-span-2">
            <div class="intro-y box p-5">
                <div class="mt-1">
                    <a href="{{ route('service.antrean', 'baru') }}" class="flex items-center px-3 py-2 rounded-md {{ $current_status == 'baru' ? 'bg-theme-1 text-white font-medium' : '' }}">
                        <i class="w-4 h-4 mr-2" data-feather="user-plus"></i> Transaksi Baru
                    </a>
                    <a href="{{ route('service.antrean', 'proses') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'proses' ? 'bg-theme-1 text-white font-medium' : '' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="user-check"></i> Transaksi Diproses 
                    </a>
                    <a href="{{ route('service.antrean', 'konfirmasi') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'konfirmasi' ? 'bg-theme-1 text-white font-medium' : '' }}">
                        <i class="w-4 h-4 mr-2" data-feather="phone-outgoing"></i> Konfirmasi
                    </a>
                    <a href="{{ route('service.antrean', 'pelunasan') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'pelunasan' ? 'bg-theme-1 text-white font-medium' : '' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="credit-card"></i> Pelunasan 
                    </a>
                    <a href="{{ route('service.antrean', 'lunas') }}" class="flex items-center px-3 py-2 mt-2 rounded-md {{ $current_status == 'lunas' ? 'bg-theme-1 text-white font-medium' : '' }}"> 
                        <i class="w-4 h-4 mr-2" data-feather="users"></i> Selesai (Lunas)
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10">
            <div class="intro-y datatable-wrapper box p-5">
                <div class="overflow-x-auto">
                    <table class="table table-report table-report--bordered w-full">
                        <thead>
                            <tr>
                                <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                                <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                                <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaksis as $index => $row)
                                @php
                                    $customer = $row->customer;
                                    $isPriority = $customer && (($customer->cos_tier === 'prioritas') || ($customer->cos_score >= 5) || ($customer->total_transaksi >= 5));
                                    $isLoyal = $customer && (($customer->cos_tier === 'loyal') || ($customer->cos_score >= 3 && !$isPriority));
                                    $hp = $customer->cos_hp ?? '';
                                    $masked_hp = strlen($hp) > 4 ? substr($hp, 0, -4) . 'XXXX' : $hp;
                                @endphp
                                <tr class="{{ $isPriority ? 'bg-amber-50/50 hover:bg-amber-50/80 border-l-4 border-l-amber-500' : '' }}">
                                    <td class="text-center border-b whitespace-no-wrap">{{ $transaksis->firstItem() + $index }}</td>
                                    <td class="text-center border-b whitespace-no-wrap font-medium">{{ $row->trans_kode }}</td>
                                    <td class="border-b whitespace-no-wrap">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-medium text-gray-800">{{ $customer->cos_nama ?? '-' }}</span>
                                            @if($isPriority)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-xs" title="Pelanggan Prioritas (VIP)">
                                                    👑 PRIORITAS
                                                </span>
                                            @elseif($isLoyal)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-700 border border-blue-200">
                                                    ⭐ Loyal
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="border-b whitespace-no-wrap text-gray-600">{{ $customer->cos_alamat ?? '-' }}</td>
                                    <td class="text-center border-b whitespace-no-wrap font-medium text-gray-700">
                                        {{ $masked_hp }}
                                    </td>
                                    <td class="text-center border-b whitespace-no-wrap">
                                        <div class="flex sm:justify-center items-center">
                                            <a href="{{ route('service.proses', $row->trans_kode) }}" class="button px-3 py-1.5 mr-1 mb-2 bg-theme-9 text-white tooltip flex items-center justify-center font-medium shadow-sm" title="Proses">
                                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Proses
                                            </a>
                                            @if($current_status == 'konfirmasi')
                                                <a href="{{ route('service.batal_transaksi', $row->trans_kode) }}" class="button px-2 mr-1 mb-2 bg-theme-6 text-white" onclick="return confirm('Yakin ingin membatalkan transaksi ini?')">
                                                    Batal
                                                </a>
                                            @elseif($current_status == 'pelunasan')
                                                <a href="{{ route('service.return_pembayaran', $row->trans_kode) }}" class="button px-2 mr-1 mb-2 bg-theme-6 text-white" onclick="return confirm('Yakin ingin return pembayaran ini?')">
                                                    Return
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.cetak.print_1', $row->trans_kode) }}" target="_blank" class="button px-2 mr-1 mb-2 bg-theme-6 text-white tooltip" title="Print TTS (PDF)">
                                                <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="printer" class="w-4 h-4"></i> </span>
                                            </a>
                                            <a role="button" onclick="sendToWA('{{ url('/') }}', '{{ $row->customer->cos_hp ?? '' }}', '{{ $row->customer->cos_nama ?? '' }}', '{{ $row->cos_kode }}', '{{ $row->trans_kode }}')" class="button px-2 mr-1 mb-2 bg-theme-9 text-white tooltip" title="Kirim WA">
                                                <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="message-circle" class="w-4 h-4"></i> </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $transaksis->links() }}
                </div>
            </div>
        </div>
    </div>    
</div>

@if($current_status == 'baru')
<!-- modal tambah customer -->
<div class="modal flex items-center justify-center" id="add-new-costom" style="z-index: 1050;">
    <div class="modal__content modal__content--xl p-10 intro-y box sm:py-15" style="max-height: 80vh; overflow-y: auto;">
        <div class="nav-tabs wizard flex flex-col lg:flex-row justify-center px-5 sm:px-20">
            <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10 ">
                <a href="#" class="w-10 h-10 rounded-full button text-white bg-theme-1 active" data-toggle="tab" data-target="#custom">1</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Customer</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <a href="#" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200" data-toggle="tab" data-target="#unit">2</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Data Unit</div>
            </div>
            <div class="intro-x lg:text-center flex items-center mt-5 lg:mt-0 lg:block flex-1 z-10">
                <a href="#" class="w-10 h-10 rounded-full button text-gray-600 bg-gray-200" data-toggle="tab" data-target="#kelket">3</a>
                <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto">Keluhan & Keterangan</div>
            </div>
            <div class="wizard__line hidden lg:block w-2/3 bg-gray-200 absolute mt-2"></div>
        </div>
        <form id="transForm" novalidate method="post" action="{{ route('service.save_trans') }}" onsubmit="return submitTransForm(this);">
            @csrf
            <div class="tab-content">
                <div class="tab-content__pane active" id="custom">
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
                        <div class="font-medium text-base">Data Customer</div>
                        <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Nama <span class="text-red-500">*</span></div>
                                <input type="text" class="input w-full border flex-1" name="nama" required placeholder="Masukan nama customer">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">No Telepon <span class="text-red-500">*</span></div>
                                <input type="number" class="input w-full border flex-1" name="tlp" required placeholder="Masukan no tlep customer">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Alamat</div>
                                <textarea class="input w-full border mt-2 flex-1" name="alamat"></textarea>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Cabang</div>
                                <select class="input w-full border mt-2 flex-1" name="cabang">
                                    <option value="Tegal" selected>Tegal</option>
                                    <option value="Cibubur">Cibubur</option>
                                </select>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Tanggal Lahir</div>
                                <input type="date" class="input w-full border flex-1" name="cos_tgl_lahir">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="unit">
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
                        <div class="font-medium text-base">Data Unit</div>
                        <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Status Garansi</div>
                                <select class="input w-full border flex-1" name="status">
                                     <option value="">-</option>
                                     <option value="CID">CID</option>
                                     <option value="IW">IW</option>
                                     <option value="OOW">OOW</option>
                                 </select>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Device</div>
                                <input type="text" class="input w-full border flex-1" name="device" placeholder="Masukan device">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Merk / Type <span class="text-red-500">*</span></div>
                                <input type="text" class="input w-full border flex-1" name="type" required placeholder="Masukan type unit">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Model</div>
                                <input type="text" class="input w-full border flex-1" name="model" placeholder="Masukan model unit">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">No Seri</div>
                                <input type="text" class="input w-full border flex-1" name="seri" placeholder="Masukan no seri">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Tipe Password</div>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="pswd_type" value="text" checked class="mr-2" onchange="togglePswd('text')"> Text
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="pswd_type" value="pattern_desc" class="mr-2" onchange="togglePswd('desc')"> Pola
                                    </label>
                                </div>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6 pswd_text" id="pswd_text_div">
                                <div class="mb-2">Password Text</div>
                                <input type="text" class="input w-full border flex-1" name="pswd" placeholder="Masukan password text">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6 pswd_pattern_desc" id="pswd_desc_div" style="display: none;">
                                <div class="mb-2">Deskripsi Pola Password</div>
                                <textarea class="input w-full border flex-1" name="pswd_desc" placeholder="Misal: L ke kanan bawah"></textarea>
                            </div>
                            <div class="intro-y col-span-12">
                                <div class="mb-2">Asesoris</div>
                                <textarea class="input w-full border mt-2 flex-1" name="asesoris"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content__pane" id="kelket">
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
                        <div class="font-medium text-base">Keluhan dan Keterangan</div>
                        <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                            <div class="intro-y col-span-12">
                                <div class="mb-2">Keluhan <span class="text-red-500">*</span></div>
                                <textarea class="input w-full border mt-2 flex-1" name="keluhan" required></textarea>
                            </div>
                            <div class="intro-y col-span-12">
                                <div class="mb-2">Keterangan Tambahan</div>
                                <textarea class="input w-full border mt-2 flex-1" name="ket"></textarea>
                            </div>
                            <!-- Jika Quick Service, bisa tambahkan checkbox is_quick_service di sini -->
                            @if(Route::is('quickservice.*'))
                            <input type="hidden" name="is_quick_service" value="1">
                            @endif
                        </div>
                    </div>
                    <div class="px-5 py-3 text-right border-t border-gray-200">
                        <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
                        <button type="submit" id="btnSimpanTrans" class="button w-20 bg-theme-1 text-white">Simpan</button>
                    </div>
                </div>
            </div>
        </form>            
    </div>
</div>

<script>
    function togglePswd(type) {
        if (type === 'text') {
            document.getElementById('pswd_text_div').style.display = 'block';
            document.getElementById('pswd_desc_div').style.display = 'none';
        } else {
            document.getElementById('pswd_text_div').style.display = 'none';
            document.getElementById('pswd_desc_div').style.display = 'block';
        }
    }

    function activateTab(tabId) {
        var $tabLink = $('.wizard a[data-target="' + tabId + '"]');
        if ($tabLink.length) {
            $tabLink.trigger('click');
        }
    }

    function submitTransForm(form) {
        // Validate Step 1: Data Customer
        const nama = form.querySelector('[name="nama"]');
        const tlp = form.querySelector('[name="tlp"]');
        if (!nama || !nama.value.trim()) {
            activateTab('#custom');
            nama.focus();
            alert('Mohon isi Nama Customer di Step 1 (Data Customer)');
            return false;
        }
        if (!tlp || !tlp.value.trim()) {
            activateTab('#custom');
            tlp.focus();
            alert('Mohon isi No Telepon Customer di Step 1 (Data Customer)');
            return false;
        }

        // Validate Step 2: Data Unit
        const type = form.querySelector('[name="type"]');
        if (!type || !type.value.trim()) {
            activateTab('#unit');
            type.focus();
            alert('Mohon isi Merk / Type Unit di Step 2 (Data Unit)');
            return false;
        }

        // Validate Step 3: Keluhan
        const keluhan = form.querySelector('[name="keluhan"]');
        if (!keluhan || !keluhan.value.trim()) {
            activateTab('#kelket');
            keluhan.focus();
            alert('Mohon isi Keluhan di Step 3 (Keluhan & Keterangan)');
            return false;
        }

        var btn = form.querySelector('#btnSimpanTrans');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
        }

        var formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok || data.status !== 'success') {
                throw new Error(data.message || 'Terjadi kesalahan pada respon server');
            }
            return data;
        })
        .then(data => {
            $('#add-new-costom').modal('hide');
            alert('DATA BERHASIL DI TAMBAHKAN');
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Terjadi kesalahan jaringan');
            if (btn) {
                btn.disabled = false;
                btn.innerText = 'Simpan';
            }
        });
        return false;
    }

    function sendToWA(url, hp, nama, kode, trans_kode) {
        if (hp.startsWith('0')) {
            hp = '62' + hp.substring(1);
        }
        hp = hp.replace(/\D/g, '');
        let message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami. Untuk Mengecek Transaksi Anda Silahkan Login menggunakan username: ${kode}, password: ${kode}.`;
        const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
    }
</script>
@endif
@endsection
