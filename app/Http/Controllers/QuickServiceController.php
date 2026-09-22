<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderList;
use App\Models\Tindakan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
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

    public function antrean(Request $request, $status = 'baru')
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

        $search = $request->input('search');

        $query = Transaksi::with(['customer', 'karyawan']);

        if ($status === 'baru') {
            $query->whereIn('trans_status', ['Baru', 'Pelunasan']);
        } else {
            $query->where('trans_status', $status_map[$status]);
        }

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->where('trans_kode', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($q) use ($search) {
                        $q->where('cos_nama', 'like', "%{$search}%")
                            ->orWhere('cos_hp', 'like', "%{$search}%")
                            ->orWhere('cos_alamat', 'like', "%{$search}%");
                    });
            });
        }

        // Order by latest transaction date
        $transaksis = $query->orderBy('trans_tanggal', 'desc')
            ->orderBy('trans_kode', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('quickservice.antrean', [
            'title' => 'Quick Service',
            'transaksis' => $transaksis,
            'current_status' => $status,
        ]);
    }

    public function create()
    {
        return redirect()->route('quickservice.antrean', ['status' => 'baru', 'open_modal' => 1]);
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
            $password = in_array($request->pswd_type, ['text', 'pin']) ? ($request->pswd ?? '') : ($request->pswd_type === 'pattern_desc' ? ($request->pswd_desc ?? '') : '');

            Customer::create([
                'id_costomer' => $id_costomer,
                'cos_nama' => $request->nama,
                'username' => $request->tlp ?? $request->nama,
                'password' => Hash::make($request->tlp ?? '123456'),
                'cos_tgl_lahir' => $request->cos_tgl_lahir,
                'cos_alamat' => $request->alamat ?? '-',
                'cos_cabang' => $request->cabang ?? 'Tegal',
                'cos_device' => $request->device ?? '-',
                'cos_hp' => $request->tlp,
                'cos_tipe' => $request->type ?? '-',
                'cos_model' => $request->model ?? '-',
                'cos_no_seri' => $request->seri ?? '-',
                'cos_asesoris' => $request->asesoris ?? '-',
                'cos_status' => $request->status ?? 'OOW',
                'cos_pswd_type' => $request->pswd_type ?? 'text',
                'cos_pswd' => $password,
                'cos_pswd_canvas' => $request->pswd_canvas,
                'cos_keluhan' => $request->keluhan ?? '-',
                'cos_keterangan' => $request->ket ?? '-',
                'cos_tanggal' => date('Y-m-d'),
                'cos_jam' => date('H:i:s'),
                'cos_poin' => 0,
            ]);

            // 3. Insert Transaksi
            $transaksi = Transaksi::create([
                'cos_kode' => $id_costomer,
                'kry_kode' => auth()->user()->kry_kode ?? null,
                'trans_total' => 0,
                'trans_discount' => 0,
                'trans_status' => 'Pelunasan',
                'trans_tanggal' => date('Y-m-d'),
                'cos_tanggal' => date('Y-m-d'),
                'cos_jam' => date('H:i:s'),
            ]);

            $trans_kode = (string) $transaksi->trans_kode;

            // 4. Insert default tindakan for Quick Service
            Tindakan::create([
                'trans_kode' => $trans_kode,
                'tdkn_barang' => 'QUICK SERVICE',
                'tdkn_harga' => 0,
                'tdkn_qty' => 1,
                'tdkn_subtot' => 0,
                'tdkn_ket' => $request->ket ?? 'Quick Service',
                'tdkn_tanggal' => date('Y-m-d'),
                'tdkn_jam' => date('H:i:s'),
            ]);

            // 6. Insert OrderList
            OrderList::create([
                'trans_kode' => $trans_kode,
                'cos_kode' => $id_costomer,
                'kry_kode' => auth()->user()->kry_kode ?? null,
                'trans_total' => 0,
                'trans_discount' => 0,
                'trans_tanggal' => date('Y-m-d'),
                'trans_status' => 'itemSubmitted',
                'merek' => $request->type ?? '-',
                'device' => $request->device ?? '-',
                'status_garansi' => $request->status ?? 'OOW',
                'seri' => $request->seri ?? '-',
                'ket_keluhan' => $request->keluhan ?? '-',
                'email' => 'example@gmail.com',
                'alamat' => $request->alamat ?? 'Tegal',
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
