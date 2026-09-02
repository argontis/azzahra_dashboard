@extends('layouts.app')

@section('content')
<script>
// Ensure Export PDF uses selected date range
document.addEventListener('DOMContentLoaded', function() {
	var exportBtn = document.getElementById('exportPdfBtn');
	var tglAwal = document.querySelector('input[name="tgl_awal"]');
	var tglAkhir = document.querySelector('input[name="tgl_akhir"]');
	if (exportBtn && tglAwal && tglAkhir) {
		exportBtn.addEventListener('click', function(e) {
			e.preventDefault();
			var awal = tglAwal.value;
			var akhir = tglAkhir.value;
			if (!awal || !akhir) {
				alert('Pilih tanggal awal dan akhir terlebih dahulu!');
				return false;
			}
			var url = '{{ url('Admin/export_pdf_laporan') }}' + '?tgl_awal=' + encodeURIComponent(awal) + '&tgl_akhir=' + encodeURIComponent(akhir);
			// Disable button to prevent double clicks/requests
			try { exportBtn.setAttribute('disabled', 'disabled'); exportBtn.classList.add('opacity-50'); } catch(e) {}
			// Navigate to the PDF URL (let browser handle download)
			window.location.href = url;
		});
	}

	// Instantly reload report when date is picked
	function reloadLaporan() {
		var awal = tglAwal.value;
		var akhir = tglAkhir.value;
		if (awal && akhir) {
			var url = '{{ url('Admin/laporan') }}' + '?tgl_awal=' + encodeURIComponent(awal) + '&tgl_akhir=' + encodeURIComponent(akhir);
			window.location.href = url;
		}
	}
	if (tglAwal && tglAkhir) {
		tglAwal.addEventListener('change', reloadLaporan);
		tglAkhir.addEventListener('change', reloadLaporan);
	}
});
</script>
<!-- Header -->
        <header class="page-header mb-5" >
            <div class="mobile-menu-btn" onclick="toggleMobileSidebar()">
                <i data-feather="menu"></i>
            </div>
            <div class="header-title">
                <h1><i data-feather="activity" class="w-6 h-6 inline-block mr-2"></i>Data Laporan</h1>                
                <p>Laporan per tanggal</p>
            </div>
            <div class="header-actions">
                <div class="search-input-wrapper">
                    <i data-feather="search" class="search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                <div class="header-btn header-btn-bell" id="topbar-bell-btn" title="Pemberitahuan Sistem & Maintenance" style="cursor: pointer; position: relative;">
                    <i data-feather="bell"></i>
                    <div class="badge-dot" style="display: none;"></div>
                </div>
                <div class="header-btn header-btn-mail" id="topbar-mail-btn" title="Kotak Pesan" style="cursor: pointer; position: relative;">
                    <i data-feather="mail"></i>
                    <span class="topbar-mail-badge" style="display: none; position: absolute; top: -4px; right: -4px; background: #ef4444; color: white; border-radius: 9999px; font-size: 10px; font-weight: bold; min-width: 16px; height: 16px; line-height: 16px; text-align: center; padding: 0 4px;"></span>
                </div>
            </div>
        </header>
	<div class="content">	
        <div class="col-span-12 lg:col-span-9 xxl:col-span-10">
        	<form action="{{ url('Export/lap_excel') }}" method="post">
	            <div class="intro-y flex flex-col-reverse sm:flex-row items-center">           	
            		<div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
	                    <i class="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-gray-700" data-feather="calendar"></i> 
	                    <input type="date" class="input w-full sm:w-64 box px-10 text-gray-700 placeholder-theme-13" placeholder="Search tanggal" name="tgl_awal" required="" value="{{ $tgl_awal }}">                    
	                </div>
	                <div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
	                    Sampai                    
	                </div>
	                <div class="w-full sm:w-auto relative mr-auto mt-3 sm:mt-0">
	                    <i class="w-4 h-4 absolute my-auto inset-y-0 ml-3 left-0 z-10 text-gray-700" data-feather="calendar"></i> 
	                    <input type="date" class="input w-full sm:w-64 box px-10 text-gray-700 placeholder-theme-13" placeholder="Search tanggal" name="tgl_akhir" required="" value="{{ $tgl_akhir }}">
	                    
	                </div>
					<div class="w-full sm:w-auto flex">
					<input type="hidden" name="tgl_1" value="{{ $tgl_awal }}">
					<input type="hidden" name="tgl_2" value="{{ $tgl_akhir }}">
					<a id="exportPdfBtn" href="{{ url('Admin/export_pdf_laporan?tgl_awal=' . $tgl_awal . '&tgl_akhir=' . $tgl_akhir) }}" class="button text-white bg-red-600 shadow-md flex"><i data-feather="file"></i> &nbsp;Export to PDF</a>
					</div>
	                </div>
	            </div>
            </form>
			@if (isset($all_have_cabang) && $all_have_cabang && isset($payments_by_cabang) && is_array($payments_by_cabang))
				@foreach (["Tegal", "Cibubur", "Kampus Saintek", "Kampus PKTJ"] as $cabang)
					<div class="intro-y inbox box mt-5">
						<div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
							<div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
							<div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
								<div class="text-xl text-theme-1 font-medium">Azzahra Computer {{ $cabang }}</div>
								<div class="mt-1">Tanggal, <?php echo date('d-m-Y',strtotime($tgl_awal)) ?> Sampai <?php echo date('d-m-Y',strtotime($tgl_akhir)) ?></div>
							</div>
						</div>
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
									<?php 
										$cabang_data = $payments_by_cabang[$cabang] ?? [];
										$dp_bca = array_filter($cabang_data, function($p){ return $p->dtl_status == 'DP' && $p->dtl_bank == 'BCA'; });
										$dp_mandiri = array_filter($cabang_data, function($p){ return $p->dtl_status == 'DP' && $p->dtl_bank == 'MANDIRI'; });
										$dp_tunai = array_filter($cabang_data, function($p){ return $p->dtl_status == 'DP' && $p->dtl_jenis_bayar == 'TUNAI'; });
										$lunas_bca = array_filter($cabang_data, function($p){ return $p->dtl_status == 'PELUNASAN' && $p->dtl_bank == 'BCA'; });
										$lunas_mandiri = array_filter($cabang_data, function($p){ return $p->dtl_status == 'PELUNASAN' && $p->dtl_bank == 'MANDIRI'; });
										$lunas_tunai = array_filter($cabang_data, function($p){ return $p->dtl_status == 'PELUNASAN' && $p->dtl_jenis_bayar == 'TUNAI'; });
										$menunggu = array_filter($cabang_data, function($p){ return $p->dtl_stt_stor == 'Menunggu'; });
										
										$sum_dp_bca = array_sum(array_column($dp_bca, 'dtl_jml_bayar'));
										$sum_dp_mandiri = array_sum(array_column($dp_mandiri, 'dtl_jml_bayar'));
										$sum_dp_tunai = array_sum(array_column($dp_tunai, 'dtl_jml_bayar'));
										$sum_lunas_bca = array_sum(array_column($lunas_bca, 'dtl_jml_bayar'));
										$sum_lunas_mandiri = array_sum(array_column($lunas_mandiri, 'dtl_jml_bayar'));
										$sum_lunas_tunai = array_sum(array_column($lunas_tunai, 'dtl_jml_bayar'));
										$sum_menunggu = array_sum(array_column($menunggu, 'dtl_jml_bayar'));
									?>

									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK BCA</div>
											<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
										</td>
										<td class="text-right border-b w-32">{{ count($dp_bca); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format($sum_dp_bca, 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK BRI</div>
											<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
										</td>
										<td class="text-right border-b w-32">{{ count(array_filter($cabang_data, function($p){ return $p->dtl_status == 'DP' && $p->dtl_bank == 'BRI'; })); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format(array_sum(array_column(array_filter($cabang_data, function($p){ return $p->dtl_status == 'DP' && $p->dtl_bank == 'BRI'; }), 'dtl_jml_bayar')), 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK MANDIRI</div>
										</td>
										<td class="text-right border-b w-32">{{ count($dp_mandiri); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format($sum_dp_mandiri, 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">DOWN PATMENT TUNAI</div>
										</td>
										<td class="text-right border-b w-32">{{ count($dp_tunai); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format($sum_dp_tunai, 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">PELUNASAN BANK BCA</div>
											<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
										</td>
										<td class="text-right border-b w-32">{{ count($lunas_bca); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format($sum_lunas_bca, 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">PELUNASAN BANK BRI</div>
											<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
										</td>
										<td class="text-right border-b w-32">{{ count(array_filter($cabang_data, function($p){ return $p->dtl_status == 'PELUNASAN' && $p->dtl_bank == 'BRI'; })); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format(array_sum(array_column(array_filter($cabang_data, function($p){ return $p->dtl_status == 'PELUNASAN' && $p->dtl_bank == 'BRI'; }), 'dtl_jml_bayar')), 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">PELUNASAN BANK MANDIRI</div>
										</td>
										<td class="text-right border-b w-32">{{ count($lunas_mandiri); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format($sum_lunas_mandiri, 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">PELUNASAN TUNAI</div>
										</td>
										<td class="text-right border-b w-32">{{ count($lunas_tunai); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format($sum_lunas_tunai, 0).",-"; }}
										</td>
									</tr>
									<tr>
										<td class="border-b">
											<div class="font-medium whitespace-no-wrap">STATUS SETOR MENUNGGU</div>
										</td>
										<td class="text-right border-b w-32">{{ count($menunggu); }}</td>
										<td class="text-right border-b w-32">
											{{ "Rp. ".number_format($sum_menunggu, 0).",-"; }}
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<!-- DP Payments Detail -->
						<div class="px-5 sm:px-16 py-10 sm:py-20">
							<h3 class="text-lg font-medium mb-5">Detail Pembayaran DP</h3>
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
										<?php $dp = array_filter($payments_by_cabang[$cabang] ?? [], function($p){ return $p->dtl_status == 'DP'; }); ?>
										@if (!empty($dp))
											@foreach ($dp as $payment)
												<tr>
													<td class="border-b">{{ $payment->cos_kode }}</td>
													<td class="border-b">{{ $payment->cos_nama }}</td>
													<td class="border-b">{{ $payment->cos_alamat }}</td>
													<td class="text-right border-b">
														{{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-"; }}
													</td>
													<td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
													<td class="border-b">{{ $payment->dtl_stt_stor }}</td>
													<td class="border-b">
														<div class="font-medium whitespace-no-wrap">
															<?php echo date('d-m-Y', strtotime($payment->dtl_tanggal)) ?>
														</div>
														<div class="text-gray-600 text-xs whitespace-no-wrap">
															{{ $payment->dtl_jam }}
														</div>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td colspan="7" class="text-center border-b text-gray-500">Tidak ada pembayaran DP dalam periode ini</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<!-- Pelunasan Payments Detail -->
						<div class="px-5 sm:px-16 py-10 sm:py-20">
							<h3 class="text-lg font-medium mb-5">Detail Pembayaran Pelunasan</h3>
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
										<?php $lunas = array_filter($payments_by_cabang[$cabang] ?? [], function($p){ return $p->dtl_status == 'PELUNASAN'; }); ?>
										@if (!empty($lunas))
											@foreach ($lunas as $payment)
												<tr>
													<td class="border-b">{{ $payment->cos_kode }}</td>
													<td class="border-b">{{ $payment->cos_nama }}</td>
													<td class="border-b">{{ $payment->cos_alamat }}</td>
													<td class="text-right border-b">
														{{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-"; }}
													</td>
													<td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
													<td class="border-b">{{ $payment->dtl_stt_stor }}</td>
													<td class="border-b">
														<div class="font-medium whitespace-no-wrap">
															<?php echo date('d-m-Y', strtotime($payment->dtl_tanggal)) ?>
														</div>
														<div class="text-gray-600 text-xs whitespace-no-wrap">
															{{ $payment->dtl_jam }}
														</div>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td colspan="7" class="text-center border-b text-gray-500">Tidak ada pembayaran pelunasan dalam periode ini</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<!-- Menunggu Payments Detail -->
						<div class="px-5 sm:px-16 py-10 sm:py-20">
							<h3 class="text-lg font-medium mb-5">Detail Pembayaran Status Menunggu</h3>
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
										<?php $menunggu_list = array_filter($payments_by_cabang[$cabang] ?? [], function($p){ return $p->dtl_stt_stor == 'Menunggu'; }); ?>
										@if (!empty($menunggu_list))
											@foreach ($menunggu_list as $payment)
												<tr>
													<td class="border-b">{{ $payment->cos_kode }}</td>
													<td class="border-b">{{ $payment->cos_nama }}</td>
													<td class="border-b">{{ $payment->cos_alamat }}</td>
													<td class="text-right border-b">
														{{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-"; }}
													</td>
													<td class="border-b">{{ $payment->dtl_status }}</td>
													<td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
													<td class="border-b">{{ $payment->dtl_stt_stor }}</td>
													<td class="border-b">
														<div class="font-medium whitespace-no-wrap">
															<?php echo date('d-m-Y', strtotime($payment->dtl_tanggal)) ?>
														</div>
														<div class="text-gray-600 text-xs whitespace-no-wrap">
															{{ $payment->dtl_jam }}
														</div>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td colspan="8" class="text-center border-b text-gray-500">Tidak ada pembayaran dengan status menunggu dalam periode ini</td>
											</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="flex flex-col lg:flex-row border-b px-5 sm:px-9 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
							<div class="text-center sm:text-left mt-10 sm:mt-0">
								<div class="text-base text-gray-600">Azzahra Computer {{ $cabang }}</div>
								<div class="text-lg text-theme-1 font-medium mt-2">TTD</div>
								<div class="mt-1">Admin</div>
							</div>
							<div class="text-center sm:text-right sm:ml-auto">
								<div class="text-base text-gray-600">Total Keseluruhan</div>
								<div class="text-xl text-theme-1 font-medium mt-2">
									<?php $total_cabang = array_sum(array_column($payments_by_cabang[$cabang] ?? [], 'dtl_jml_bayar')); ?>
									{{ "Rp. ".number_format($total_cabang, 0).",-"; }}
								</div>
								<div class="mt-1 text-xs">Dengan Total Customer - {{ count($payments_by_cabang[$cabang] ?? []); }}</div>
							</div>
						</div>
					</div>
				@endforeach
			@else
				<!-- fallback to original implementation if not all cabang set -->
					        	
				<div class="intro-y inbox box mt-5">
					<div class="flex flex-col lg:flex-row border-b px-5 sm:px-20 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
						<div class="font-semibold text-theme-1 text-3xl">LAPORAN</div>
						<div class="mt-20 lg:mt-0 lg:ml-auto lg:text-right">
							<div class="text-xl text-theme-1 font-medium">Azzahra Computer Tegal</div>
							<div class="mt-1">Tanggal, <?php echo date('d-m-Y',strtotime($tgl_awal)) ?> Sampai <?php echo date('d-m-Y',strtotime($tgl_akhir)) ?></div>
						</div>
					</div>
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
										<div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK BCA</div>
										<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_DP_bca }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_DP_bca, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK BRI</div>
										<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_DP_bri }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_DP_bri, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">DOWN PATMENT BANK MANDIRI</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_DP_mandiri ?? 0 }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_DP_mandiri ?? 0, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">DOWN PATMENT TUNAI</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_DP_tunai }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_DP_tunai, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">PELUNASAN BANK BCA</div>
										<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 0470727705</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_lns_bca }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_lns_bca, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">PELUNASAN BANK BRI</div>
										<div class="text-gray-600 text-xs whitespace-no-wrap">NO Rek. 1390023150083</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_lns_bri }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_lns_bri, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">PELUNASAN BANK MANDIRI</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_lns_mandiri ?? 0 }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_lns_mandiri ?? 0, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">PELUNASAN TUNAI</div>
									</td>
									<td class="text-right border-b w-32">{{ $jml_lns_tunai }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($tot_lns_tunai, 0).",-"; }}
									</td>
								</tr>
								<tr>
									<td class="border-b">
										<div class="font-medium whitespace-no-wrap">STATUS SETOR MENUNGGU</div>
									</td>
									<td class="text-right border-b w-32">{{ $menunggu_count; }}</td>
									<td class="text-right border-b w-32">
										{{ "Rp. ".number_format($menunggu_total, 0).",-"; }}
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- DP Payments Detail -->
					<div class="px-5 sm:px-16 py-10 sm:py-20">
						<h3 class="text-lg font-medium mb-5">Detail Pembayaran DP</h3>
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
									@if ($dp_payments->isNotEmpty())
										@foreach ($dp_payments as $payment)
											<tr>
												<td class="border-b">{{ $payment->cos_kode }}</td>
												<td class="border-b">{{ $payment->cos_nama }}</td>
												<td class="border-b">{{ $payment->cos_alamat }}</td>
												<td class="text-right border-b">
													{{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-"; }}
												</td>
												<td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
												<td class="border-b">{{ $payment->dtl_stt_stor }}</td>
												<td class="border-b">
													<div class="font-medium whitespace-no-wrap">
														<?php echo date('d-m-Y', strtotime($payment->dtl_tanggal)) ?>
													</div>
													<div class="text-gray-600 text-xs whitespace-no-wrap">
														{{ $payment->dtl_jam }}
													</div>
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="7" class="text-center border-b text-gray-500">Tidak ada pembayaran DP dalam periode ini</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					<!-- Pelunasan Payments Detail -->
					<div class="px-5 sm:px-16 py-10 sm:py-20">
						<h3 class="text-lg font-medium mb-5">Detail Pembayaran Pelunasan</h3>
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
									@if ($lunas_payments->isNotEmpty())
										@foreach ($lunas_payments as $payment)
											<tr>
												<td class="border-b">{{ $payment->cos_kode }}</td>
												<td class="border-b">{{ $payment->cos_nama }}</td>
												<td class="border-b">{{ $payment->cos_alamat }}</td>
												<td class="text-right border-b">
													{{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-"; }}
												</td>
												<td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
												<td class="border-b">{{ $payment->dtl_stt_stor }}</td>
												<td class="border-b">
													<div class="font-medium whitespace-no-wrap">
														<?php echo date('d-m-Y', strtotime($payment->dtl_tanggal)) ?>
													</div>
													<div class="text-gray-600 text-xs whitespace-no-wrap">
														{{ $payment->dtl_jam }}
													</div>
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="7" class="text-center border-b text-gray-500">Tidak ada pembayaran pelunasan dalam periode ini</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					<!-- Menunggu Payments Detail -->
					<div class="px-5 sm:px-16 py-10 sm:py-20">
						<h3 class="text-lg font-medium mb-5">Detail Pembayaran Status Menunggu</h3>
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
												<td class="border-b">{{ $payment->cos_alamat }}</td>
												<td class="text-right border-b">
													{{ "Rp. ".number_format($payment->dtl_jml_bayar, 0).",-"; }}
												</td>
												<td class="border-b">{{ $payment->dtl_status }}</td>
												<td class="border-b">{{ $payment->dtl_jenis_bayar }}</td>
												<td class="border-b">{{ $payment->dtl_stt_stor }}</td>
												<td class="border-b">
													<div class="font-medium whitespace-no-wrap">
														<?php echo date('d-m-Y', strtotime($payment->dtl_tanggal)) ?>
													</div>
													<div class="text-gray-600 text-xs whitespace-no-wrap">
														{{ $payment->dtl_jam }}
													</div>
												</td>
											</tr>
										@endforeach
									@else
										<tr>
											<td colspan="7" class="text-center border-b text-gray-500">Tidak ada pembayaran dengan status menunggu dalam periode ini</td>
										</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
					<div class="flex flex-col lg:flex-row border-b px-5 sm:px-9 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
					<div class="text-center sm:text-left mt-10 sm:mt-0">
						<div class="text-base text-gray-600">Azzahra Computer Tegal</div>
						<div class="text-lg text-theme-1 font-medium mt-2">TTD</div>
						<div class="mt-1">Admin</div>
					</div>
					<div class="text-center sm:text-right sm:ml-auto">
						<div class="text-base text-gray-600">Total Keseluruhan</div>
						<div class="text-xl text-theme-1 font-medium mt-2">
							{{ "Rp. ".number_format($tot_tranfer, 0).",-"; }}
						</div>
						<div class="mt-1 text-xs">Dengan Total Customer - {{ $jml_tranfer }}</div>
					</div>
				</div>
					<div class="flex flex-col lg:flex-row border-b px-5 sm:px-9 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
					<div class="text-center sm:text-left mt-10 sm:mt-0">
						<div class="text-base text-gray-600">Bank Transfer</div>
						<div class="text-lg text-theme-1 font-medium mt-2">Yang Belum disetorkan</div>
						<div class="mt-1">Azzahra Computer Tegal</div>
					</div>
					<div class="text-center sm:text-right sm:ml-auto">
						<div class="text-base text-gray-600">Total</div>
						<div class="text-xl text-theme-1 font-medium mt-2">
							{{ "Rp. ".number_format($menunggu_total, 0).",-"; }}
						</div>
						<div class="mt-1 text-xs">Dengan Total Customer - {{ $menunggu_count }}</div>
					</div>
				</div>
					<div class="flex flex-col lg:flex-row border-b px-5 sm:px-9 pt-10 pb-10 sm:pb-20 text-center sm:text-left">
						<div class="text-center sm:text-left mt-10 sm:mt-0">
							<div class="text-base text-gray-600">Pembayaran Tunai</div>
							<div class="text-lg text-theme-1 font-medium mt-2">DP dan Pelunasan</div>
							<div class="mt-1">Azzahra Computer Tegal</div>
						</div>
						<div class="text-center sm:text-right sm:ml-auto">
							<div class="text-base text-gray-600">Total</div>
							<div class="text-xl text-theme-1 font-medium mt-2">
								{{ "Rp. ".number_format($tot_tunai, 0).",-"; }}
							</div>
							<div class="mt-1 text-xs">Dengan Total Customer - {{ $jml_tunai }}</div>
						</div>
					</div>
				
				</div>
			@endif
        </div>
</div>
@endsection
