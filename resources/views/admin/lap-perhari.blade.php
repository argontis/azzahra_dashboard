@extends('layouts.app')

@section('content')
<div class="content">
    <header class="page-header mb-5">
        <div class="header-title">
            <h1><i data-feather="layout" class="w-6 h-6 inline-block mr-2"></i>{{ $title ?? 'Admin Dashboard' }}</h1>
        </div>
    </header>
    <div class="intro-y box p-5 mt-5">
        <div class="overflow-x-auto">
            <table class="table table-report table-report--bordered display datatable w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                        <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">TANGGAL</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">JENIS BAYAR</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">BANK</th>
                        <th class="border-b-2 text-right whitespace-no-wrap">JUMLAH (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($payments as $index => $row)
                        <tr>
                            <td class="text-center border-b">{{ $index + 1 }}</td>
                            <td class="text-center border-b">{{ $row->trans_kode }}</td>
                            <td class="border-b">{{ $row->transaksi->customer->cos_nama ?? '-' }}</td>
                            <td class="text-center border-b">{{ \Carbon\Carbon::parse($row->dtl_tanggal)->format('d-m-Y') }}</td>
                            <td class="text-center border-b">
                                <span class="px-2 py-1 rounded {{ $row->dtl_status == 'PELUNASAN' ? 'bg-theme-9 text-white' : 'bg-theme-1 text-white' }}">
                                    {{ $row->dtl_status }}
                                </span>
                            </td>
                            <td class="text-center border-b">{{ $row->dtl_jenis_bayar }}</td>
                            <td class="text-center border-b">{{ $row->dtl_bank ?? '-' }}</td>
                            <td class="text-right border-b">
                                {{ number_format($row->dtl_jml_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                        @php $total += $row->dtl_jml_bayar; @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-right font-bold text-lg p-4">TOTAL PENDAPATAN HARI INI:</td>
                        <td class="text-right font-bold text-lg text-theme-9 p-4">Rp {{ number_format($total, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($menunggu_count > 0)
        <div class="mt-8 rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white">
            <i data-feather="alert-triangle" class="w-6 h-6 mr-2"></i> Terdapat {{ $menunggu_count }} transaksi bank yang belum disetorkan sejumlah Rp {{ number_format($menunggu_total, 0, ',', '.') }}.
        </div>
        @endif
    </div>
</div>
@endsection
