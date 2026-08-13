@extends('layouts.app')

@section('content')
<!-- Header -->
<header class="page-header mb-5">
    <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
        <i data-feather="menu"></i>
    </div>
    <div class="header-title">
        <h1><i data-feather="percent" class="w-6 h-6 inline-block mr-2"></i>Discount</h1>                
        <p>Transaksi Dengan Discount</p>	
    </div>            
</header>
<div class="content" style="margin-top: 60px">
    <div class="sukses" data-sukses="<?php echo session('sukses');?>"></div>	
    <div class="col-span-12 lg:col-span-9 xxl:col-span-10">
        <div class="intro-y datatable-wrapper box p-5 mt-5">
            <table class="table table-report table-report--bordered display datatable w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 text-center whitespace-no-wrap">NO</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">INVOICE</th>
                        <th class="border-b-2 whitespace-no-wrap">NAMA CUSTOMER</th>
                        <th class="border-b-2 text-right whitespace-no-wrap">TOTAL TRANSAKSI</th>
                        <th class="border-b-2 text-right whitespace-no-wrap">DISCOUNT</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 0; @endphp
                    @foreach ($trans as $row)
                        <tr>
                            <td class="text-center border-b">{{ ++$no }}</td>
                            <td class="text-center border-b">{{ $row->trans_kode }}</td>
                            <td class="border-b">{{ $row->cos_nama ?: 'N/A' }}</td>
                            <td class="text-right border-b">Rp. {{ number_format($row->trans_total, 0, ',', '.') }}</td>
                            <td class="text-right border-b text-theme-6 font-bold">Rp. {{ number_format($row->trans_discount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="flex sm:justify-center items-center">
                                    <a href="{{ url('Admin/konfirmasi/'.$row->trans_kode) }}" class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
                                        <i data-feather="align-justify" class="w-4 h-4 mr-2"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
