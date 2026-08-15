@extends('layouts.app')

@section('content')
<!-- Header -->
        <header class="page-header mb-5">
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Data Laporan</h1>                
                <p>Laporan Hari ini</p>
            </div>            
        </header>
<div class="content" style="margin-top: 200px;">
    <div class="intro-y box overflow-hidden mt-5">
        <div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
            <div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
            <div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
                <div class="text-xl text-theme-1 font-medium">Azzahra Computer Tegal</div>
                <div class="mt-1">Tegal, {{ \Carbon\Carbon::today()->format('d-F-Y') }}</div>
            </div>
        </div>
        <div class="px-5 sm:px-16 py-5">
            <div class="flex justify-end">
                <form action="{{ url('Export/lap_perhari_excel') }}" method="post" class="mr-2">
                    <button type="submit" class="button text-white bg-theme-1 shadow-md flex">
                        <i data-feather="file-text" class="mr-2"></i>Export to Excel
                    </button>
                </form>
                <div class="dropdown relative ml-auto sm:ml-0">
                    <button class="dropdown-toggle button px-2 box text-gray-700">
                        <span class="w-5 h-5 flex items-center justify-center"> <i class="w-4 h-4" data-feather="plus"></i> </span>
                    </button>
                    <div class="dropdown-box mt-10 absolute w-40 top-0 right-0 z-20">
                        <div class="dropdown-box__content box p-2">
                        <a href="{{ url('Admin/export_pdf_lap_perhari') }}" class="flex items-center block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md"> <i data-feather="file" class="w-4 h-4 mr-2"></i> Export PDF </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-5 sm:px-16 py-10 sm:py-20">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">DESCRIPTION</th>
                            <th class="border-b-2 text-right whitespace-no-wrap">JUMLAH</th>
                            <th class="border-b-2 text-right whitespace-no-wrap">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">DOWN PAYMENT BANK BCA</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
                            </td>
                            <td class="text-right border-b w-32">{{ $jml_DP_bca }}</td>
                            <td class="text-right border-b w-32">
                                {{ "Rp. ".number_format($tot_DP_bca ?? 0, 0).",-" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">DOWN PAYMENT BANK MANDIRI</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
                            </td>
                            <td class="text-right border-b w-32">{{ $jml_DP_mandiri }}</td>
                            <td class="text-right border-b w-32">
                                {{ "Rp. ".number_format($tot_DP_mandiri ?? 0, 0).",-" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">DOWN PAYMENT TUNAI</div>
                            </td>
                            <td class="text-right border-b w-32">{{ $jml_DP_tunai }}</td>
                            <td class="text-right border-b w-32">
                                {{ "Rp. ".number_format($tot_DP_tunai ?? 0, 0).",-" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">PELUNASAN BANK BCA</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
                            </td>
                            <td class="text-right border-b w-32">{{ $jml_lns_bca }}</td>
                            <td class="text-right border-b w-32">
                                {{ "Rp. ".number_format($tot_lns_bca ?? 0, 0).",-" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">PELUNASAN BANK MANDIRI</div>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
                            </td>
                            <td class="text-right border-b w-32">{{ $jml_lns_mandiri }}</td>
                            <td class="text-right border-b w-32">
                                {{ "Rp. ".number_format($tot_lns_mandiri ?? 0, 0).",-" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">PELUNASAN TUNAI</div>
                            </td>
                            <td class="text-right border-b w-32">{{ $jml_lns_tunai }}</td>
                            <td class="text-right border-b w-32">
                                {{ "Rp. ".number_format($tot_lns_tunai ?? 0, 0).",-" }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b">
                                <div class="font-medium whitespace-no-wrap">STATUS SETOR MENUNGGU</div>
                            </td>
                            <td class="text-right border-b w-32">{{ $menunggu_count }}</td>
                            <td class="text-right border-b w-32">
                                {{ "Rp. ".number_format($menunggu_total, 0).",-" }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- DP Payments Detail -->
        <div class="px-5 sm:px-16 py-10 sm:py-20">
            <h3 class="text-lg font-medium mb-5">Detail Pembayaran DP Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">TTS</th>
                            <th class="border-b-2 whitespace-no-wrap">Nama Customer</th>
                            <th class="border-b-2 whitespace-no-wrap">Domisili</th>
                            <th class="border-b-2 text-right whitespace-no-wrap">Jumlah Bayar</th>
                            <th class="border-b-2 whitespace-no-wrap">Jenis</th>
                            <th class="border-b-2 whitespace-no-wrap">Status Setor</th>
                            <th class="border-b-2 whitespace-no-wrap">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total_dp = 0;
                        $count_dp = 0;
                        @endphp
                        @if ($dp_payments->isNotEmpty())
                            @foreach ($dp_payments as $payment) 
                                @php
                                $total_dp += $payment->dtl_jml_bayar;
                                $count_dp++;
                                @endphp
                                <tr>
                                    <td class="border-b">{{ $payment->cos_kode }}</td>
                                    <td class="border-b">{{ $payment->cos_nama }}</td>
                                    <td class="border-b">{{ $payment->cos_alamat ?? '-' }}
                                    </td>
                                    <td class="text-right border-b">
                                        {{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-" }}
                                    </td>
                                    <td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
                                    <td class="border-b">{{ $payment->dtl_stt_stor }}</td>
                                    <td class="border-b">
                                        <div class="font-medium whitespace-no-wrap">
                                            {{ \Carbon\Carbon::parse($payment->dtl_tanggal)->format('d-m-Y') }}
                                        </div>
                                        <div class="text-gray-600 text-xs whitespace-no-wrap">
                                            {{ $payment->dtl_jam }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center border-b text-gray-500">Tidak ada pembayaran DP hari ini</td>
                            </tr>
                        @endif
                        @if ($dp_payments->isNotEmpty())
                            <tr class="bg-gray-100 font-bold">
                                <td colspan="2" class="border-t-2 border-b-2 text-right py-3">TOTAL</td>
                                <td class="text-right border-t-2 border-b-2 py-3">
                                    {{ "Rp. ".number_format($total_dp, 0).",-" }}
                                </td>
                                <td colspan="3" class="border-t-2 border-b-2 py-3"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Pelunasan Payments Detail -->
        <div class="px-5 sm:px-16 py-10 sm:py-20">
            <h3 class="text-lg font-medium mb-5">Detail Pembayaran Pelunasan Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">TTS</th>
                            <th class="border-b-2 whitespace-no-wrap">Nama Customer</th>
                            <th class="border-b-2 whitespace-no-wrap">Domisili</th>
                            <th class="border-b-2 text-right whitespace-no-wrap">Jumlah Bayar</th>
                            <th class="border-b-2 whitespace-no-wrap">Jenis</th>
                            <th class="border-b-2 whitespace-no-wrap">Status Setor</th>
                            <th class="border-b-2 whitespace-no-wrap">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total_lunas = 0;
                        $count_lunas = 0;
                        @endphp
                        @if ($lunas_payments->isNotEmpty())
                            @foreach ($lunas_payments as $payment) 
                                @php
                                $total_lunas += $payment->dtl_jml_bayar;
                                $count_lunas++;
                                @endphp
                                <tr>
                                    <td class="border-b">{{ $payment->cos_kode }}</td>
                                    <td class="border-b">{{ $payment->cos_nama }}</td>
                                   <td class="border-b">{{ $payment->cos_alamat ?? '-' }}
                                    </td>
                                    <td class="text-right border-b">
                                        {{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-" }}
                                    </td>
                                    <td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
                                    <td class="border-b">{{ $payment->dtl_stt_stor }}</td>
                                    <td class="border-b">
                                        <div class="font-medium whitespace-no-wrap">
                                            {{ \Carbon\Carbon::parse($payment->dtl_tanggal)->format('d-m-Y') }}
                                        </div>
                                        <div class="text-gray-600 text-xs whitespace-no-wrap">
                                            {{ $payment->dtl_jam }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center border-b text-gray-500">Tidak ada pembayaran pelunasan hari ini</td>
                            </tr>
                        @endif
                        @if ($lunas_payments->isNotEmpty())
                            <tr class="bg-gray-100 font-bold">
                                <td colspan="2" class="border-t-2 border-b-2 text-right py-3">TOTAL</td>
                                <td class="text-right border-t-2 border-b-2 py-3">
                                    {{ "Rp. ".number_format($total_lunas, 0).",-" }}
                                </td>
                                <td colspan="3" class="border-t-2 border-b-2 py-3"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Menunggu Payments Detail -->
        <div class="px-5 sm:px-16 py-10 sm:py-20">
            <h3 class="text-lg font-medium mb-5">Detail Pembayaran Status Menunggu Hari Ini</h3>
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="border-b-2 whitespace-no-wrap">TTS</th>
                            <th class="border-b-2 whitespace-no-wrap">Nama Customer</th>
                            <th class="border-b-2 whitespace-no-wrap">Domisili</th>
                            <th class="border-b-2 text-right whitespace-no-wrap">Jumlah Bayar</th>
                            <th class="border-b-2 whitespace-no-wrap">Tipe</th>
                            <th class="border-b-2 whitespace-no-wrap">Jenis</th>
                            <th class="border-b-2 whitespace-no-wrap">Status Setor</th>
                            <th class="border-b-2 whitespace-no-wrap">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($menunggu_payments->isNotEmpty())
                            @foreach ($menunggu_payments as $payment)
                                <tr>
                                    <td class="border-b">{{ $payment->cos_kode }}</td>
                                    <td class="border-b">{{ $payment->cos_nama }}</td>
                                     <td class="border-b">{{ $payment->cos_alamat ?? '-' }}
                                    </td>
                                    <td class="text-right border-b">
                                        {{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-" }}
                                    </td>
                                    <td class="border-b">{{ $payment->dtl_status }}</td>
                                    <td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
                                    <td class="border-b">{{ $payment->dtl_stt_stor }}</td>
                                    <td class="border-b">
                                        <div class="font-medium whitespace-no-wrap">
                                            {{ \Carbon\Carbon::parse($payment->dtl_tanggal)->format('d-m-Y') }}
                                        </div>
                                        <div class="text-gray-600 text-xs whitespace-no-wrap">
                                            {{ $payment->dtl_jam }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center border-b text-gray-500">Tidak ada pembayaran dengan status menunggu hari ini</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>      
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600">Down Payment</div>
                <div class="text-lg text-theme-1 font-medium mt-2">Tunai</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                    {{ "Rp. ".number_format($tot_DP_tunai ?? 0, 0).",-" }}
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - {{ $jml_DP_tunai }}</div>
            </div>
        </div>
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600">Down Payment</div>
                <div class="text-lg text-theme-1 font-medium mt-2">Transfer</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                    {{ "Rp. ".number_format(($tot_DP_bca ?? 0) + ($tot_DP_mandiri ?? 0), 0).",-" }}
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - {{ $jml_DP_bca + $jml_DP_mandiri }}</div>
            </div>
        </div>
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600">Pelunasan</div>
                <div class="text-lg text-theme-1 font-medium mt-2">Tunai</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                    {{ "Rp. ".number_format($tot_lns_tunai ?? 0, 0).",-" }}
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - {{ $jml_lns_tunai }}</div>
            </div>
        </div>
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-base text-gray-600">Pelunasan</div>
                <div class="text-lg text-theme-1 font-medium mt-2">Transfer</div>
                <div class="mt-1">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base text-gray-600">Total</div>
                <div class="text-xl text-theme-1 font-medium mt-2">
                    {{ "Rp. ".number_format(($tot_lns_bca ?? 0) + ($tot_lns_mandiri ?? 0), 0).",-" }}
                </div>
                <div class="mt-1 tetx-xs">Dengan Total Jumlah - {{ $jml_lns_bca + $jml_lns_mandiri }}</div>
            </div>
        </div>
        @php
        // Hitung total keseluruhan dari detail tabel
        $grand_total = ($total_dp ?? 0) + ($total_lunas ?? 0);
        $grand_count = ($count_dp ?? 0) + ($count_lunas ?? 0);
        @endphp
        <div class="px-5 sm:px-20 pb-10 sm:pb-20 flex flex-col-reverse sm:flex-row bg-theme-1 text-white">
            <div class="text-center sm:text-left mt-10 sm:mt-0">
                <div class="text-lg font-bold mt-5">TOTAL KESELURUHAN</div>
                <div class="text-base mt-2">Azzahra Computer Tegal</div>
            </div>
            <div class="text-center sm:text-right sm:ml-auto">
                <div class="text-base mt-5">Total Pembayaran</div>
                <div class="text-3xl font-bold mt-2">
                    {{ "Rp. ".number_format($grand_total, 0).",-" }}
                </div>
                <div class="mt-1 text-sm">Dengan Total Jumlah Transaksi - {{ $grand_count }}</div>
            </div>
        </div>
</div>
@endsection