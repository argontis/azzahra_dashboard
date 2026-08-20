@extends('layouts.app')

@section('content')
<!-- Header Area -->
<div class="page-header" style="position: fixed; top: 0 !important; left: 260px !important; width: calc(100% - 260px) !important; margin: 0 !important; border-radius: 0 !important; z-index: 9999;">
    <div class="flex items-center gap-2 text-white">
        <i data-feather="zap" class="w-5 h-5"></i>
        <h1 class="text-xl font-bold tracking-wide">Quick Service</h1>
        <span class="hidden md:inline-block text-white text-xs opacity-90 ml-4 border-l border-white/20 pl-4">Manage quick service data</span>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="relative hidden sm:block">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search..." class="pl-9 pr-4 py-2 rounded-md border-0 focus:ring-2 focus:ring-blue-400 text-gray-700 text-sm w-48 lg:w-80 shadow-sm">
        </div>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm relative">
            <i data-feather="bell" class="w-4 h-4"></i>
            <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
        </button>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm relative">
            <i data-feather="mail" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<div class="content-area">
    <div class="sukses" data-sukses="{{ session('sukses') }}"></div>
    <div class="gagal" data-gagal="{{ session('gagal') }}"></div>
    
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8 mb-4">
        <h2 class="text-xl font-semibold text-gray-700 mr-auto">
            Data {{ ucfirst($current_status) }} Quick Service
        </h2>
        <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
            <a role="button" class="btn text-white bg-blue-700 hover:bg-blue-800 shadow-md px-4 py-2 rounded-md font-medium" data-toggle="modal" data-target="#add-new-costom">
                Buat Transaksi
            </a>
        </div>
    </div>
    
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 lg:col-span-3 xxl:col-span-2">
            <div class="box p-3 mt-6 shadow-sm rounded-lg bg-white border border-gray-100">
                <div class="flex flex-col gap-1">
                    <a href="#" class="flex items-center px-4 py-3 rounded-md transition-colors bg-blue-800 text-white font-medium shadow-md"> 
                        <i class="w-4 h-4 mr-3" data-feather="user-plus"></i> Transaksi baru 
                    </a>
                    <a href="#" class="flex items-center px-4 py-3 rounded-md transition-colors text-gray-600 hover:bg-gray-50">
                        <i class="w-4 h-4 mr-3" data-feather="credit-card"></i> Costomer
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10">
            <div class="intro-y datatable-wrapper box p-5 mt-6 bg-white shadow-sm rounded-lg border border-gray-100">
                <table class="table table-report table-report--bordered display datatable w-full">
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
                            <tr>
                                <td class="text-center border-b">{{ $transaksis->firstItem() + $index }}</td>
                                <td class="text-center border-b">{{ $row->trans_kode }}</td>
                                <td class="border-b">{{ $row->customer->cos_nama ?? '-' }}</td>
                                <td class="border-b">{{ $row->customer->cos_alamat ?? '-' }}</td>
                                <td class="text-center border-b">
                                    @php
                                        $hp = $row->customer->cos_hp ?? '';
                                        $masked_hp = strlen($hp) > 4 ? substr($hp, 0, -4) . 'XXXX' : $hp;
                                    @endphp
                                    {{ $masked_hp }}
                                </td>
                                <td class="text-center border-b">
                                    <div class="flex sm:justify-center items-center">
                                        <a href="#" class="button px-2 mr-1 mb-2 bg-theme-1 text-white tooltip" title="Print TTS">
                                            <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="printer" class="w-4 h-4"></i> </span>
                                        </a>
                                        <a role="button" onclick="sendToWA('{{ url('/') }}', '{{ $row->customer->cos_hp ?? '' }}', '{{ $row->customer->cos_nama ?? '' }}', '{{ $row->cos_kode }}', '{{ $row->trans_kode }}')" class="button px-2 mr-1 mb-2 bg-green-500 text-white tooltip" title="Kirim WA">
                                            <span class="w-5 h-5 flex items-center justify-center"> <i data-feather="message-circle" class="w-4 h-4"></i> </span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $transaksis->links() }}
                </div>
            </div>
        </div>
    </div>    
</div>

@if($current_status == 'baru' || $current_status == 'proses')
<!-- modal tambah customer QS -->
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
        <form id="transForm" method="post" action="{{ route('quickservice.save_trans') }}" onsubmit="return submitTransForm(this);">
            @csrf
            <div class="tab-content">
                <div class="tab-content__pane active" id="custom">
                    <div class="px-5 sm:px-20 mt-10 pt-10 border-t border-gray-200">
                        <div class="font-medium text-base">Data Customer QS</div>
                        <div class="grid grid-cols-12 gap-4 row-gap-5 mt-5">
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Nama</div>
                                <input type="text" class="input w-full border flex-1" name="nama" required placeholder="Masukan nama customer">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">No Telepon</div>
                                <input type="number" class="input w-full border flex-1" name="tlp" required placeholder="Masukan no tlep customer">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Alamat</div>
                                <textarea class="input w-full border mt-2 flex-1" name="alamat" required></textarea>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Cabang</div>
                                <select class="input w-full border mt-2 flex-1" name="cabang" required>
                                    <option value="Tegal" selected>Tegal</option>
                                    <option value="Cibubur">Cibubur</option>
                                </select>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Tanggal Lahir</div>
                                <input type="date" class="input w-full border flex-1" name="cos_tgl_lahir" required>
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
                                <select class="input w-full border flex-1" name="status" required>
                                     <option value="">-</option>
                                     <option value="CID">CID</option>
                                     <option value="IW">IW</option>
                                     <option value="OOW">OOW</option>
                                 </select>
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Device</div>
                                <input type="text" class="input w-full border flex-1" name="device" required placeholder="Masukan device">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Merk / Type</div>
                                <input type="text" class="input w-full border flex-1" name="type" required placeholder="Masukan type unit">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">Model</div>
                                <input type="text" class="input w-full border flex-1" name="model" required placeholder="Masukan model unit">
                            </div>
                            <div class="intro-y col-span-12 sm:col-span-6">
                                <div class="mb-2">No Seri</div>
                                <input type="text" class="input w-full border flex-1" name="seri" required placeholder="Masukan no seri">
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
                                <div class="mb-2">Keluhan</div>
                                <textarea class="input w-full border mt-2 flex-1" name="keluhan" required></textarea>
                            </div>
                            <div class="intro-y col-span-12">
                                <div class="mb-2">Keterangan Tambahan</div>
                                <textarea class="input w-full border mt-2 flex-1" name="ket" required></textarea>
                            </div>
                            <!-- Jika Quick Service, bisa tambahkan checkbox is_quick_service di sini -->
                            <input type="hidden" name="is_quick_service" value="1">
                        </div>
                    </div>
                    <div class="px-5 py-3 text-right border-t border-gray-200">
                        <button type="button" data-dismiss="modal" class="button w-20 border text-gray-700 mr-1">Cancel</button>
                        <button type="submit" class="button w-20 bg-theme-1 text-white">Simpan</button>
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

    function submitTransForm(form) {
        var formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                $('#add-new-costom').modal('hide');
                alert('DATA BERHASIL DI TAMBAHKAN');
                window.location.reload();
            } else {
                alert('Terjadi kesalahan pada respon server');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan');
        });
        return false;
    }

    function sendToWA(url, hp, nama, kode, trans_kode) {
        if (hp.startsWith('0')) {
            hp = '62' + hp.substring(1);
        }
        hp = hp.replace(/\D/g, '');
        let message = `SALAM SATU HATI,\n\nHALO ${nama},\n\nTerima Kasih Telah Percaya kepada Kami. Untuk Mengecek Transaksi QS Anda Silahkan Login menggunakan username: ${kode}, password: ${kode}.`;
        const waUrl = `https://wa.me/${hp}?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
    }
</script>
@endif
@endsection
