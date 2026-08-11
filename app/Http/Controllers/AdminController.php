<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Tindakan;
use App\Models\OrderList;
use App\Models\KetersediaanSparepart;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Basic metrics (similar to M_admin legacy)
        $baru = Transaksi::where('trans_status', 'Baru')->count();
        $konf = Transaksi::where('trans_status', 'Diproses')->count();
        $discount = DB::table('vocer')->where('voc_status', 'ON')->count();
        
        $bca = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'BCA')->where('dtl_stt_stor', 'Menunggu')->count();
        $mandiri = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'MANDIRI')->where('dtl_stt_stor', 'Menunggu')->count();
        $bri = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'BRI')->where('dtl_stt_stor', 'Menunggu')->count();
        $tunai = TransaksiDetail::where('dtl_jenis_bayar', 'TUNAI')->where('dtl_status', 'PELUNASAN')->whereDate('dtl_tanggal', $today)->count();

        $total_bca = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'BCA')->where('dtl_stt_stor', 'Menunggu')->sum('dtl_jml_bayar');
        $total_mandiri = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'MANDIRI')->where('dtl_stt_stor', 'Menunggu')->sum('dtl_jml_bayar');
        $total_bri = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'BRI')->where('dtl_stt_stor', 'Menunggu')->sum('dtl_jml_bayar');
        $total_tunai = TransaksiDetail::where('dtl_jenis_bayar', 'TUNAI')->where('dtl_status', 'PELUNASAN')->whereDate('dtl_tanggal', $today)->sum('dtl_jml_bayar');
        
        $total_voucher = DB::table('vocer')->where('voc_status', 'ON')->sum('voc_jumlah');
        $users_baru = Customer::whereDate('created_at', $today)->count();
        $revenue_today = TransaksiDetail::whereDate('dtl_tanggal', $today)->whereIn('dtl_status', ['DP', 'PELUNASAN'])->sum('dtl_jml_bayar');
        
        $total_customers = Customer::count();
        $dp_pending = Transaksi::where('trans_status', 'Pelunasan')->count();
        $total_pending_transfers = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_stt_stor', 'Menunggu')->count();

        $service_completion_rate = 95; // Default healthy rate
        $high_pending = $total_pending_transfers > 5;
        $urgent_confirmations = $konf;

        $total_methods = $bca + $mandiri + $bri + $tunai;
        $bank_percentages = [];
        $tunai_percentage = 0;
        $voucher_usage_percentage = 0; // Or whatever calculation is needed
        
        if ($total_methods > 0) {
            $bank_percentages['BCA'] = round(($bca / $total_methods) * 100);
            $bank_percentages['MANDIRI'] = round(($mandiri / $total_methods) * 100);
            $bank_percentages['BRI'] = round(($bri / $total_methods) * 100);
            $tunai_percentage = round(($tunai / $total_methods) * 100);
        }

        return view('admin.dashboard', compact(
            'baru', 'konf', 'discount', 'bca', 'mandiri', 'bri', 'tunai',
            'total_bca', 'total_mandiri', 'total_bri', 'total_tunai', 'total_voucher',
            'users_baru', 'revenue_today', 'total_customers', 'dp_pending', 'total_pending_transfers',
            'service_completion_rate', 'high_pending', 'urgent_confirmations',
            'bank_percentages', 'tunai_percentage', 'voucher_usage_percentage'
        ));
    }

    public function customer()
    {
        $customers = Customer::orderBy('id_costomer', 'desc')->paginate(20);
        return view('admin.customer', ['title' => 'Customer', 'custom' => $customers]);
    }

    public function cus_baru()
    {
        $trans = DB::table('transaksi')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('transaksi.trans_status', 'Baru')
            ->get();
        return view('admin.cus-baru', ['title' => 'Transaksi Baru', 'trans' => $trans]);
    }

    public function cus_proses()
    {
        $trans = DB::table('transaksi')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('transaksi.trans_status', 'Pelunasan')
            ->get();
        return view('admin.cus_proses', ['title' => 'Transaksi Proses (Pelunasan)', 'trans' => $trans]);
    }

    public function cus_konf()
    {
        $trans = DB::table('transaksi')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('transaksi.trans_status', 'Diproses')
            ->get();
        return view('admin.cus-konf', ['title' => 'Transaksi Konfirmasi', 'trans' => $trans]);
    }

    public function cus_konf_bank()
    {
        $trans = DB::table('transaksi_detail')
            ->leftJoin('transaksi', 'transaksi.trans_kode', '=', 'transaksi_detail.trans_kode')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('transaksi.trans_status', 'Pelunasan')
            ->where('transaksi_detail.dtl_jenis_bayar', 'TRANFER')
            ->where('transaksi_detail.dtl_stt_stor', 'Menunggu')
            ->get();
        
        return view('admin.cus_konf_bank', ['title' => 'Konfirmasi Bank Transfer', 'trans' => $trans]);
    }

    public function konfirmasi($kode)
    {
        $transaksi = Transaksi::with(['customer', 'karyawan'])->where('trans_kode', $kode)->firstOrFail();
        $tindakan = Tindakan::where('trans_kode', $kode)->get();

        return view('admin.konfirmasi', [
            'title' => 'Customer Konfirmasi',
            'proses' => array_merge($transaksi->toArray(), $transaksi->customer->toArray()),
            'trans' => $transaksi,
            'data' => $tindakan
        ]);
    }

    public function update_konf(Request $request)
    {
        $kd_trans = $request->input('tras_kode');
        $kd_tdkn = $request->input('tdkn');
        $tindakan = $request->input('tindakan');
        $qty = $request->input('qty');
        $subtot = $request->input('subtot');
        
        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($kd_tdkn as $index => $id_tindakan) {
                $sub = str_replace('.', '', $subtot[$index]);
                Tindakan::where('tdkn_kode', $id_tindakan)->update([
                    'tdkn_qty' => $qty[$index],
                    'tdkn_subtot' => $sub
                ]);
                $total += $sub;
            }

            Transaksi::where('trans_kode', $kd_trans)->update([
                'trans_total' => $total,
                'trans_status' => 'Konfirmasi'
            ]);

            OrderList::where('trans_kode', $kd_trans)->update([
                'trans_total' => $total,
                'trans_status' => 'waitingOrder'
            ]);

            DB::commit();
            return redirect()->route('admin.cus_konf')->with('sukses', 'DI KONFIRMASI');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('gagal', 'Terjadi Kesalahan: ' . $e->getMessage());
        }
    }

    public function setoran(Request $request)
    {
        $kode = $request->input('kode');
        $jml = $request->input('jml_tranfer');

        TransaksiDetail::where('dtl_kode', $kode)->update([
            'dtl_jml_bayar' => $jml,
            'dtl_stt_stor' => 'Disetorkan'
        ]);

        return redirect()->route('admin.cus_konf_bank')->with('sukses', 'DI SETORKAN');
    }

    public function lap_perhari()
    {
        $today = Carbon::today()->toDateString();
        $payments = TransaksiDetail::with('transaksi.customer')->whereDate('dtl_tanggal', $today)->get();
        
        $menunggu = TransaksiDetail::where('dtl_stt_stor', 'Menunggu')->whereDate('dtl_tanggal', $today);
        
        return view('admin.lap-perhari', [
            'title' => 'Laporan Harian',
            'payments' => $payments,
            'menunggu_total' => $menunggu->sum('dtl_jml_bayar'),
            'menunggu_count' => $menunggu->count()
        ]);
    }

    private function laporan_data($tgl_awal, $tgl_akhir)
    {
        $payments = DB::table('transaksi_detail')
            ->leftJoin('transaksi', 'transaksi.trans_kode', '=', 'transaksi_detail.trans_kode')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->whereDate('transaksi_detail.dtl_tanggal', '>=', $tgl_awal)
            ->whereDate('transaksi_detail.dtl_tanggal', '<=', $tgl_akhir)
            ->get();
            
        $dp_payments = $payments->where('dtl_status', 'DP')->values();
        $lunas_payments = $payments->where('dtl_status', 'PELUNASAN')->values();
        
        $lunas_codes = $lunas_payments->pluck('cos_kode')->toArray();
        $dp_payments = $dp_payments->reject(function ($p) use ($lunas_codes) {
            return in_array($p->cos_kode, $lunas_codes);
        })->values();
        
        $menunggu_payments = $payments->where('dtl_stt_stor', 'Menunggu')->values();

        $menunggu = DB::table('transaksi_detail')
            ->where('dtl_stt_stor', 'Menunggu')
            ->whereIn('dtl_status', ['DP', 'PELUNASAN'])
            ->whereDate('dtl_tanggal', '>=', $tgl_awal)
            ->whereDate('dtl_tanggal', '<=', $tgl_akhir)
            ->get();

        $menunggu_total = $menunggu->sum('dtl_jml_bayar');
        $menunggu_count = $menunggu->count();

        $jml_DP_bca = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'bca')->count();
        $tot_DP_bca = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'bca')->sum('dtl_jml_bayar');
        $jml_DP_bri = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'bri')->count(); 
        $tot_DP_bri = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'bri')->sum('dtl_jml_bayar');
        $jml_DP_tunai = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'tunai')->count();
        $tot_DP_tunai = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'tunai')->sum('dtl_jml_bayar');

        $jml_lns_tunai = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'tunai')->count();
        $tot_lns_tunai = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'tunai')->sum('dtl_jml_bayar');
        $jml_lns_bca = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bca')->count();
        $tot_lns_bca = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bca')->sum('dtl_jml_bayar');
        $jml_lns_bri = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bri')->count();
        $tot_lns_bri = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bri')->sum('dtl_jml_bayar');

        $jml_tranfer = $payments->where('dtl_jenis_bayar', 'TRANFER')->count();
        $tot_tranfer = $payments->where('dtl_jenis_bayar', 'TRANFER')->sum('dtl_jml_bayar');
        $jml_tunai = $payments->where('dtl_jenis_bayar', 'TUNAI')->count();
        $tot_tunai = $payments->where('dtl_jenis_bayar', 'TUNAI')->sum('dtl_jml_bayar');
        $jml_setor = $payments->where('dtl_stt_stor', 'Sudah')->count();

        $all_have_cabang = true;
        foreach ($payments as $p) {
            if (empty($p->cabang)) {
                $all_have_cabang = false;
                break;
            }
        }
        $payments_by_cabang = null;
        if ($all_have_cabang) {
            $payments_by_cabang = [
                'Tegal' => [], 'Cibubur' => [], 'Kampus Saintek' => [], 'Kampus PKTJ' => []
            ];
            foreach ($payments as $p) {
                if (isset($payments_by_cabang[$p->cabang])) {
                    $payments_by_cabang[$p->cabang][] = $p;
                }
            }
        }

        return compact(
            'payments', 'dp_payments', 'lunas_payments', 'menunggu_payments', 'menunggu_total', 'menunggu_count',
            'jml_DP_bca', 'tot_DP_bca', 'jml_DP_bri', 'tot_DP_bri', 'jml_DP_tunai', 'tot_DP_tunai',
            'jml_lns_tunai', 'tot_lns_tunai', 'jml_lns_bca', 'tot_lns_bca', 'jml_lns_bri', 'tot_lns_bri',
            'jml_tranfer', 'tot_tranfer', 'jml_tunai', 'tot_tunai', 'jml_setor', 'payments_by_cabang', 'all_have_cabang'
        );
    }

    public function laporan(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', Carbon::today()->toDateString());
        $tgl_akhir = $request->input('tgl_akhir', Carbon::today()->toDateString());

        $data = $this->laporan_data($tgl_awal, $tgl_akhir);
        $data['title'] = 'Laporan Transaksi';
        $data['tgl_awal'] = $tgl_awal;
        $data['tgl_akhir'] = $tgl_akhir;
        
        $data['jml_bca'] = $data['jml_lns_bca'];
        $data['tot_bca'] = $data['tot_lns_bca'];
        $data['jml_bri'] = $data['jml_lns_bri'];
        $data['tot_bri'] = $data['tot_lns_bri'];
        $data['jml_mandiri'] = $data['jml_lns_bri'];
        $data['tot_mandiri'] = $data['tot_lns_bri'];
        $data['count_tunai'] = $data['jml_lns_tunai'];
        $data['total_tunai'] = $data['tot_lns_tunai'];
        
        // Pass legacy properties for the foreach arrays
        $data['dp'] = $data['dp_payments'];
        $data['lunas'] = $data['lunas_payments'];
        $data['menunggu_list'] = $data['menunggu_payments'];

        return view('admin.laporan', $data);
    }
    
    public function export_pdf_lap_perhari()
    {
        $today = Carbon::today()->toDateString();
        $payments = TransaksiDetail::with('transaksi.customer')->whereDate('dtl_tanggal', $today)->get();
        
        $pdf = Pdf::loadView('admin.laporan_pdf', compact('payments', 'today'));
        return $pdf->download('Laporan_Harian_Admin_' . $today . '.pdf');
    }

    public function export_pdf_laporan(Request $request)
    {
        $tgl_awal = $request->input('tgl_awal', Carbon::today()->toDateString());
        $tgl_akhir = $request->input('tgl_akhir', Carbon::today()->toDateString());

        $payments = TransaksiDetail::with('transaksi.customer')
            ->whereDate('dtl_tanggal', '>=', $tgl_awal)
            ->whereDate('dtl_tanggal', '<=', $tgl_akhir)
            ->get();
            
        $pdf = Pdf::loadView('admin.laporan_pdf_range', compact('payments', 'tgl_awal', 'tgl_akhir'));
        return $pdf->download('Laporan_Admin_' . $tgl_awal . '_to_' . $tgl_akhir . '.pdf');
    }
}
