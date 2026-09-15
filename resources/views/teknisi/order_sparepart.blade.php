@extends('layouts.app')

@section('content')
<style>
    .os-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-menunggu { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .status-sampai   { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-dot {
        width: 7px; height: 7px; border-radius: 50%; display: inline-block; flex-shrink: 0;
    }
    .status-dot-menunggu { background-color: #f59e0b; }
    .status-dot-sampai   { background-color: #22c55e; }
    .empty-state {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; padding: 64px 20px; color: #94a3b8;
    }
    .table-row-hover:hover { background-color: #f8fafc; }
</style>

<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="box" class="w-5 h-5 inline-block mr-2"></i>Order Sparepart Saya</h1>
        <p>Pantau status permintaan sparepart yang kamu ajukan</p>
    </div>
    <div class="header-actions">
        <div class="search-input-wrapper">
            <i data-feather="search" class="search-icon"></i>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="header-btn header-btn-bell" id="topbar-bell-btn" style="cursor: pointer; position: relative;">
            <i data-feather="bell"></i>
            <div class="badge-dot"></div>
        </div>
        <div class="header-btn header-btn-mail" id="topbar-mail-btn" style="cursor: pointer; position: relative;">
            <i data-feather="mail"></i>
        </div>
    </div>
</header>

<div class="content-area">

    <div class="mb-4 flex flex-wrap justify-between items-center gap-3">
        <a href="{{ route('teknisi.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#f1f5f9;border-radius:8px;font-size:13px;font-weight:600;color:#374151;text-decoration:none;border:1px solid #e2e8f0;">
            <i data-feather="arrow-left" style="width:14px;height:14px;"></i>
            Kembali ke Dashboard
        </a>
        <div class="flex gap-2">
            <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:6px 14px;font-size:12px;font-weight:600;color:#92400e;display:inline-flex;align-items:center;gap:6px;">
                <span class="status-dot status-dot-menunggu"></span>
                Menunggu: {{ $totalMenunggu }}
            </div>
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:6px 14px;font-size:12px;font-weight:600;color:#166534;display:inline-flex;align-items:center;gap:6px;">
                <span class="status-dot status-dot-sampai"></span>
                Sampai: {{ $totalSampai }}
            </div>
        </div>
    </div>

    @if(session('sukses'))
    <div class="alert bg-green-100 text-green-800 p-3.5 rounded-lg mb-4 border border-green-200 text-xs flex items-center gap-2">
        <i data-feather="check-circle" style="width:16px;height:16px;" class="text-green-600"></i>
        <span>{{ session('sukses') }}</span>
    </div>
    @endif

    <div class="os-card p-6">
        <div class="flex items-center gap-2 mb-5">
            <div style="width:22px;height:22px;border-radius:50%;background:#d97706;color:#fff;display:flex;align-items:center;justify-content:center;">
                <i data-feather="box" style="width:13px;height:13px;"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-sm">Riwayat Order Sparepart</h3>
            <span style="margin-left:auto;font-size:11px;color:#94a3b8;">Total: {{ $orders->total() }} order</span>
        </div>

        @if($orders->isEmpty())
            <div class="empty-state">
                <i data-feather="package" style="width:56px;height:56px;opacity:0.35;margin-bottom:16px;"></i>
                <p class="font-semibold text-sm text-gray-400">Belum ada order sparepart</p>
                <p class="text-xs text-gray-400 mt-1">Gunakan tombol "Order Sparepart" di halaman Input Tindakan</p>
            </div>
        @else
            <div class="overflow-x-auto" style="border-top: 1px solid #f1f5f9;">
                <table class="table w-full text-xs">
                    <thead>
                        <tr style="border-bottom:1px solid #f1f5f9;color:#94a3b8;font-weight:600;">
                            <th class="w-10 text-center py-3">#</th>
                            <th class="py-3 text-left">NO TRANSAKSI</th>
                            <th class="py-3 text-left">NAMA CUSTOMER</th>
                            <th class="py-3 text-left">NAMA BARANG / SPAREPART</th>
                            <th class="py-3 text-center">STATUS BARANG</th>
                            <th class="py-3 text-center">STATUS ORDER</th>
                            <th class="py-3 text-left">TANGGAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $i => $order)
                        <tr class="table-row-hover" style="border-bottom:1px solid #f8fafc;">
                            <td class="text-center text-gray-500 py-3">{{ $orders->firstItem() + $i }}</td>
                            <td class="py-3">
                                <span class="font-mono font-semibold" style="color:#2563eb;">{{ $order->trans_kode }}</span>
                            </td>
                            <td class="py-3 font-medium text-gray-800">{{ $order->cos_nama }}</td>
                            <td class="py-3 text-gray-700">
                                <div class="flex items-center gap-1.5">
                                    <i data-feather="package" style="width:12px;height:12px;color:#d97706;flex-shrink:0;"></i>
                                    {{ $order->barang_nama }}
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                @if($order->ketersediaan === 'tidak_ada')
                                    <span style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:20px;padding:3px 10px;font-size:10px;font-weight:600;">
                                        Tidak Ada di Teknisi
                                    </span>
                                @else
                                    <span style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:20px;padding:3px 10px;font-size:10px;font-weight:600;">
                                        Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @if($order->status === 'menunggu')
                                    <span class="status-badge status-menunggu">
                                        <span class="status-dot status-dot-menunggu"></span>
                                        Menunggu Barang
                                    </span>
                                @elseif(strtolower($order->status) === 'sampai')
                                    <span class="status-badge status-sampai">
                                        <span class="status-dot status-dot-sampai"></span>
                                        Barang Sampai ✓
                                    </span>
                                @else
                                    <span class="status-badge" style="background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0;">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-gray-500">
                                {{ \Carbon\Carbon::parse($order->created_at)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $orders->links() }}
            </div>
            @endif
        @endif
    </div>

    <div style="margin-top:16px;background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px 18px;font-size:12px;color:#1e40af;">
        <div class="flex items-start gap-2">
            <i data-feather="info" style="width:14px;height:14px;flex-shrink:0;margin-top:1px;color:#3b82f6;"></i>
            <div>
                <span class="font-semibold">Info:</span>
                Saat status berubah menjadi <strong>"Barang Sampai"</strong>, kamu bisa kembali ke halaman order tersebut dan melanjutkan input tindakan perbaikan.
            </div>
        </div>
    </div>

</div>
@endsection

