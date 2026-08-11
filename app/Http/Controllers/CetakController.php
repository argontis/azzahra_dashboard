<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Tindakan;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    /**
     * print_1 & download : A4 Landscape Invoice
     */
    public function print_1($param, Request $request)
    {
        return $this->generate_invoice($param, $request, 'print_1');
    }

    public function download($trans_kode, $dtl_status = 'PELUNASAN', Request $request)
    {
        return $this->generate_invoice($trans_kode, $request, 'download', $dtl_status);
    }

    private function generate_invoice($param, $request, $type, $dtl_status = 'PELUNASAN')
    {
        // For simplicity, we assume $param is trans_kode
        $trans = Transaksi::with(['customer', 'tindakan', 'transaksi_details'])->where('trans_kode', $param)->firstOrFail();
        
        $customer = $trans->customer;
        $barang = $trans->tindakan;
        $pembayaran = $trans->transaksi_details;

        $dp = 0;
        $detail = null;

        if ($type == 'download') {
            $detail = TransaksiDetail::where('trans_kode', $param)
                        ->where('dtl_status', $dtl_status)
                        ->orderBy('dtl_kode', 'desc')
                        ->first();

            if ($dtl_status == 'DP' && $detail) {
                $dp = $detail->dtl_jml_bayar;
            } elseif ($dtl_status == 'PELUNASAN') {
                $dp_detail = TransaksiDetail::where('trans_kode', $param)->where('dtl_status', 'DP')->first();
                if ($dp_detail) {
                    $dp = $dp_detail->dtl_jml_bayar;
                }
            }
        }

        $total_barang = $barang->sum(function($b) {
            return ($b->tdkn_qty ?? 1) * $b->tdkn_subtot;
        });

        $final_total = $total_barang - $dp;
        if ($dtl_status == 'PELUNASAN') {
            $final_total = $total_barang;
        }

        $data = [
            'customer' => $customer,
            'trans' => $trans,
            'barang' => $barang,
            'pembayaran' => $pembayaran,
            'kasir' => auth()->user()->name ?? 'Kasir',
            'tanggal' => date('d/m/Y'),
            'jam' => date('H:i'),
            'dtl_status' => $dtl_status,
            'dp' => $dp,
            'final_total' => $final_total,
            'dtl_jml_bayar' => $detail ? $detail->dtl_jml_bayar : ($dtl_status == 'PELUNASAN' ? $final_total : 0),
        ];

        if ($request->get('preview')) {
            return view('cetak.invoice', $data);
        }

        $pdf = Pdf::loadView('cetak.invoice', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Invoice_' . $trans->trans_kode . '.pdf');
    }

    /**
     * print_3 : Kwitansi Return/Pembayaran
     */
    public function print_3($kode)
    {
        return $this->generate_thermal($kode, 'KWITANSI PEMBAYARAN', 'PEMBAYARAN', 'THERMAL_RECEIPT');
    }

    /**
     * print_4 : Pelunasan
     */
    public function print_4($kode)
    {
        return $this->generate_thermal($kode, 'PELUNASAN', 'PEMBAYARAN PELUNASAN', 'THERMAL_PELUNASAN', 'PELUNASAN');
    }

    /**
     * print_5 : DP
     */
    public function print_5($kode)
    {
        return $this->generate_thermal($kode, 'DOWN PAYMENT (DP)', 'PEMBAYARAN DP', 'THERMAL_DP', 'DP');
    }

    private function generate_thermal($trans_kode, $title, $section_title, $output_prefix, $filter_status = null)
    {
        $trans = Transaksi::with(['customer', 'tindakan', 'transaksi_details'])->where('trans_kode', $trans_kode)->firstOrFail();
        
        $customer = $trans->customer;
        $barang = $trans->tindakan;
        
        $bayar = null;
        if ($filter_status) {
            $bayar = TransaksiDetail::where('trans_kode', $trans_kode)
                        ->where('dtl_status', $filter_status)
                        ->orderBy('dtl_kode', 'desc')
                        ->first();
        } else {
            $bayar = TransaksiDetail::where('trans_kode', $trans_kode)
                        ->orderBy('dtl_kode', 'desc')
                        ->first();
        }

        if (!$bayar) {
            $bayar = (object)[
                'dtl_tanggal' => date('Y-m-d H:i:s'),
                'dtl_status' => $filter_status ?? 'PELUNASAN',
                'dtl_jenis_bayar' => '-',
                'dtl_bank' => '',
                'dtl_jml_bayar' => $trans->trans_total,
            ];
        }

        $data = [
            'title' => $title,
            'section_title' => $section_title,
            'customer' => $customer,
            'trans' => $trans,
            'bayar' => $bayar,
            'barang' => $barang,
            'total' => $trans->trans_total,
        ];

        // 80mm width thermal = 226.77 pt
        $customPaper = array(0,0,226.77,800); // long height, auto-cuts depending on printer, PDF will just be a long page.
        $pdf = Pdf::loadView('cetak.thermal', $data)->setPaper($customPaper, 'portrait');
        return $pdf->stream($output_prefix . '_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * print_6 : Pengakuan Pelanggan (A4 Portrait)
     */
    public function print_6($trans_kode)
    {
        $trans = Transaksi::with('customer')->where('trans_kode', $trans_kode)->firstOrFail();

        $data = [
            'trans' => $trans,
            'customer' => $trans->customer,
        ];

        $pdf = Pdf::loadView('cetak.pengakuan', $data)->setPaper('a4', 'portrait');
        return $pdf->stream('Pengakuan_' . $trans_kode . '.pdf');
    }
}
