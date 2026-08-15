@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="credit-card" class="w-6 h-6 inline-block mr-2"></i> Pembayaran</h1>                
        <p>Kelola Data Pembayaran Customer</p>
    </div>            
</header>

<div class="content" style="margin-top: 60px;">
    @if(session('sukses'))
        <div class="alert alert-success d-flex align-items-center mb-5" role="alert">
            <i data-feather="check-circle" class="mr-2"></i>
            <span>{{ session('sukses') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12">
            <!-- Box Tabel -->
            <div class="intro-y datatable-wrapper box p-5">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                    <h2 class="font-medium text-base mr-auto">
                        Daftar Pembayaran ({{ ucfirst($filter ?? 'Semua') }})
                    </h2>
                </div>
                
                <div class="p-5">
                    <table class="table table-report table-report--bordered display datatable w-full">
                        <thead>
                            <tr>
                                <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                                <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">TOTAL BAYAR</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                                <th class="border-b-2 text-center whitespace-no-wrap">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ini adalah contoh data statis / placeholder -->
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <div class="flex flex-col items-center justify-center">
                                        <i data-feather="inbox" class="w-10 h-10 mb-2 text-gray-500"></i>
                                        <p>Belum ada data pembayaran untuk saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- End Box Tabel -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection