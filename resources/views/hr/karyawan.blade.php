@extends('layouts.app')
@section('content')
  <!-- Header -->
        <header class="page-header">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="users" class="w-5 h-5 inline-block mr-2"></i>Karyawan</h1>                
                <p>Welcome back, here's your business overview</p>
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
	<div class="sukses" data-sukses="{{ session('sukses') }}"></div>

    @if(session('error'))
    <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded-lg mt-4 flex items-center gap-2 border border-red-200">
        <i data-feather="alert-circle" class="w-5 h-5 text-red-600"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-2 mt-4">
        <div class="flex items-center gap-2">
            <a role="button" class="button text-white bg-blue-600 hover:bg-blue-700 shadow-sm px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer" data-toggle="modal" data-target="#add-new-karyawan">
                <i data-feather="plus" class="w-4 h-4"></i> Tambah Karyawan Baru
            </a>
            <a href="{{ route('hr.export_karyawan') }}" class="button bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-1.5 shadow-sm transition-all">
                <i data-feather="file-text" class="w-4 h-4 text-indigo-600"></i> Export to PDF
            </a>
        </div>
    </div>
	
    <div class="intro-y datatable-wrapper box p-5 mt-3">
    	<table class="table table-report table-report--bordered display datatable w-full mt-5">
    		<thead>
    			<tr>
    				<th class="border-b-2 text-center whitespace-no-wrap" style="width: 5%;">NO</th>
    				<th class="border-b-2 text-center whitespace-no-wrap">NIK</th>
    				<th class="border-b-2 text-center whitespace-no-wrap">RFID</th>
                    <th class="border-b-2 whitespace-no-wrap">NAMA LENGKAP KARYAWAN</th>
                    <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">JABATAN</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
    			</tr>
    		</thead>
    		<tbody>
    			@foreach ($karyawan_list as $index => $row)
	    				@if ($row->kry_nama != 'Qymous Code')
	    					<tr>
		    					<td class="text-center border-b">{{ $loop->iteration }}</td>
			                    <td class="text-center border-b font-mono">{{ $row->kry_nik ?? '-' }}</td>
			                    <td class="text-center border-b font-mono font-semibold text-blue-600">{{ $row->kry_username ?? '-' }}</td>
			                    <td class="border-b font-medium">{{ $row->kry_nama }}</td>
			                    <td class="border-b">{{ $row->kry_alamat ?? '-' }}</td>
			                    <td class="text-center border-b">{{ $row->kry_telp ?? '-' }}</td>
			                    <td class="text-center border-b">{{ $row->kry_level ?? '-' }}</td>
			                    <td class="border-b w-5">
			                        <div class="flex sm:justify-center items-center">
			                            <a class="flex items-center mr-3" role="button" data-toggle="modal" data-target="#edit-karyawan-{{ $row->kry_kode }}"> 
			                            	<i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit 
			                            </a>
			                            <a class="flex items-center text-theme-6 tombol-hapus" href="{{ url('HR/delete_karyawan/'.$row->kry_kode) }}" data-nama="{{ $row->kry_nama }}"> 
			                            	<i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete 
			                            </a>
			                        </div>
			                    </td>
			                </tr>
	    				@endif
	    			@endforeach    			
    		</tbody>
    	</table>
    </div>

    <!-- Header & Button untuk Magang / PKL -->
    <div class="intro-y flex flex-col sm:flex-row items-center justify-between mt-10">
        <h2 class="text-lg font-medium flex items-center">
            <i data-feather="book-open" class="w-5 h-5 inline-block mr-2 text-blue-600"></i>Daftar Magang / PKL
        </h2>
        <a role="button" class="button text-white bg-theme-1 shadow-md" data-toggle="modal" data-target="#add-new-magang">
            + Tambah Magang / PKL
        </a>
    </div>

    <!-- Tabel Data Magang / PKL -->
    <div class="intro-y datatable-wrapper box p-5 mt-5">
        <table class="table table-report table-report--bordered display datatable w-full">
            <thead>
                <tr>
                    <th class="border-b-2 text-center whitespace-no-wrap" style="width: 5%;">NO</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">RFID</th>
                    <th class="border-b-2 whitespace-no-wrap">NAMA LENGKAP</th>
                    <th class="border-b-2 whitespace-no-wrap">ALAMAT</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">NO HP</th>
                    <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($magang_list as $index => $m)
                <tr>
                    <td class="text-center border-b">{{ $loop->iteration }}</td>
                    <td class="text-center border-b font-mono font-semibold text-blue-600">{{ $m->kry_username }}</td>
                    <td class="border-b font-medium">{{ $m->kry_nama }}</td>
                    <td class="border-b">{{ $m->kry_alamat ?? '-' }}</td>
                    <td class="text-center border-b">{{ $m->kry_telp ?? '-' }}</td>
                    <td class="border-b w-5">
                        <div class="flex sm:justify-center items-center">
                            <a class="flex items-center mr-3" role="button" data-toggle="modal" data-target="#edit-magang-{{ $m->kry_kode }}"> 
                                <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit 
                            </a>
                            <a class="flex items-center text-theme-6 tombol-hapus" href="{{ url('HR/delete_karyawan/'.$m->kry_kode) }}" data-nama="{{ $m->kry_nama }}"> 
                                <i data-feather="trash-2" class="w-4 h-4 mr-1"></i> Delete 
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center border-b p-6 text-gray-500 italic">Belum ada data Magang / PKL.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

<!-- modal tambah magang/pkl -->
<div class="modal" id="add-new-magang">
    <div class="modal__content modal__content--lg p-6">
        <div class="flex items-center px-5 py-4 border-b border-gray-200">
            <h2 class="font-bold text-lg text-gray-800 mr-auto">Tambah Peserta Magang / PKL</h2>
            <button type="button" data-dismiss="modal" class="button border text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ route('hr.save_magang') }}" class="p-5">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kode / Kartu RFID *</label>
                <input type="text" name="rfid" class="input w-full border rounded p-2 text-sm" placeholder="Contoh: 0008168066 (dapat di-scan dengan alat USB RFID Reader)" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama" class="input w-full border rounded p-2 text-sm" placeholder="Nama lengkap siswa / mahasiswa Magang PKL" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" class="input w-full border rounded p-2 text-sm" rows="2" placeholder="Alamat lengkap"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">No HP</label>
                <input type="text" name="tlp" class="input w-full border rounded p-2 text-sm" placeholder="Nomor telepon / WhatsApp">
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button border text-gray-700 px-4 py-2">Batal</button>
                <button type="submit" class="button bg-theme-1 text-white px-4 py-2 font-semibold">Simpan Magang / PKL</button>
            </div>
        </form>
    </div>
</div>

@foreach ($magang_list as $m)
<div class="modal" id="edit-magang-{{ $m->kry_kode }}">
    <div class="modal__content modal__content--lg p-6">
        <div class="flex items-center px-5 py-4 border-b border-gray-200">
            <h2 class="font-bold text-lg text-gray-800 mr-auto">Edit Data Magang / PKL</h2>
            <button type="button" data-dismiss="modal" class="button border text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ route('hr.update_karyawan') }}" class="p-5">
            @csrf
            <input type="hidden" name="kode" value="{{ $m->kry_kode }}">
            <input type="hidden" name="level" value="Magang / PKL">

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Kode / Kartu RFID</label>
                <input type="text" name="username" value="{{ $m->kry_username }}" class="input w-full border rounded p-2 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ $m->kry_nama }}" class="input w-full border rounded p-2 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" class="input w-full border rounded p-2 text-sm" rows="2">{{ $m->kry_alamat }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">No HP</label>
                <input type="text" name="tlp" value="{{ $m->kry_telp }}" class="input w-full border rounded p-2 text-sm">
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                <button type="button" data-dismiss="modal" class="button border text-gray-700 px-4 py-2">Batal</button>
                <button type="submit" class="button bg-theme-1 text-white px-4 py-2 font-semibold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach
<!-- modal tambah-->
<!-- modal tambah karyawan -->
<div class="modal" id="add-new-karyawan">
    <div class="modal__content modal__content--lg p-6">
        <div class="flex items-center px-5 py-4 border-b border-gray-200">
            <h2 class="font-bold text-lg text-gray-800 mr-auto">Tambah Karyawan Baru</h2>
            <button type="button" data-dismiss="modal" class="button border text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ url('HR/save_karyawan') }}" class="p-5">
            @csrf
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIK *</label>
                    <input type="text" name="nik" class="input w-full border rounded p-2 text-sm" placeholder="Masukan NIK KTP" required>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode / Kartu RFID *</label>
                    <input type="text" name="username" class="input w-full border rounded p-2 text-sm" placeholder="Scan Kartu RFID / ID" required>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap Karyawan *</label>
                    <input type="text" name="nama" class="input w-full border rounded p-2 text-sm" placeholder="Nama lengkap karyawan" required>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan *</label>
                    <select name="level" class="input w-full border rounded p-2 text-sm" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Admin">Admin</option>
                        <option value="Kasir">Kasir</option>
                        <option value="Customer Service">Customer Service</option>
                        <option value="Teknisi">Teknisi</option>
                        <option value="HR">HR</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No HP *</label>
                    <input type="text" name="tlp" class="input w-full border rounded p-2 text-sm" placeholder="Nomor telepon / WhatsApp" required>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="pswd" class="input w-full border rounded p-2 text-sm" placeholder="Default: 123456">
                </div>
                <div class="col-span-12">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" class="input w-full border rounded p-2 text-sm" rows="2" placeholder="Alamat lengkap"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 mt-4">
                <button type="button" data-dismiss="modal" class="button border text-gray-700 px-4 py-2">Batal</button>
                <button type="submit" class="button bg-theme-1 text-white px-4 py-2 font-semibold">Simpan Karyawan</button>
            </div>
        </form>
    </div>
</div>

@foreach ($karyawan_list as $row)
<div class="modal" id="edit-karyawan-{{ $row->kry_kode }}">
    <div class="modal__content modal__content--lg p-6">
        <div class="flex items-center px-5 py-4 border-b border-gray-200">
            <h2 class="font-bold text-lg text-gray-800 mr-auto">Edit Karyawan: {{ $row->kry_nama }}</h2>
            <button type="button" data-dismiss="modal" class="button border text-gray-700">&times;</button>
        </div>
        <form method="POST" action="{{ url('HR/update_karyawan') }}" class="p-5">
            @csrf
            <input type="hidden" name="kode" value="{{ $row->kry_kode }}">

            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ $row->kry_nik }}" class="input w-full border rounded p-2 text-sm" placeholder="NIK KTP" required>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kode / Kartu RFID</label>
                    <input type="text" name="username" value="{{ $row->kry_username }}" class="input w-full border rounded p-2 text-sm" placeholder="Scan Kartu RFID / ID" required>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap Karyawan</label>
                    <input type="text" name="nama" value="{{ $row->kry_nama }}" class="input w-full border rounded p-2 text-sm" required>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan</label>
                    <select name="level" class="input w-full border rounded p-2 text-sm" required>
                        <option value="Admin" {{ $row->kry_level == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Kasir" {{ $row->kry_level == 'Kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="Customer Service" {{ $row->kry_level == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                        <option value="Teknisi" {{ $row->kry_level == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="HR" {{ $row->kry_level == 'HR' ? 'selected' : '' }}>HR</option>
                    </select>
                </div>
                <div class="col-span-12 sm:col-span-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No HP</label>
                    <input type="text" name="tlp" value="{{ $row->kry_telp }}" class="input w-full border rounded p-2 text-sm" required>
                </div>
                <div class="col-span-12">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" class="input w-full border rounded p-2 text-sm" rows="2" required>{{ $row->kry_alamat }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 mt-4">
                <button type="button" data-dismiss="modal" class="button border text-gray-700 px-4 py-2">Batal</button>
                <button type="submit" class="button bg-theme-1 text-white px-4 py-2 font-semibold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection