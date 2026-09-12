<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Tindakan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tier = $request->input('tier', 'all');

        $latestTransaksi = DB::table('transaksi')
            ->select('cos_kode', DB::raw('MAX(trans_kode) as latest_trans_kode'))
            ->groupBy('cos_kode');

        $query = DB::table('costomer')
            ->leftJoinSub($latestTransaksi, 'latest_t', function ($join) {
                $join->on('costomer.id_costomer', '=', 'latest_t.cos_kode');
            })
            ->leftJoin('transaksi', 'transaksi.trans_kode', '=', 'latest_t.latest_trans_kode')
            ->select('costomer.*', 'transaksi.trans_status', 'transaksi.trans_kode');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('costomer.id_costomer', 'like', "%{$search}%")
                    ->orWhere('costomer.cos_nama', 'like', "%{$search}%")
                    ->orWhere('costomer.cos_alamat', 'like', "%{$search}%")
                    ->orWhere('costomer.cos_hp', 'like', "%{$search}%")
                    ->orWhere('transaksi.trans_kode', 'like', "%{$search}%");
            });
        }

        if ($tier === 'prioritas') {
            $query->where(function ($q) {
                $q->where('costomer.cos_tier', 'prioritas')
                    ->orWhere('costomer.cos_score', '>=', 5)
                    ->orWhere('costomer.total_transaksi', '>=', 5);
            });
        } elseif ($tier === 'loyal') {
            $query->where('costomer.cos_tier', 'loyal');
        } elseif ($tier === 'reguler') {
            $query->where(function ($q) {
                $q->where('costomer.cos_tier', 'reguler')
                    ->orWhereNull('costomer.cos_tier');
            });
        }

        $custom = $query->orderBy('costomer.id_costomer', 'desc')->paginate(10)->withQueryString();

        $tier_counts = [
            'all' => DB::table('costomer')->count(),
            'prioritas' => DB::table('costomer')->where('cos_tier', 'prioritas')->orWhere('cos_score', '>=', 5)->count(),
            'loyal' => DB::table('costomer')->where('cos_tier', 'loyal')->count(),
            'reguler' => DB::table('costomer')->where('cos_tier', 'reguler')->orWhereNull('cos_tier')->count(),
        ];

        return view('customer.index', [
            'title' => 'Customer',
            'custom' => $custom,
            'current_tier' => $tier,
            'tier_counts' => $tier_counts,
        ]);
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customer.edit', [
            'title' => 'Edit Customer',
            'customer' => $customer,
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
        $tindakan = Tindakan::where('trans_kode', $kode_transaksi)->get();
        $pembayaran = TransaksiDetail::where('trans_kode', $kode_transaksi)->get();

        // Match legacy 'proses' array structure
        $proses = array_merge($transaksi->toArray(), $transaksi->customer->toArray());

        return view('customer.histori', [
            'title' => 'Customer',
            'proses' => $proses,
            'transaksi' => $transaksi,
            'tindakan' => $tindakan,
            'pembayaran' => $pembayaran,
        ]);
    }

    public function export_pdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        set_time_limit(120);

        $limit = min(max((int) $request->input('limit', 50), 1), 100);
        $search = $request->input('search');

        $latestTransaksi = DB::table('transaksi')
            ->select('cos_kode', DB::raw('MAX(trans_kode) as latest_trans_kode'))
            ->groupBy('cos_kode');

        $query = DB::table('costomer')
            ->leftJoinSub($latestTransaksi, 'latest_t', function ($join) {
                $join->on('costomer.id_costomer', '=', 'latest_t.cos_kode');
            })
            ->leftJoin('transaksi', 'transaksi.trans_kode', '=', 'latest_t.latest_trans_kode')
            ->select('costomer.*', 'transaksi.trans_status', 'transaksi.trans_kode');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('costomer.id_costomer', 'like', "%{$search}%")
                    ->orWhere('costomer.cos_nama', 'like', "%{$search}%")
                    ->orWhere('costomer.cos_alamat', 'like', "%{$search}%")
                    ->orWhere('costomer.cos_hp', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('costomer.id_costomer', 'desc')
            ->limit($limit)
            ->get();

        $pdf = Pdf::loadView('customer.export_pdf', ['customers' => $customers])
            ->setPaper('a4', 'landscape');

        return $pdf->download('data_customer_'.date('Y-m-d').'.pdf');
    }
}
