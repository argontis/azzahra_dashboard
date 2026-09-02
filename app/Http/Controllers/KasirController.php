<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Tindakan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Transaksi::with(['customer', 'karyawan'])
            ->where('trans_status', '!=', 'Lunas');

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('cos_nama', 'like', "%{$search}%")
                    ->orWhere('cos_alamat', 'like', "%{$search}%")
                    ->orWhere('cos_hp', 'like', "%{$search}%");
            });
        }

        // Order by latest customer date
        $transaksis = $query->orderBy('trans_kode', 'desc')->paginate(25);

        return view('kasir.customer', [
            'title' => 'Kasir - Customer',
            'transaksis' => $transaksis,
        ]);
    }

    public function cari($kode)
    {
        $transaksi = Transaksi::with(['customer', 'karyawan'])
            ->where('trans_kode', $kode)
            ->firstOrFail();

        $tindakan = Tindakan::where('trans_kode', $kode)->get();
        $bayar = TransaksiDetail::where('trans_kode', $kode)->get();

        return view('kasir.cari', [
            'title' => 'Pembayaran',
            'trans' => $transaksi,
            'tindakan' => $tindakan,
            'bayar' => $bayar,
        ]);
    }

    public function pelunasan(Request $request)
    {
        $kode = $request->input('kode');
        $jenis_bayar = $request->input('jenis_bayar');
        $bank = ($jenis_bayar == 'TUNAI') ? '-' : $request->input('bank');

        $transaksi = Transaksi::where('trans_kode', $kode)->firstOrFail();

        if ($transaksi->trans_status == 'Lunas') {
            return redirect()
                ->route('kasir.cari', $kode)
                ->with('gagal', 'Customer ini sudah melakukan pelunasan');
        }

        DB::transaction(function () use (
            $kode,
            $jenis_bayar,
            $bank,
            $request,
            $transaksi
        ) {
            TransaksiDetail::create([
                'trans_kode' => $kode,
                'kry_kode' => auth()->user()->kry_kode ?? null,
                'dtl_jml_bayar' => str_replace('.', '', $request->input('lunas')),
                'dtl_jenis_bayar' => $jenis_bayar,
                'dtl_bank' => $bank,
                'dtl_status' => 'PELUNASAN',
                'dtl_tanggal' => now()->toDateString(),
                'dtl_jam' => now()->toTimeString(),
                'dtl_stt_stor' => 'Disetorkan',
            ]);

            $transaksi->update([
                'trans_status' => 'Lunas',
            ]);

            // Recalculate customer score automatically
            if ($transaksi->customer) {
                $transaksi->customer->recalculateScore();
            }
        });

        return redirect()
            ->route('kasir.cari', $kode)
            ->with('sukses', 'DI LUNASI');
    }

    public function save_dp(Request $request)
    {
        $kode = $request->input('kode');
        $jenis_bayar = $request->input('jenis_bayar');
        $bank = ($jenis_bayar == 'TUNAI') ? '-' : $request->input('bank');

        DB::transaction(function () use (
            $kode,
            $jenis_bayar,
            $bank,
            $request
        ) {
            TransaksiDetail::create([
                'trans_kode' => $kode,
                'kry_kode' => auth()->user()->kry_kode ?? null,
                'dtl_jml_bayar' => str_replace('.', '', $request->input('dp')),
                'dtl_jenis_bayar' => $jenis_bayar,
                'dtl_bank' => $bank,
                'dtl_status' => 'DP',
                'dtl_tanggal' => now()->toDateString(),
                'dtl_jam' => now()->toTimeString(),
                'dtl_stt_stor' => 'Disetorkan',
            ]);

            Transaksi::where('trans_kode', $kode)
                ->update([
                    'trans_status' => 'Pelunasan',
                ]);
        });

        return redirect()
            ->route('kasir.cari', $kode)
            ->with('sukses', 'DP DI SIMPAN');
    }

    public function pembayaran(Request $request, $filter = null)
    {
        $search = $request->input('search');

        $query = TransaksiDetail::with([
            'transaksi.customer',
            'transaksi.karyawan',
        ])
            ->orderBy('dtl_tanggal', 'desc')
            ->orderBy('dtl_jam', 'desc');

        if ($filter == 'dp') {
            $query->where('dtl_status', 'DP');
        } elseif ($filter == 'lunas') {
            $query->where('dtl_status', 'PELUNASAN');
        }

        if ($search) {
            $query->whereHas('transaksi.customer', function ($q) use ($search) {
                $q->where('cos_nama', 'like', "%{$search}%")
                    ->orWhere('cos_kode', 'like', "%{$search}%");
            });
        }

        $pembayarans = $query->paginate(25);

        $dpCount = TransaksiDetail::where('dtl_status', 'DP')->count();
        $lunasCount = TransaksiDetail::where('dtl_status', 'PELUNASAN')->count();
        $totalCount = $dpCount + $lunasCount;

        return view('kasir.pembayaran', [
            'title' => 'Pembayaran',
            'pembayarans' => $pembayarans,
            'filter' => $filter,
            'dpCount' => $dpCount,
            'lunasCount' => $lunasCount,
            'totalCount' => $totalCount,
        ]);
    }

    public function laporan()
    {
        $date = now()->toDateString();

        // DP & Pelunasan today
        $payments = TransaksiDetail::with([
            'transaksi.customer',
        ])
            ->whereDate('dtl_tanggal', $date)
            ->get();

        $dp_total = $payments
            ->where('dtl_status', 'DP')
            ->sum('dtl_jml_bayar');

        $lunas_total = $payments
            ->where('dtl_status', 'PELUNASAN')
            ->sum('dtl_jml_bayar');

        $total_all = $dp_total + $lunas_total;

        return view('kasir.laporan', [
            'title' => 'Laporan Kasir Hari Ini',
            'date' => $date,
            'dp_total' => $dp_total,
            'lunas_total' => $lunas_total,
            'total_all' => $total_all,
            'payments' => $payments,
        ]);
    }
}
