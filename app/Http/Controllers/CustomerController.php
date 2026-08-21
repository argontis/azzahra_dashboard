<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = DB::table('costomer')
            ->leftJoin('transaksi', function($join) {
                $join->on('transaksi.cos_kode', '=', 'costomer.id_costomer')
                     ->whereIn('transaksi.trans_kode', function($q) {
                         $q->select(DB::raw('MAX(trans_kode)'))
                           ->from('transaksi')
                           ->groupBy('cos_kode');
                     });
            })
            ->select('costomer.*', 'transaksi.trans_status', 'transaksi.trans_kode');
        
        if ($search) {
            $query->where('cos_nama', 'like', "%{$search}%")
                  ->orWhere('cos_alamat', 'like', "%{$search}%")
                  ->orWhere('cos_hp', 'like', "%{$search}%");
        }
        
        $custom = $query->orderBy('costomer.id_costomer', 'desc')->paginate(10);
        
        return view('customer.index', [
            'title' => 'Customer',
            'custom' => $custom
        ]);
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        
        return view('customer.edit', [
            'title' => 'Edit Customer',
            'customer' => $customer
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $customer->update([
            'cos_nama' => $request->nama,
            'cos_alamat' => $request->alamat,
            'cos_hp' => $request->tlp,
            'cos_tipe' => $request->type,
            'cos_model' => $request->model,
            'cos_no_seri' => $request->seri,
            'cos_asesoris' => $request->asesoris,
            'cos_status' => $request->status,
            'cos_pswd' => $request->pswd,
            'cos_keluhan' => $request->keluhan,
            'cos_keterangan' => $request->ket,
        ]);

        return redirect('/Customer')->with('sukses', 'DI SIMPAN');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        
        return redirect('/Customer')->with('sukses', 'DI HAPUS');
    }

    public function histori($kode_transaksi)
    {
        $transaksi = Transaksi::with('customer')->where('trans_kode', $kode_transaksi)->firstOrFail();
        $tindakan = \App\Models\Tindakan::where('trans_kode', $kode_transaksi)->get();
        $pembayaran = \App\Models\TransaksiDetail::where('trans_kode', $kode_transaksi)->get();
        
        // Match legacy 'proses' array structure
        $proses = array_merge($transaksi->toArray(), $transaksi->customer->toArray());
        
        return view('customer.histori', [
            'title' => 'Customer',
            'proses' => $proses,
            'transaksi' => $transaksi,
            'tindakan' => $tindakan,
            'pembayaran' => $pembayaran
        ]);
    }

    public function export_pdf()
    {
        $customers = DB::table('costomer')
            ->leftJoin('transaksi', function($join) {
                $join->on('transaksi.cos_kode', '=', 'costomer.id_costomer')
                     ->whereIn('transaksi.trans_kode', function($q) {
                         $q->select(DB::raw('MAX(trans_kode)'))
                           ->from('transaksi')
                           ->groupBy('cos_kode');
                     });
            })
            ->select('costomer.*', 'transaksi.trans_status', 'transaksi.trans_kode')
            ->orderBy('costomer.id_costomer', 'desc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('customer.export_pdf', ['customers' => $customers])
            ->setPaper('a4', 'landscape');

        return $pdf->download('data_customer_' . date('Y-m-d') . '.pdf');
    }
}
