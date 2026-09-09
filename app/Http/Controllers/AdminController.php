<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OrderList;
use App\Models\Tindakan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Basic metrics (similar to M_admin legacy)
        $baru_query = OrderList::join('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'order_list.kry_kode', '=', 'karyawan.kry_kode')
            ->whereDate('order_list.created_at', $today);
        $baru_count = $baru_query->count();
        $baru = $baru_query->orderBy('order_list.created_at', 'desc')->limit(5)->get();

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
        $users_baru_query = Customer::whereDate('created_at', $today);
        $users_baru_count = $users_baru_query->count();
        $users_baru = $users_baru_query->orderBy('created_at', 'desc')->limit(5)->get();
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

        $title = 'Dashboard';

        return view('admin.dashboard', compact(
            'title', 'baru', 'baru_count', 'konf', 'discount', 'bca', 'mandiri', 'bri', 'tunai',
            'total_bca', 'total_mandiri', 'total_bri', 'total_tunai', 'total_voucher',
            'users_baru', 'users_baru_count', 'revenue_today', 'total_customers', 'dp_pending', 'total_pending_transfers',
            'service_completion_rate', 'high_pending', 'urgent_confirmations',
            'bank_percentages', 'tunai_percentage', 'voucher_usage_percentage'
        ));
    }

    public function customer()
    {
        $customers = DB::table('costomer')
            ->leftJoin('transaksi', function ($join) {
                $join->on('transaksi.cos_kode', '=', 'costomer.id_costomer')
                    ->whereIn('transaksi.trans_kode', function ($query) {
                        $query->select(DB::raw('MAX(trans_kode)'))
                            ->from('transaksi')
                            ->groupBy('cos_kode');
                    });
            })
            ->select('costomer.*', 'transaksi.trans_status', 'transaksi.trans_kode')
            ->orderBy('costomer.id_costomer', 'desc')
            ->paginate(20);

        return redirect()->route('customer.index');
    }

    public function cus_baru()
    {
        $trans = DB::table('transaksi')
            ->leftJoin('transaksi_detail', 'transaksi.trans_kode', '=', 'transaksi_detail.trans_kode')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('transaksi.trans_status', 'Baru')
            // Tambahkan orderBy di sini.
            // Ganti 'created_at' jika nama kolom tanggal di database Anda berbeda (misal: 'tgl_transaksi')
            ->orderBy('transaksi.created_at', 'desc')
            ->get();

        return view('admin.cus-baru', ['title' => 'Transaksi Baru', 'trans' => $trans]);
    }

    public function cus_proses()
    {
        $trans = DB::table('transaksi')
            ->leftJoin('transaksi_detail', 'transaksi.trans_kode', '=', 'transaksi_detail.trans_kode')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('transaksi.trans_status', 'Pelunasan')
            ->get();

        return view('admin.cus_proses', ['title' => 'Transaksi Proses (Pelunasan)', 'trans' => $trans]);
    }

    public function cus_konf()
    {
        $trans = DB::table('transaksi')
            ->leftJoin('transaksi_detail', 'transaksi.trans_kode', '=', 'transaksi_detail.trans_kode')
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

    public function cus_discount()
    {
        $trans = DB::table('transaksi')
            ->leftJoin('transaksi_detail', 'transaksi.trans_kode', '=', 'transaksi_detail.trans_kode')
            ->leftJoin('costomer', 'transaksi.cos_kode', '=', 'costomer.id_costomer')
            ->leftJoin('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->where('transaksi.trans_discount', '>', 0)
            ->get();

        return view('admin.cus_discount', ['title' => 'Transaksi-Discount', 'trans' => $trans]);
    }

    public function konfirmasi($kode)
    {
        $transaksi = Transaksi::with(['customer', 'karyawan'])->where('trans_kode', $kode)->firstOrFail();
        $tindakan = Tindakan::where('trans_kode', $kode)->get();

        return view('admin.konfirmasi', [
            'title' => 'Customer Konfirmasi',
            'proses' => array_merge($transaksi->toArray(), $transaksi->customer->toArray()),
            'trans' => $transaksi,
            'data' => $tindakan,
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
                    'tdkn_subtot' => $sub,
                ]);
                $total += $sub;
            }

            Transaksi::where('trans_kode', $kd_trans)->update([
                'trans_total' => $total,
                'trans_status' => 'Konfirmasi',
            ]);

            OrderList::where('trans_kode', $kd_trans)->update([
                'trans_total' => $total,
                'trans_status' => 'waitingOrder',
            ]);

            DB::commit();

            return redirect()->route('admin.cus_konf')->with('sukses', 'DI KONFIRMASI');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('gagal', 'Terjadi Kesalahan: '.$e->getMessage());
        }
    }

    public function setoran(Request $request)
    {
        $kode = $request->input('kode');
        $jml = $request->input('jml_tranfer');

        TransaksiDetail::where('dtl_kode', $kode)->update([
            'dtl_jml_bayar' => $jml,
            'dtl_stt_stor' => 'Disetorkan',
        ]);

        return redirect()->route('admin.cus_konf_bank')->with('sukses', 'DI SETORKAN');
    }

    public function lap_perhari()
    {
        $today = Carbon::today()->toDateString();

        $data = $this->laporan_data($today, $today);
        $data['title'] = 'Laporan Harian';

        return view('admin.lap-perhari', $data);
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
        $jml_DP_mandiri = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'mandiri')->count();
        $tot_DP_mandiri = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'mandiri')->sum('dtl_jml_bayar');
        $jml_DP_tunai = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'tunai')->count();
        $tot_DP_tunai = $payments->where('dtl_status', 'DP')->where('dtl_metode', 'tunai')->sum('dtl_jml_bayar');

        $jml_lns_tunai = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'tunai')->count();
        $tot_lns_tunai = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'tunai')->sum('dtl_jml_bayar');
        $jml_lns_bca = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bca')->count();
        $tot_lns_bca = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bca')->sum('dtl_jml_bayar');
        $jml_lns_bri = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bri')->count();
        $tot_lns_bri = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'bri')->sum('dtl_jml_bayar');
        $jml_lns_mandiri = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'mandiri')->count();
        $tot_lns_mandiri = $payments->where('dtl_status', 'PELUNASAN')->where('dtl_metode', 'mandiri')->sum('dtl_jml_bayar');

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
                'Tegal' => [], 'Cibubur' => [], 'Kampus Saintek' => [], 'Kampus PKTJ' => [],
            ];
            foreach ($payments as $p) {
                if (isset($payments_by_cabang[$p->cabang])) {
                    $payments_by_cabang[$p->cabang][] = $p;
                }
            }
        }

        return compact(
            'payments', 'dp_payments', 'lunas_payments', 'menunggu_payments', 'menunggu_total', 'menunggu_count',
            'jml_DP_bca', 'tot_DP_bca', 'jml_DP_bri', 'tot_DP_bri', 'jml_DP_mandiri', 'tot_DP_mandiri', 'jml_DP_tunai', 'tot_DP_tunai',
            'jml_lns_tunai', 'tot_lns_tunai', 'jml_lns_bca', 'tot_lns_bca', 'jml_lns_bri', 'tot_lns_bri', 'jml_lns_mandiri', 'tot_lns_mandiri',
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
        $data['jml_mandiri'] = $data['jml_lns_mandiri'];
        $data['tot_mandiri'] = $data['tot_lns_mandiri'];
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

        return $pdf->download('Laporan_Harian_Admin_'.$today.'.pdf');
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

        return $pdf->download('Laporan_Admin_'.$tgl_awal.'_to_'.$tgl_akhir.'.pdf');
    }

    public function export_dashboard(Request $request)
    {
        $today = Carbon::today();
        $weeklyPeriodParam = $request->input('weekly_period', '7D');
        $technicianPeriodParam = $request->input('technician_period', '7D');
        $section = $request->input('section', 'all');

        // Determine date ranges
        $weeklyDays = match ($weeklyPeriodParam) {
            '1M' => 30,
            '3M' => 90,
            '1Y' => 365,
            default => 7,
        };
        $techDays = match ($technicianPeriodParam) {
            '1M' => 30,
            '1Y' => 365,
            'all' => 3650,
            default => 7,
        };

        $weeklyStart = Carbon::now()->subDays($weeklyDays)->startOfDay();
        $techStart = Carbon::now()->subDays($techDays)->startOfDay();

        // ── Performance Metrics ──
        $revenue_today = TransaksiDetail::whereDate('dtl_tanggal', $today)
            ->whereIn('dtl_status', ['DP', 'PELUNASAN'])
            ->sum('dtl_jml_bayar');

        $pembayaran_lunas = TransaksiDetail::where('dtl_status', 'PELUNASAN')->count();
        $total_customers = Customer::count();
        $dp_pending = Transaksi::where('trans_status', 'Pelunasan')->count();

        // ── Payment Methods ──
        $bca = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'BCA')->where('dtl_stt_stor', 'Menunggu')->count();
        $mandiri = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'MANDIRI')->where('dtl_stt_stor', 'Menunggu')->count();
        $bri = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_bank', 'BRI')->where('dtl_stt_stor', 'Menunggu')->count();
        $tunai = TransaksiDetail::where('dtl_jenis_bayar', 'TUNAI')->where('dtl_status', 'PELUNASAN')->whereDate('dtl_tanggal', $today)->count();
        $total_pending_transfers = TransaksiDetail::where('dtl_jenis_bayar', 'TRANFER')->where('dtl_stt_stor', 'Menunggu')->count();
        $discount_active = DB::table('vocer')->where('voc_status', 'ON')->count();

        $total_methods = $bca + $mandiri + $bri + $tunai;
        $bca_pct = $total_methods > 0 ? round(($bca / $total_methods) * 100, 1) : 0;
        $mandiri_pct = $total_methods > 0 ? round(($mandiri / $total_methods) * 100, 1) : 0;
        $bri_pct = $total_methods > 0 ? round(($bri / $total_methods) * 100, 1) : 0;
        $tunai_pct = $total_methods > 0 ? round(($tunai / $total_methods) * 100, 1) : 0;
        $voucher_pct = $total_methods > 0 ? round(($discount_active / max($total_methods, 1)) * 100, 1) : 0;

        // ── Weekly Revenue Performance ──
        $weekly_revenue = DB::table('transaksi_detail')
            ->selectRaw('DATE(dtl_tanggal) as day, SUM(dtl_jml_bayar) as total')
            ->whereIn('dtl_status', ['DP', 'PELUNASAN'])
            ->where('dtl_tanggal', '>=', $weeklyStart)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // ── Weekly Transaction Performance ──
        $weekly_transactions = DB::table('transaksi_detail')
            ->selectRaw('DATE(dtl_tanggal) as day, COUNT(*) as total')
            ->where('dtl_tanggal', '>=', $weeklyStart)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // ── Weekly New Customers ──
        $weekly_customers = DB::table('costomer')
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', $weeklyStart)
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // ── Top Technicians ──
        $technician_stats = DB::table('transaksi')
            ->join('karyawan', 'transaksi.kry_kode', '=', 'karyawan.kry_kode')
            ->selectRaw('karyawan.kry_nama as technician_name, COUNT(*) as services_completed')
            ->where('transaksi.updated_at', '>=', $techStart)
            ->whereNotNull('transaksi.kry_kode')
            ->groupBy('karyawan.kry_nama')
            ->orderByDesc('services_completed')
            ->limit(10)
            ->get();

        // ── Recent Customers ──
        $recent_customers = DB::table('costomer')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // ── Recent Users ──
        $recent_users = DB::table('users')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // ── Activity Summary ──
        $konf_pending = Transaksi::where('trans_status', 'Diproses')->count();
        $voucher_aktif = DB::table('vocer')->where('voc_status', 'ON')->count();
        $customer_baru_hari_ini = Customer::whereDate('created_at', $today)->count();
        $user_baru_hari_ini = DB::table('users')->whereDate('created_at', $today)->count();

        $generated_at = Carbon::now('Asia/Jakarta')->format('d F Y H:i:s');
        $generated_by = auth()->user()->name ?? 'Admin';

        return view('admin.export_dashboard', compact(
            'section', 'generated_at', 'generated_by',
            'revenue_today', 'pembayaran_lunas', 'total_customers', 'dp_pending',
            'bca', 'mandiri', 'bri', 'tunai', 'total_pending_transfers',
            'bca_pct', 'mandiri_pct', 'bri_pct', 'tunai_pct', 'voucher_pct', 'discount_active',
            'weekly_revenue', 'weekly_transactions', 'weekly_customers',
            'technician_stats', 'technicianPeriodParam', 'weeklyPeriodParam',
            'recent_customers', 'recent_users',
            'konf_pending', 'voucher_aktif', 'customer_baru_hari_ini', 'user_baru_hari_ini'
        ));
    }
}
