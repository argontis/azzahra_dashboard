<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KetersediaanSparepart;
use App\Models\Transaksi;
use App\Models\Tindakan;

class KetersediaanSparepartController extends Controller
{
    /**
     * Display a listing of spare parts waiting.
     */
    public function index()
    {
        $spareparts = \Illuminate\Support\Facades\DB::table('ketersediaan_sparepart')
            ->select('ketersediaan_sparepart.*', 'transaksi.*', 'costomer.cos_nama', 'karyawan.kry_nama')
            ->join('transaksi', 'ketersediaan_sparepart.trans_kode', '=', 'transaksi.trans_kode')
            ->join('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->join('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('ketersediaan_sparepart.status', 'Menunggu')
            ->get();
        
        $data = [
            'title' => 'Ketersediaan Sparepart',
            'spareparts' => $spareparts
        ];

        return view('admin.ketersediaan_sparepart', $data);
    }

    /**
     * Mark a spare part as arrived and reset transaction status.
     */
    public function barang_sampai($id)
    {
        $sparepart = KetersediaanSparepart::find($id);

        if ($sparepart) {
            // Update sparepart status
            $sparepart->update(['status' => 'Sampai']);

            // Update transaksi status back to 'Baru'
            Transaksi::where('trans_kode', $sparepart->trans_kode)
                ->update(['trans_status' => 'Baru']);

            // Delete existing tindakan for this transaction to start fresh
            Tindakan::where('trans_kode', $sparepart->trans_kode)->delete();

            return redirect()->route('admin.ketersediaan_sparepart')->with('sukses', 'Barang telah sampai! Order kembali ke konfirmasi.');
        }

        return redirect()->route('admin.ketersediaan_sparepart')->with('gagal', 'Data sparepart tidak ditemukan.');
    }
}
