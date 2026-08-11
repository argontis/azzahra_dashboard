<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export daily report as CSV
     */
    public function lap_perhari_excel()
    {
        $today = Carbon::today()->toDateString();
        
        $payments = TransaksiDetail::with(['transaksi.customer'])
            ->whereDate('dtl_tanggal', $today)
            ->get();

        $filename = "laporan_hari_ini_" . date('d-m-Y') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ["NO", "INVOICE", "NAMA CUSTOMER", "STATUS", "JENIS BAYAR", "BANK", "JUMLAH (Rp)"];

        $callback = function() use($payments, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM to fix UTF-8 in Excel
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            fputcsv($file, $columns);
            
            $total = 0;
            $no = 1;
            
            foreach ($payments as $payment) {
                $row['NO'] = $no++;
                $row['INVOICE'] = $payment->trans_kode;
                $row['NAMA CUSTOMER'] = $payment->transaksi->customer->cos_nama ?? '-';
                $row['STATUS'] = $payment->dtl_status;
                $row['JENIS BAYAR'] = $payment->dtl_jenis_bayar;
                $row['BANK'] = $payment->dtl_bank ?? '-';
                $row['JUMLAH (Rp)'] = $payment->dtl_jml_bayar;

                fputcsv($file, [
                    $row['NO'],
                    $row['INVOICE'],
                    $row['NAMA CUSTOMER'],
                    $row['STATUS'],
                    $row['JENIS BAYAR'],
                    $row['BANK'],
                    $row['JUMLAH (Rp)']
                ]);
                
                $total += $payment->dtl_jml_bayar;
            }
            
            fputcsv($file, ['', '', '', '', '', 'TOTAL PENDAPATAN', $total]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export periodic report as CSV
     */
    public function lap_excel(Request $request)
    {
        $tgl_awal = $request->query('tgl_awal') ?? Carbon::today()->startOfMonth()->toDateString();
        $tgl_akhir = $request->query('tgl_akhir') ?? Carbon::today()->endOfMonth()->toDateString();
        
        $payments = TransaksiDetail::with(['transaksi.customer'])
            ->whereBetween('dtl_tanggal', [$tgl_awal . ' 00:00:00', $tgl_akhir . ' 23:59:59'])
            ->get();

        $filename = "laporan_periode_" . $tgl_awal . "_sampai_" . $tgl_akhir . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ["NO", "INVOICE", "NAMA CUSTOMER", "TANGGAL", "STATUS", "JENIS BAYAR", "BANK", "JUMLAH (Rp)"];

        $callback = function() use($payments, $columns, $tgl_awal, $tgl_akhir) {
            $file = fopen('php://output', 'w');
            
            fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF)));
            
            fputcsv($file, ["PERIODE LAPORAN", $tgl_awal . " s/d " . $tgl_akhir]);
            fputcsv($file, []);
            fputcsv($file, $columns);
            
            $total = 0;
            $no = 1;
            
            foreach ($payments as $payment) {
                $row['NO'] = $no++;
                $row['INVOICE'] = $payment->trans_kode;
                $row['NAMA CUSTOMER'] = $payment->transaksi->customer->cos_nama ?? '-';
                $row['TANGGAL'] = Carbon::parse($payment->dtl_tanggal)->format('d-m-Y');
                $row['STATUS'] = $payment->dtl_status;
                $row['JENIS BAYAR'] = $payment->dtl_jenis_bayar;
                $row['BANK'] = $payment->dtl_bank ?? '-';
                $row['JUMLAH (Rp)'] = $payment->dtl_jml_bayar;

                fputcsv($file, [
                    $row['NO'],
                    $row['INVOICE'],
                    $row['NAMA CUSTOMER'],
                    $row['TANGGAL'],
                    $row['STATUS'],
                    $row['JENIS BAYAR'],
                    $row['BANK'],
                    $row['JUMLAH (Rp)']
                ]);
                
                $total += $payment->dtl_jml_bayar;
            }
            
            fputcsv($file, ['', '', '', '', '', '', 'TOTAL PENDAPATAN', $total]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
