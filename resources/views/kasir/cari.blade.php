@extends('layouts.app')

@section('content')
<header class="page-header mb-5">
    <div class="header-title">
        <h1><i data-feather="file-text" class="w-6 h-6 inline-block mr-2"></i>Detail Transaksi</h1>
        <p>Kode: {{ $trans->trans_kode }}</p>
    </div>
    <div class="header-section mt-5 flex gap-2">
        <a href="{{ route('kasir.index') }}" class="button bg-gray-500 text-white">Kembali</a>
    </div>
</header>

<div class="content mt-5">
    @if(session('sukses'))
    <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">{{ session('sukses') }}</div>
    @endif
    
    @if(session('gagal'))
    <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded mb-4">{{ session('gagal') }}</div>
    @endif

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-8">
            @php
                $customer = $trans->customer;
                $isPriority = $customer && (($customer->cos_tier === 'prioritas') || ($customer->cos_score >= 5) || ($customer->total_transaksi >= 5));
                $isLoyal = $customer && (($customer->cos_tier === 'loyal') || ($customer->cos_score >= 3 && !$isPriority));
                $score = $customer->cos_score ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
                $totalTx = $customer->total_transaksi ?? ($isPriority ? 5 : ($isLoyal ? 3 : 1));
            @endphp

            @if($isPriority)
                <div class="alert alert-warning bg-amber-500 text-white p-4 rounded-lg mb-4 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">👑</span>
                        <div>
                            <div class="font-bold text-sm">PELANGGAN PRIORITAS (VIP) - Skor {{ $score }}</div>
                            <div class="text-xs text-amber-100">Customer memiliki {{ $totalTx }} riwayat transaksi & berhak atas potongan diskon loyalitas up-to 25% - 50%.</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="box p-5">
                <div class="flex items-center justify-between border-b pb-2 mb-4">
                    <h2 class="font-bold text-lg">Informasi Customer</h2>
                    @if($isPriority)
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                            👑 Prioritas VIP (Skor 5)
                        </span>
                    @elseif($isLoyal)
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
                            ⭐ Loyal (Skor {{ $score }})
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Reguler (Skor {{ $score }})
                        </span>
                    @endif
                </div>
                <table class="table w-full mb-6">
                    <tr><td class="w-1/4 font-semibold">Nama</td><td>: {{ $trans->customer->cos_nama ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">No. HP</td><td>: {{ $trans->customer->cos_hp ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">Alamat</td><td>: {{ $trans->customer->cos_alamat ?? '-' }}</td></tr>
                    <tr><td class="font-semibold">Total Transaksi</td><td>: {{ $totalTx }} kali</td></tr>
                </table>

                <h2 class="font-bold text-lg border-b pb-2 mb-4">Rincian Tindakan / Servis</h2>
                <table class="table table-report w-full mb-6">
                    <thead>
                        <tr>
                            <th>BARANG / JASA</th>
                            <th class="text-right">HARGA</th>
                            <th class="text-center">QTY</th>
                            <th class="text-right">SUBTOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tindakan as $item)
                        <tr>
                            <td>{{ $item->tdkn_barang }}</td>
                            <td class="text-right">Rp {{ number_format($item->tdkn_harga, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->tdkn_qty }}</td>
                            <td class="text-right font-medium">Rp {{ number_format($item->tdkn_subtot, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Belum ada rincian.</td></tr>
                        @endforelse
                        <tr>
                            <td colspan="3" class="text-right font-bold">TOTAL KESELURUHAN</td>
                            <td class="text-right font-bold text-theme-6">Rp {{ number_format($trans->trans_total, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>

                <h2 class="font-bold text-lg border-b pb-2 mb-4">Riwayat Pembayaran</h2>
                <table class="table table-report w-full">
                    <thead>
                        <tr>
                            <th>TANGGAL</th>
                            <th>STATUS</th>
                            <th>METODE</th>
                            <th class="text-right">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalBayar = 0; @endphp
                        @forelse($bayar as $b)
                        @php $totalBayar += $b->dtl_jml_bayar; @endphp
                        <tr>
                            <td>{{ $b->dtl_tanggal }} {{ $b->dtl_jam }}</td>
                            <td><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">{{ $b->dtl_status }}</span></td>
                            <td>{{ $b->dtl_jenis_bayar }} {{ $b->dtl_bank != '-' ? '('.$b->dtl_bank.')' : '' }}</td>
                            <td class="text-right">Rp {{ number_format($b->dtl_jml_bayar, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">Belum ada pembayaran.</td></tr>
                        @endforelse
                        <tr>
                            <td colspan="3" class="text-right font-bold">TOTAL TERBAYAR</td>
                            <td class="text-right font-bold text-theme-9">Rp {{ number_format($totalBayar, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right font-bold">SISA TAGIHAN</td>
                            <td class="text-right font-bold text-theme-6">Rp {{ number_format(max(0, $trans->trans_total - $totalBayar), 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4">
            <div class="box p-5 mb-5">
                <h2 class="font-bold text-lg border-b pb-2 mb-4">Aksi Pembayaran</h2>
                
                @if($trans->trans_status == 'Lunas')
                    <div class="alert alert-success text-center">Transaksi ini sudah <strong>LUNAS</strong>.</div>
                @else
                    <!-- Form Bayar DP -->
                    <div class="mb-5 border border-gray-200 rounded p-4">
                        <h3 class="font-semibold mb-3">Bayar DP</h3>
                        <form action="{{ route('kasir.save_dp') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kode" value="{{ $trans->trans_kode }}">
                            <div class="mb-3">
                                <label>Jumlah DP (Rp)</label>
                                <input type="number" name="dp" class="input w-full border mt-1" required>
                            </div>
                            <div class="mb-3">
                                <label>Metode Pembayaran</label>
                                <select name="jenis_bayar" class="input w-full border mt-1" required>
                                    <option value="TUNAI">Tunai</option>
                                    <option value="TRANSFER">Transfer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Bank (Jika Transfer)</label>
                                <select name="bank" class="input w-full border mt-1">
                                    <option value="-">-</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BRI">BRI</option>
                                    <option value="MANDIRI">MANDIRI</option>
                                </select>
                            </div>
                            <button type="submit" class="button bg-theme-1 text-white w-full">Simpan DP</button>
                        </form>
                    </div>

                    <!-- Form Pelunasan -->
                    <div class="border border-gray-200 rounded p-4">
                        <h3 class="font-semibold mb-3">Pelunasan</h3>
                        <form action="{{ route('kasir.pelunasan') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kode" value="{{ $trans->trans_kode }}">
                            <div class="mb-3">
                                <label>Sisa Pelunasan (Rp)</label>
                                <input type="number" name="lunas" class="input w-full border mt-1" value="{{ max(0, $trans->trans_total - $totalBayar) }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label>Metode Pembayaran</label>
                                <select name="jenis_bayar" class="input w-full border mt-1" required>
                                    <option value="TUNAI">Tunai</option>
                                    <option value="TRANSFER">Transfer</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Bank (Jika Transfer)</label>
                                <select name="bank" class="input w-full border mt-1">
                                    <option value="-">-</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BRI">BRI</option>
                                    <option value="MANDIRI">MANDIRI</option>
                                </select>
                            </div>
                            <button type="submit" class="button bg-theme-6 text-white w-full" onclick="return confirm('Yakin ingin melunasi transaksi ini?');">Lunasi Transaksi</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
