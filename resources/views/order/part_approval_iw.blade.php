@extends('layouts.app')

@section('content')
<header class="page-header">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1>{{ $page_title }}</h1>
        <p>Approval Part In-Warranty</p>
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
    <div class="intro-y box p-5 mt-5">
        <div class="overflow-x-auto">
            <table class="table table-bordered w-full text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="whitespace-nowrap">Transaksi</th>
                        <th class="whitespace-nowrap">Customer</th>
                        <th class="whitespace-nowrap">Tanggal Diajukan</th>
                        <th class="whitespace-nowrap">Tipe</th>
                        <th class="whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvals as $approval)
                    <tr>
                        <td>{{ $approval->trans_kode }}</td>
                        <td>{{ $approval->order->customer->cos_nama ?? 'N/A' }}</td>
                        <td>{{ $approval->created_at ? $approval->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td><span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full font-bold uppercase">{{ $approval->type }}</span></td>
                        <td>
                            <button onclick="approvePart({{ $approval->approval_id }})" class="btn btn-success btn-sm"><i data-feather="check" class="w-4 h-4 mr-1"></i> Approve</button>
                            <button onclick="rejectPart({{ $approval->approval_id }})" class="btn btn-danger btn-sm"><i data-feather="x" class="w-4 h-4 mr-1"></i> Reject</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">Tidak ada part yang menunggu persetujuan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function approvePart(id) {
        if(confirm('Apakah Anda yakin ingin menyetujui permintaan part ini?')) {
            fetch(`{{ url('order_approval') }}/${id}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(res => res.json()).then(data => {
                if(data.success) location.reload();
                else alert(data.message);
            });
        }
    }

    function rejectPart(id) {
        let reason = prompt('Masukkan alasan penolakan:');
        if(reason) {
            fetch(`{{ url('order_approval') }}/${id}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ reject_reason: reason })
            }).then(res => res.json()).then(data => {
                if(data.success) location.reload();
                else alert(data.message);
            });
        }
    }
</script>
@endsection
