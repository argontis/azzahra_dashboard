<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderList;
use App\Models\Tindakan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class QuickServiceController extends Controller
{
    public function index()
    {
        $stats = [
            'baru' => Transaksi::where('trans_status', 'Baru')->count(), // Usually QuickService skips this
            'proses' => Transaksi::where('trans_status', 'Diproses')->count(),
            'konfirmasi' => Transaksi::where('trans_status', 'Konfirmasi')->count(),
            'pelunasan' => Transaksi::where('trans_status', 'Pelunasan')->count(),
            'lunas' => Transaksi::where('trans_status', 'Lunas')->count(),
        ];

        return view('quickservice.dashboard', [
            'title' => 'Quick Service Dashboard',
            'stats' => $stats,
        ]);
    }

    public function antrean($status = 'baru')
    {
        $status_map = [
            'baru' => 'Baru',
            'proses' => 'Diproses',
            'konfirmasi' => 'Konfirmasi',
            'pelunasan' => 'Pelunasan',
            'lunas' => 'Lunas',
        ];

        if (! array_key_exists($status, $status_map)) {
            $status = 'baru';
        }

        $query = Transaksi::with(['customer', 'karyawan']);

        if ($status === 'baru') {
            $query->whereIn('trans_status', ['Baru', 'Pelunasan']);
        } else {
            $query->where('trans_status', $status_map[$status]);
        }

        // Order by latest customer date (assuming trans_tanggal or cos_tanggal logic)
        $transaksis = $query->orderBy('cos_tanggal', 'desc')
            ->orderBy('trans_kode', 'desc')
            ->paginate(25);

        return view('quickservice.antrean', [
            'title' => 'Quick Service',
            'transaksis' => $transaksis,
            'current_status' => $status,
        ]);
    }

    public function create()
    {
        return view('quickservice.form_penerimaan', [
            'title' => 'Quick Service Baru',
        ]);
    }

    public function save_trans(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tlp' => 'required',
            'type' => 'required',
            'keluhan' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // 1. Generate id_costomer
            $date_prefix = 'TTS'.date('ymd');
            $latest_customer = Customer::where('id_costomer', 'like', $date_prefix.'%')
                ->orderBy('id_costomer', 'desc')
                ->first();

            $next_num = 1;
            if ($latest_customer) {
                $last_num = (int) substr($latest_customer->id_costomer, -3);
                $next_num = $last_num + 1;
            }
            $id_costomer = $date_prefix.str_pad($next_num, 3, '0', STR_PAD_LEFT);

            // 2. Insert Customer
            $password = $request->pswd_type === 'text' ? $request->pswd : ($request->pswd_type === 'pattern_desc' ? $request->pswd_desc : '');

            Customer::create([
                'id_costomer' => $id_costomer,
                'cos_nama' => $request->nama,
                'username' => $id_costomer,
                'password' => Hash::make($id_costomer),
                'cos_tgl_lahir' => $request->cos_tgl_lahir,
                'cos_alamat' => $request->alamat,
                'cos_hp' => $request->tlp,
                'cos_tipe' => $request->type,
                'cos_model' => $request->model,
                'cos_no_seri' => $request->seri,
                'cos_asesoris' => $request->asesoris,
                'cos_status' => $request->status,
                'cos_pswd_type' => $request->pswd_type,
                'cos_pswd' => $password,
                'cos_pswd_canvas' => $request->pswd_canvas,
                'cos_keluhan' => $request->keluhan,
                'cos_keterangan' => $request->ket,
                'cos_tanggal' => date('Y-m-d'),
                'cos_jam' => date('H:i:s'),
                'cos_poin' => 0,
            ]);

            // 3. Generate trans_kode
            $date_prefix_trans = 'TR'.date('dmy');
            $latest_trans = Transaksi::where('trans_kode', 'like', $date_prefix_trans.'%')
                ->orderBy('trans_kode', 'desc')
                ->first();

            $next_trans_num = 1;
            if ($latest_trans) {
                $last_trans_num = (int) substr($latest_trans->trans_kode, -3);
                $next_trans_num = $last_trans_num + 1;
            }
            $trans_kode = $date_prefix_trans.str_pad($next_trans_num, 3, '0', STR_PAD_LEFT);

            $is_quick_service = true; // In QS, it's always quick service

            // 4. Insert Transaksi
            Transaksi::create([
                'trans_kode' => $trans_kode,
                'cos_kode' => $id_costomer,
                'kry_kode' => auth()->user()->kry_kode ?? null,
                'trans_total' => 0,
                'trans_discount' => 0,
                'trans_status' => 'Baru',
                'cos_tanggal' => date('Y-m-d'),
                'cos_jam' => date('H:i:s'),
                'trans_tanggal' => date('Y-m-d'),
            ]);

            // 5. If quick service, add default tindakan
            if ($is_quick_service) {
                Tindakan::create([
                    'trans_kode' => $trans_kode,
                    'tdkn_barang' => 'SERVICE (QUICK)',
                    'tdkn_qty' => 1,
                    'tdkn_subtot' => 0,
                    'tdkn_tanggal' => date('Y-m-d'),
                    'tdkn_jam' => date('H:i:s'),
                ]);
            }

            // 6. Insert OrderList
            OrderList::create([
                'trans_kode' => $trans_kode,
                'cos_kode' => $id_costomer,
                'trans_total' => 0,
                'trans_discount' => 0,
                'trans_tanggal' => date('Y-m-d'),
                'trans_status' => 'QS', // In QS, status is QS
                'merek' => $request->type ?? '',
                'device' => $request->device ?? '',
                'status_garansi' => $request->status ?? '',
                'seri' => $request->seri ?? '',
                'ket_keluhan' => $request->keluhan ?? '',
                'email' => 'example@gmail.com',
                'alamat' => $request->alamat ?? '',
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'trans_kode' => $trans_kode,
                    'id_costomer' => $id_costomer,
                ]);
            }

            return redirect()->route('quickservice.antrean', 'baru')->with('sukses', 'Pelanggan Quick Service berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: '.$e->getMessage(),
                ], 400);
            }

            return back()->with('gagal', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
