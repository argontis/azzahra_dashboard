<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Tindakan;
use App\Models\OrderList;
use App\Models\KetersediaanSparepart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{
    public function index()
    {
        $orders_baru = Transaksi::with(['customer'])
            ->where('trans_status', 'Baru')
            ->orderBy('cos_tanggal', 'desc')
            ->get();

        $orders_repairing = Transaksi::with(['customer'])
            ->where('trans_status', 'Diproses')
            ->orderBy('cos_tanggal', 'desc')
            ->get();

        return view('teknisi.dashboard', [
            'title' => 'Dashboard Teknisi',
            'orders_baru' => $orders_baru,
            'orders_repairing' => $orders_repairing
        ]);
    }

    public function input_tindakan($kode)
    {
        $transaksi = Transaksi::with(['customer'])->where('trans_kode', $kode)->firstOrFail();

        return view('teknisi.input_tindakan', [
            'title' => 'Input Tindakan & Sparepart',
            'transaksi' => $transaksi
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
                if (empty($nama)) continue;
                
                $qty = $qtys[$index] ?? 1;
                $subtot = $subtots[$index] ?? 0;
                $ket = $kets[$index] ?? '';

                Tindakan::create([
                    'trans_kode' => $trans_kode,
                    'tdkn_barang' => $nama,
                    'tdkn_qty' => $qty,
                    'tdkn_subtot' => $subtot,
                    'tdkn_tanggal' => now()->toDateString(),
                    'tdkn_jam' => now()->toTimeString()
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
                        'kry_kode' => Auth::id() // Technician doing the work
                    ]
                );
            }

            DB::commit();
            return redirect()->route('teknisi.index')->with('sukses', 'Tindakan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('gagal', 'Gagal menyimpan data tindakan: ' . $e->getMessage());
        }
    }

    public function order_sparepart(Request $request)
    {
        $request->validate([
            'trans_kode' => 'required',
            'barang_nama' => 'required',
            'ketersediaan' => 'required'
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
                    'status' => 'menunggu'
                ]);

                // Update status transaksi ke Konfirmasi
                if ($transaksi) {
                    $transaksi->trans_status = 'Konfirmasi';
                    $transaksi->save();
                    
                    // Clear existing actions if waiting for sparepart
                    Tindakan::where('trans_kode', $trans_kode)->delete();

                    OrderList::where('trans_kode', $trans_kode)->update([
                        'trans_status' => 'waitingOrder'
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
