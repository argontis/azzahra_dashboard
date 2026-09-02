@extends('layouts.app') 

@section('content')
<style>
    /* Mengunci Layout Utama */
    .area-konten {
        margin-left: 260px; 
        padding: 30px 24px;
        min-height: 100vh;
        background-color: #f8fafc; /* Warna background luar abu-abu sangat lembut */
        font-family: 'Inter', sans-serif;
    }

    /* Membatasi lebar form agar tidak terlalu memanjang dan memusatkannya ke tengah */
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    /* Style untuk Kotak Card Form */
    .card-form {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        padding: 32px;
    }

    /* Judul Setiap Bagian (Informasi Pelanggan & Detail Servis) */
    .section-title {
        color: #1a3c87;
        font-weight: 700;
        font-size: 1.1rem;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px;
        margin-bottom: 24px;
        margin-top: 32px;
    }
    .section-title:first-child {
        margin-top: 0;
    }

    /* Style untuk Label (Nama, No HP, dll) */
    .form-label {
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
        font-size: 0.9rem;
        display: block;
    }

    /* Style untuk Input & Textarea */
    .form-control-custom {
        width: 100%;
        display: block;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 12px 16px;
        background-color: #f8fafc; /* Warna input sedikit keabu-abuan agar kontras dengan card putih */
        font-size: 0.95rem;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    /* Efek ketika input sedang diklik/diisi */
    .form-control-custom:focus {
        background-color: #ffffff;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1);
        outline: none;
    }

    /* Layout responsif untuk jarak baris */
    .form-group {
        margin-bottom: 20px;
    }

    @media (max-width: 991px) {
        .area-konten {
            margin-left: 0;
            padding: 16px;
        }
        .card-form {
            padding: 20px;
        }
    }
</style>

<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="user-plus" class="w-5 h-5 inline-block mr-2"></i>Tambah Customer Baru</h1>
        <p>Input data pelanggan dan detail permasalahan</p>
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
        
        </div>

        <!-- Card Form -->
        <div class="card-form">
            <form action="{{ url('/Kasir/simpan_customer') }}" method="POST">
                @csrf
                
                <h5 class="section-title">Informasi Pelanggan</h5>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="cos_nama" class="form-control-custom" required placeholder="Masukkan Nama Pelanggan">
                    </div>
                    
                    <div class="col-md-6 form-group">
                        <label class="form-label">No. HP / WhatsApp <span class="text-danger">*</span></label>
                        <input type="number" name="cos_hp" class="form-control-custom" required placeholder="Contoh: 08123456789">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email <span style="color: #94a3b8; font-weight: normal;">(Opsional)</span></label>
                    <input type="email" name="cos_email" class="form-control-custom" placeholder="email@contoh.com">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea name="cos_alamat" class="form-control-custom" rows="3" required placeholder="Masukkan detail alamat lengkap..."></textarea>
                </div>

                <h5 class="section-title">Detail Transaksi / Servis</h5>
                
                <div class="col-md-12">
                    <label class="form-label fw-semibold text-muted small mb-1">Deskripsi Permasalahan / Keluhan <span class="text-danger">*</span></label>
                    <!-- PASTIKAN name-nya adalah cos_keluhan -->
                    <textarea name="cos_keluhan" class="form-control bg-light" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 12px 16px;" rows="4" required placeholder="Jelaskan detail kendala atau perangkat yang dibawa..."></textarea>
                </div>

                <!-- Footer Tombol -->
                <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                    <a href="{{ url('/Kasir') }}" style="background-color: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 600; padding: 10px 24px; text-decoration: none; border: 1px solid #e2e8f0; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#e2e8f0'" onmouseout="this.style.backgroundColor='#f1f5f9'">
                        Batal
                    </a>
                    <button type="submit" style="background-color: #1a3c87; color: white; border-radius: 8px; font-weight: 600; padding: 10px 24px; border: none; display: flex; align-items: center; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#122b63'" onmouseout="this.style.backgroundColor='#1a3c87'">
                        <i data-feather="save" style="width: 18px; height: 18px; margin-right: 8px;"></i> Simpan Data
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection