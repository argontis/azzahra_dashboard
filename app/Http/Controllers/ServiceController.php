<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderList;
use App\Models\Tindakan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ServiceController extends Controller
{
    public function index()
    {
        $stats = [
            'baru' => Transaksi::where('trans_status', 'Baru')->count(),
            'proses' => Transaksi::where('trans_status', 'Diproses')->count(),
            'konfirmasi' => Transaksi::where('trans_status', 'Konfirmasi')->count(),
            'pelunasan' => Transaksi::where('trans_status', 'Pelunasan')->count(),
            'lunas' => Transaksi::where('trans_status', 'Lunas')->count(),
        ];

        return view('service.dashboard', [
            'title' => 'Dashboard Customer Service',
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

        $transaksis = Transaksi::with(['customer', 'karyawan'])
            ->select('transaksi.*')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->where('trans_status', $status_map[$status])
            ->orderByRaw("CASE WHEN costomer.cos_tier = 'prioritas' OR costomer.cos_score >= 5 THEN 0 WHEN costomer.cos_tier = 'loyal' OR costomer.cos_score >= 3 THEN 1 ELSE 2 END ASC")
            ->orderBy('transaksi.cos_tanggal', 'desc')
            ->orderBy('transaksi.trans_kode', 'desc')
            ->paginate(25);

        return view('service.antrean', [
            'title' => 'Antrean - '.ucfirst($status),
            'transaksis' => $transaksis,
            'current_status' => $status,
        ]);
    }

    public function create()
    {
        return view('service.form_penerimaan', [
            'title' => 'Penerimaan Servis Baru',
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
                'cos_cabang' => $request->cabang,
                'cos_device' => $request->device,
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

            $is_quick_service = $request->has('is_quick_service');

            // 4. Insert Transaksi
            Transaksi::create([
                'trans_kode' => $trans_kode,
                'cos_kode' => $id_costomer,
                'kry_kode' => auth()->user()->kry_kode ?? null,
                'trans_total' => 0,
                'trans_discount' => 0,
                'trans_status' => $is_quick_service ? 'Pelunasan' : 'Baru',
                'cos_tanggal' => date('Y-m-d'),
                'cos_jam' => date('H:i:s'),
                'trans_tanggal' => date('Y-m-d'),
            ]);

            // 5. If quick service, add default tindakan
            if ($is_quick_service) {
                Tindakan::create([
                    'trans_kode' => $trans_kode,
                    'tdkn_barang' => 'SERVICE',
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
                'trans_status' => 'itemSubmitted',
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

            return redirect()->route('service.antrean', 'baru')->with('sukses', 'Pelanggan berhasil ditambahkan');

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

    public function batal_transaksi($kode)
    {
        try {
            DB::beginTransaction();

            // Set all existing tindakan subtot to 0
            Tindakan::where('trans_kode', $kode)->update(['tdkn_subtot' => 0]);

            // Add new pembatalan tindakan
            Tindakan::create([
                'trans_kode' => $kode,
                'tdkn_barang' => 'PENGECEKAN UNIT',
                'tdkn_qty' => 1,
                'tdkn_subtot' => 50000,
                'tdkn_tanggal' => date('Y-m-d'),
                'tdkn_jam' => date('H:i:s'),
            ]);

            Transaksi::where('trans_kode', $kode)->update([
                'trans_total' => 50000,
                'trans_discount' => 0,
                'trans_status' => 'Cencel',
            ]);

            DB::commit();

            return redirect()->route('service.antrean', 'konfirmasi')->with('sukses', 'Transaksi dibatalkan. Silahkan ke Kasir.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('gagal', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function return_pembayaran($kode)
    {
        try {
            DB::beginTransaction();

            Tindakan::where('trans_kode', $kode)->update(['tdkn_subtot' => 0]);

            Tindakan::create([
                'trans_kode' => $kode,
                'tdkn_barang' => 'PENGECEKAN UNIT',
                'tdkn_qty' => 1,
                'tdkn_subtot' => 50000,
                'tdkn_tanggal' => date('Y-m-d'),
                'tdkn_jam' => date('H:i:s'),
            ]);

            Transaksi::where('trans_kode', $kode)->update([
                'trans_total' => 50000,
                'trans_discount' => 0,
                'trans_status' => 'Return',
            ]);

            $transaksi = Transaksi::where('trans_kode', $kode)->first();
            if ($transaksi) {
                OrderList::where('cos_kode', $transaksi->cos_kode)->update(['trans_status' => 'Refund']);
            }

            DB::commit();

            return redirect()->route('service.antrean', 'pelunasan')->with('sukses', 'Return pembayaran berhasil. Silahkan ke Kasir.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('gagal', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function pembayaran(Request $request, $filter = null)
    {
        // Anda dapat menyalin logika dari KasirController::pembayaran()
        // atau menyesuaikannya dengan kebutuhan Customer Service di sini.

        return view('service.pembayaran', [
            'title' => 'Pembayaran',
            'filter' => $filter,
        ]);
    }

    public function laporan(Request $request)
    {
        // Anda dapat menambahkan query untuk mengambil data laporan CS di sini nantinya

        return view('service.laporan', [
            'title' => 'Laporan',
        ]);
    }

    public function proses($id)
    {
        $transaksi = Transaksi::with(['customer', 'tindakan'])
            ->where('trans_kode', $id)
            ->orWhere('cos_kode', $id)
            ->orWhere('id', $id)
            ->first();

        if (! $transaksi) {
            $customer = Customer::where('id_costomer', $id)->first();
            if ($customer) {
                $transaksi = Transaksi::with(['customer', 'tindakan'])
                    ->where('cos_kode', $customer->id_costomer)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        }

        if (! $transaksi) {
            return back()->with('gagal', 'Data customer / transaksi tidak ditemukan');
        }

        $customer = $transaksi->customer;
        $tindakans = Tindakan::where('trans_kode', $transaksi->trans_kode)->get();

        return view('service.proses', [
            'title' => 'Input Proses - '.($customer->cos_nama ?? 'Customer'),
            'transaksi' => $transaksi,
            'customer' => $customer,
            'tindakans' => $tindakans,
            'current_status' => strtolower($transaksi->trans_status ?? 'proses'),
        ]);
    }

    public function save_tindakan(Request $request, $id)
    {
        $request->validate([
            'tindakan' => 'required',
        ]);

        $transaksi = Transaksi::where('trans_kode', $id)->orWhere('cos_kode', $id)->first();
        if (! $transaksi) {
            $transaksi = Transaksi::whereHas('customer', function ($q) use ($id) {
                $q->where('id_costomer', $id);
            })->firstOrFail();
        }

        $qty = $request->input('qty', 1);
        $harga = $request->input('biaya', 0);
        $keterangan = $request->input('keterangan', '');
        $tindakan_nama = $request->input('tindakan');

        // Include detail in tdkn_barang if provided
        $tdkn_barang = $tindakan_nama;
        if (! empty($keterangan)) {
            $tdkn_barang .= ' ('.$keterangan.')';
        }

        Tindakan::create([
            'trans_kode' => $transaksi->trans_kode,
            'tdkn_barang' => $tdkn_barang,
            'tdkn_harga' => $harga,
            'tdkn_qty' => $qty,
            'tdkn_subtot' => $harga * $qty,
            'tdkn_tanggal' => date('Y-m-d'),
            'tdkn_jam' => date('H:i:s'),
        ]);

        return back()->with('sukses', 'Tindakan perbaikan berhasil ditambahkan');
    }

    public function delete_tindakan($id)
    {
        $tindakan = Tindakan::findOrFail($id);
        $tindakan->delete();

        return back()->with('sukses', 'Tindakan perbaikan berhasil dihapus');
    }

    public function simpan_proses(Request $request, $id)
    {
        $transaksi = Transaksi::where('trans_kode', $id)->orWhere('cos_kode', $id)->first();
        if (! $transaksi) {
            $transaksi = Transaksi::whereHas('customer', function ($q) use ($id) {
                $q->where('id_costomer', $id);
            })->firstOrFail();
        }

        if ($transaksi->trans_status == 'Baru') {
            $transaksi->update(['trans_status' => 'Diproses']);
        }

        // Recalculate total subtot from tindakan
        $total = Tindakan::where('trans_kode', $transaksi->trans_kode)->sum('tdkn_subtot');
        $transaksi->update(['trans_total' => $total]);

        return redirect()->route('service.antrean', 'proses')->with('sukses', 'Tindakan perbaikan berhasil disimpan!');
    }

    public function update_customer(Request $request, $id)
    {
        $customer = Customer::where('id_costomer', $id)->first();
        if (! $customer) {
            $transaksi = Transaksi::where('trans_kode', $id)->first();
            if ($transaksi) {
                $customer = Customer::where('id_costomer', $transaksi->cos_kode)->first();
            }
        }

        if ($customer) {
            $pswd_type = $request->input('pswd_type', 'text');
            $password = ($pswd_type == 'text') ? $request->input('pswd') : $request->input('pswd_desc');

            $customer->update([
                'cos_nama' => $request->input('nama', $customer->cos_nama),
                'cos_hp' => $request->input('tlp', $customer->cos_hp),
                'cos_alamat' => $request->input('alamat', $customer->cos_alamat),
                'cos_cabang' => $request->input('cabang', $customer->cos_cabang),
                'cos_tgl_lahir' => $request->input('cos_tgl_lahir', $customer->cos_tgl_lahir),
                'cos_status' => $request->input('status', $customer->cos_status),
                'cos_device' => $request->input('device', $customer->cos_device),
                'cos_tipe' => $request->input('type', $customer->cos_tipe),
                'cos_model' => $request->input('model', $customer->cos_model),
                'cos_no_seri' => $request->input('seri', $customer->cos_no_seri),
                'cos_pswd_type' => $pswd_type,
                'cos_pswd' => $password,
                'cos_asesoris' => $request->input('asesoris', $customer->cos_asesoris),
                'cos_keluhan' => $request->input('keluhan', $customer->cos_keluhan),
                'cos_keterangan' => $request->input('ket', $customer->cos_keterangan),
            ]);
        }

        return back()->with('sukses', 'Data customer berhasil diperbarui');
    }
}
