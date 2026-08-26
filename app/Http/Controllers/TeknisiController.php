<?php

namespace App\Http\Controllers;

use App\Models\KetersediaanSparepart;
use App\Models\OrderList;
use App\Models\Tindakan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeknisiController extends Controller
{
    public function index(Request $request)
    {
        $status_filter = $request->query('status', 'Semua Status');
        $periode_filter = $request->query('periode', 'Semua Waktu');
        $search = $request->query('search');

        $query = Transaksi::with(['customer'])
            ->orderBy('created_at', 'desc');

        // Status filter
        if ($status_filter === 'Order Baru') {
            $query->where('trans_status', 'Baru');
        } elseif ($status_filter === 'Sedang Dikerjakan' || $status_filter === 'Diproses') {
            $query->where('trans_status', 'Diproses');
        } elseif ($status_filter === 'Selesai') {
            $query->whereIn('trans_status', ['Pelunasan', 'Lunas']);
        } else {
            // Default: show orders that require technician attention
            $query->whereIn('trans_status', ['Baru', 'Diproses', 'Konfirmasi', 'Pelunasan']);
        }

        // Periode filter
        if ($periode_filter === 'Hari Ini') {
            $query->whereDate('cos_tanggal', date('Y-m-d'));
        } elseif ($periode_filter === '7 Hari Terakhir') {
            $query->whereDate('cos_tanggal', '>=', date('Y-m-d', strtotime('-7 days')));
        } elseif ($periode_filter === 'Bulan Ini') {
            $query->whereMonth('cos_tanggal', date('m'))->whereYear('cos_tanggal', date('Y'));
        }

        // Search keyword filter
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('trans_kode', 'like', "%{$search}%")
                    ->orWhere('cos_kode', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('cos_nama', 'like', "%{$search}%")
                            ->orWhere('cos_tipe', 'like', "%{$search}%")
                            ->orWhere('cos_model', 'like', "%{$search}%")
                            ->orWhere('cos_no_seri', 'like', "%{$search}%")
                            ->orWhere('cos_hp', 'like', "%{$search}%")
                            ->orWhere('cos_keluhan', 'like', "%{$search}%")
                            ->orWhere('cos_alamat', 'like', "%{$search}%");
                    });
            });
        }

        $orders = $query->paginate(24);
        $total_order_baru = Transaksi::where('trans_status', 'Baru')->count();
        $total_diproses = Transaksi::where('trans_status', 'Diproses')->count();
        $total_selesai = Transaksi::whereIn('trans_status', ['Pelunasan', 'Lunas'])->count();

        return view('teknisi.dashboard', [
            'title' => 'Dashboard Teknisi',
            'orders' => $orders,
            'total_order_baru' => $total_order_baru,
            'total_diproses' => $total_diproses,
            'total_selesai' => $total_selesai,
            'status_filter' => $status_filter,
            'periode_filter' => $periode_filter,
            'search' => $search,
        ]);
    }

    public function input_tindakan($kode)
    {
        $transaksi = Transaksi::with(['customer', 'tindakan'])->where('trans_kode', $kode)->firstOrFail();

        return view('teknisi.input_tindakan', [
            'title' => 'Input Tindakan Perbaikan',
            'transaksi' => $transaksi,
        ]);
    }

    public function save_tindakan(Request $request)
    {
        $request->validate([
            'trans_kode' => 'required',
            'tindakan' => 'required|array',
            'qty' => 'required|array',
            'subtot' => 'required|array',
        ]);

        $trans_kode = $request->input('trans_kode');
        $tindakans = $request->input('tindakan');
        $kets = $request->input('ket', []);
        $qtys = $request->input('qty');
        $subtots = $request->input('subtot');

        DB::beginTransaction();
        try {
            $total_tindakan = 0;

            foreach ($tindakans as $index => $nama) {
                if (empty($nama)) {
                    continue;
                }

                $qty = $qtys[$index] ?? 1;
                $subtot = $subtots[$index] ?? 0;
                $ket = $kets[$index] ?? '';

                Tindakan::create([
                    'trans_kode' => $trans_kode,
                    'tdkn_barang' => $nama,
                    'tdkn_qty' => $qty,
                    'tdkn_subtot' => $subtot,
                    'tdkn_tanggal' => now()->toDateString(),
                    'tdkn_jam' => now()->toTimeString(),
                ]);

                $total_tindakan += $subtot;
            }

            // Update status transaksi
            $transaksi = Transaksi::where('trans_kode', $trans_kode)->first();
            if ($transaksi) {
                $transaksi->trans_status = 'Diproses';
                $transaksi->trans_total = $total_tindakan; // Update base total if needed
                $transaksi->save();

                // Create or update order_list
                OrderList::updateOrCreate(
                    ['trans_kode' => $trans_kode],
                    [
                        'cos_kode' => $transaksi->cos_kode,
                        'trans_status' => 'repairing',
                        'trans_total' => $transaksi->trans_total,
                        'trans_tanggal' => $transaksi->cos_tanggal,
                        'kry_kode' => Auth::id(), // Technician doing the work
                    ]
                );
            }

            DB::commit();

            return redirect()->route('teknisi.index')->with('sukses', 'Tindakan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('gagal', 'Gagal menyimpan data tindakan: '.$e->getMessage());
        }
    }

    public function order_sparepart(Request $request)
    {
        $request->validate([
            'trans_kode' => 'required',
            'barang_nama' => 'required',
            'ketersediaan' => 'required',
        ]);

        $trans_kode = $request->input('trans_kode');
        $ketersediaan = $request->input('ketersediaan');

        if ($ketersediaan == 'tidak_ada') {
            DB::beginTransaction();
            try {
                $transaksi = Transaksi::with('customer')->where('trans_kode', $trans_kode)->first();
                $cos_nama = $transaksi ? ($transaksi->customer->cos_nama ?? '') : '';

                KetersediaanSparepart::create([
                    'trans_kode' => $trans_kode,
                    'cos_nama' => $cos_nama,
                    'barang_nama' => $request->input('barang_nama'),
                    'ketersediaan' => 'tidak_ada',
                    'status' => 'menunggu',
                ]);

                // Update status transaksi ke Konfirmasi
                if ($transaksi) {
                    $transaksi->trans_status = 'Konfirmasi';
                    $transaksi->save();

                    // Clear existing actions if waiting for sparepart
                    Tindakan::where('trans_kode', $trans_kode)->delete();

                    OrderList::where('trans_kode', $trans_kode)->update([
                        'trans_status' => 'waitingOrder',
                    ]);
                }

                DB::commit();

                return redirect()->route('teknisi.index')->with('sukses', 'Order sparepart berhasil diajukan! Menunggu barang sampai.');
            } catch (\Exception $e) {
                DB::rollBack();

                return back()->with('gagal', 'Terjadi kesalahan saat order sparepart.');
            }
        }

        return redirect()->route('teknisi.index')->with('sukses', 'Barang tersedia, silahkan input tindakan.');
    }
}
