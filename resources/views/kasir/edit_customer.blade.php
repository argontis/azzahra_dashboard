@extends('layouts.app') 

@section('content')
<style>
    .area-konten {
        margin-left: 260px; 
        padding: 30px 24px;
        min-height: 100vh;
        background-color: #f8fafc; 
        font-family: 'Inter', sans-serif;
    }
    .form-container { max-width: 800px; margin: 0 auto; }
    .card-form {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        padding: 32px;
    }
    .section-title {
        color: #1a3c87; font-weight: 700; font-size: 1.1rem;
        border-bottom: 2px solid #f1f5f9; padding-bottom: 12px;
        margin-bottom: 24px; margin-top: 32px;
    }
    .section-title:first-child { margin-top: 0; }
    .form-label { font-weight: 600; color: #475569; margin-bottom: 8px; font-size: 0.9rem; display: block; }
    .form-control-custom {
        width: 100%; display: block; border-radius: 10px; border: 1px solid #cbd5e1;
        padding: 12px 16px; background-color: #f8fafc; font-size: 0.95rem; color: #1e293b;
        transition: all 0.2s ease;
    }
    .form-control-custom:focus {
        background-color: #ffffff; border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); outline: none;
    }
    .form-group { margin-bottom: 20px; }
    @media (max-width: 991px) {
        .area-konten { margin-left: 0; padding: 16px; }
        .card-form { padding: 20px; }
    }
</style>

<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="edit" class="w-5 h-5 inline-block mr-2"></i>Edit Data Customer</h1>
        <p>Kode Transaksi: {{ $transaksi->trans_kode }}</p>
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
    <div class="form-container" style="max-width: 800px; margin: 0 auto; padding-top: 8px;">
        <div class="card-form">
            <!-- Arahkan Action Form ke rute update -->
            <form action="{{ url('/Kasir/update/' . $transaksi->trans_kode) }}" method="POST">
                @csrf
                @method('PUT') <!-- Wajib untuk proses Update data di Laravel -->
                
                <h5 class="section-title">Informasi Pelanggan</h5>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <!-- value diisi data dari database -->
                        <input type="text" name="cos_nama" value="{{ old('cos_nama', $transaksi->customer->cos_nama) }}" class="form-control-custom" required>
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label class="form-label">No. HP / WhatsApp <span class="text-danger">*</span></label>
                        <input type="number" name="cos_hp" value="{{ old('cos_hp', $transaksi->customer->cos_hp) }}" class="form-control-custom" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email <span style="color: #94a3b8; font-weight: normal;">(Opsional)</span></label>
                    <input type="email" name="cos_email" value="{{ old('cos_email', $transaksi->customer->cos_email) }}" class="form-control-custom">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="cos_alamat" class="form-control-custom" rows="3" required>{{ old('cos_alamat', $transaksi->customer->cos_alamat) }}</textarea>
                </div>

                <h5 class="section-title">Detail Transaksi / Servis</h5>
                
                <div class="form-group mb-0">
                    <label class="form-label">Deskripsi Permasalahan / Keluhan <span class="text-danger">*</span></label>
                    <!-- Mengambil teks keluhan di antara tag penutup dan pembuka textarea -->
                    <textarea name="cos_keluhan" class="form-control-custom" rows="4" required>{{ old('cos_keluhan', $transaksi->customer->cos_keluhan) }}</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                    <a href="{{ url('/Kasir') }}" style="background-color: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 600; padding: 10px 24px; text-decoration: none; border: 1px solid #e2e8f0; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#e2e8f0'" onmouseout="this.style.backgroundColor='#f1f5f9'">
                        Batal
                    </a>
                    <button type="submit" style="background-color: #f59e0b; color: white; border-radius: 8px; font-weight: 600; padding: 10px 24px; border: none; display: flex; align-items: center; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#d97706'" onmouseout="this.style.backgroundColor='#f59e0b'">
                        <i data-feather="check" style="width: 18px; height: 18px; margin-right: 8px;"></i> Perbarui Data
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection