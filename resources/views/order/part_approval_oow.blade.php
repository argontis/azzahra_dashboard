@extends('layouts.app')

@section('content')
<div class="content">
    <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
        <h2 class="text-2xl font-bold mr-auto text-gray-800">{{ $page_title }}</h2>
    </div>

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
