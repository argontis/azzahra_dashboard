<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderList;
use App\Models\OrderPartMarking;
use App\Models\Transaksi;
use App\Models\Tindakan;
use App\Models\Customer;
use App\Models\TransaksiDetail;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders based on filter.
     */
    public function index(Request $request, $filter = 'pending')
    {
        $data = [
            'title' => 'Order',
            'filter' => $filter,
        ];
        
        $search = $request->input('search');
        $per_page = 10;
        
        if ($filter == 'pending') {
            // Get orders in waitingOrder status
            $orders = DB::table('order_list')
                ->select(
                    'tindakan.tdkn_kode', 'tindakan.tdkn_barang', 'tindakan.tdkn_qty',
                    'order_list.trans_kode', 'order_list.device', 'order_list.merek', 'order_list.seri',
                    'order_list.status_garansi', 'costomer.cos_nama',
                    DB::raw('(SELECT COALESCE(SUM(tdkn_subtot), 0) FROM tindakan WHERE trans_kode = order_list.trans_kode) as total_subtot'),
                    DB::raw('(SELECT COALESCE(SUM(dtl_jml_bayar), 0) FROM transaksi_detail WHERE TRIM(trans_kode) = TRIM(order_list.trans_kode)) as total_bayar')
                )
                ->join('tindakan as tindakan', 'order_list.trans_kode', '=', 'tindakan.trans_kode')
                ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
                ->where('order_list.trans_status', 'waitingOrder')
                ->orderBy('tindakan.tdkn_kode', 'ASC')
                ->get();
            
            $data['orders'] = $orders;
            $data['table_title'] = 'Pending Orders';
            $data['karyawan'] = Karyawan::all();
            $data['show_modal'] = true;
        } elseif ($filter == 'waiting') {
            // Waiting approval (OOW/IW parts)
            // Simulating M_order->get_waiting_approval_orders()
            $orders = DB::table('order_list')
                ->select('order_list.*', 'costomer.cos_nama', 'order_list.ket_keluhan as keluhan')
                ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
                ->where('order_list.trans_status', 'waitingApproval')
                ->orderBy('order_list.trans_tanggal', 'DESC')
                ->get();
                
            $data['orders'] = $orders;
            $data['table_title'] = 'Waiting Approval Orders';
            $data['show_modal'] = false;
        } elseif ($filter == 'confirm') {
            $orders = DB::table('order_list')
                ->select('order_list.*', 'costomer.cos_nama', 'order_list.ket_keluhan as keluhan')
                ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
                ->where('order_list.trans_status', 'pending')
                ->orderBy('order_list.trans_tanggal', 'DESC')
                ->get();
                
            $data['orders'] = $orders;
            $data['table_title'] = 'Confirm Orders';
            $data['karyawan'] = Karyawan::all();
            $data['show_modal'] = false;
        } elseif ($filter == 'repairing') {
            $query1 = DB::table('order_list')
                ->select(
                    'order_list.trans_kode', 'order_list.cos_kode', 'order_list.trans_total', 
                    'order_list.trans_tanggal', 'costomer.cos_nama', 'costomer.cos_hp', 
                    'transaksis.trans_status as payment_status', DB::raw("'order_list' as source_table"), 
                    DB::raw("COALESCE(order_list.device, '-') as device"), 'costomer.cos_tipe as merek', 
                    'order_list.seri', 'costomer.cos_keluhan as ket_keluhan'
                )
                ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
                ->leftJoin('transaksis', 'order_list.trans_kode', '=', 'transaksis.trans_kode')
                ->where('order_list.trans_status', 'repairing');
                
            if (!empty($search)) {
                $query1->where(function($q) use ($search) {
                    $q->where('order_list.trans_kode', 'like', "%{$search}%")
                      ->orWhere('costomer.cos_nama', 'like', "%{$search}%");
                });
            }
            
            $orders = $query1->paginate($per_page);
            
            $data['orders'] = $orders;
            $data['table_title'] = 'Repairing Orders';
            $data['show_modal'] = false;
            $data['search'] = $search;
        } elseif ($filter == 'completed') {
            $query = DB::table('order_list')
                ->select('order_list.*', 'costomer.cos_nama', 'costomer.cos_hp', 'transaksis.trans_status as payment_status')
                ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
                ->leftJoin('transaksis', 'order_list.trans_kode', '=', 'transaksis.trans_kode')
                ->where('order_list.trans_status', 'service_completed');
                
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('order_list.trans_kode', 'like', "%{$search}%")
                      ->orWhere('costomer.cos_nama', 'like', "%{$search}%");
                });
            }
            
            $orders = $query->orderBy('order_list.trans_tanggal', 'DESC')->paginate($per_page);
            
            $data['orders'] = $orders;
            $data['table_title'] = 'Completed Orders';
            $data['show_modal'] = false;
            $data['search'] = $search;
        } elseif ($filter == 'failed') {
            $query = DB::table('order_list')
                ->select('order_list.*', 'costomer.cos_nama', 'costomer.cos_hp', 'transaksis.trans_status as payment_status')
                ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
                ->leftJoin('transaksis', 'order_list.trans_kode', '=', 'transaksis.trans_kode')
                ->where('order_list.trans_status', 'service_failed');
                
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('order_list.trans_kode', 'like', "%{$search}%")
                      ->orWhere('costomer.cos_nama', 'like', "%{$search}%");
                });
            }
            
            $orders = $query->orderBy('order_list.trans_tanggal', 'DESC')->paginate($per_page);
            
            $data['orders'] = $orders;
            $data['table_title'] = 'Failed Orders';
            $data['show_modal'] = false;
            $data['search'] = $search;
        } elseif ($filter == 'orderpart') {
            // Get order part marking
            $query = DB::table('order_part_markings')
                ->select(
                    'order_list.trans_kode', 'order_list.cos_kode', 'order_list.device', 'order_list.merek', 
                    'order_list.seri', 'order_list.ket_keluhan', 'costomer.cos_nama', 'costomer.cos_status', 
                    'order_part_markings.is_ordered', 'order_part_markings.rma_number', 
                    'order_part_markings.end_warranty_date', DB::raw('"part_marking" as source_type')
                )
                ->join('order_list', 'order_part_markings.trans_kode', '=', 'order_list.trans_kode')
                ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
                ->where('order_part_markings.is_ordered', 'yes');
                
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('order_list.trans_kode', 'like', "%{$search}%")
                      ->orWhere('costomer.cos_nama', 'like', "%{$search}%");
                });
            }
            
            $orders = $query->orderBy('order_list.trans_tanggal', 'DESC')->paginate($per_page);
            
            // Add tindakan to each order
            $ordersCollection = collect($orders->items());
            $ordersCollection->transform(function ($item, $key) {
                $tindakans = DB::table('tindakan')
                    ->join('transaksis', 'tindakan.trans_kode', '=', 'transaksis.trans_kode')
                    ->select('tindakan.tdkn_barang', 'tindakan.tdkn_qty')
                    ->where('transaksis.cos_kode', $item->cos_kode)
                    ->get();
                $item->tindakan = $tindakans;
                return $item;
            });
            
            $data['orders'] = $orders;
            $data['table_title'] = 'Order Part Status';
            $data['show_modal'] = false;
            $data['search'] = $search;
        }

        // Notifications
        $data['today_orders'] = DB::table('order_list')
            ->select('order_list.trans_kode', 'order_list.trans_status', 'costomer.cos_nama', 'order_list.created_at')
            ->leftJoin('costomer', 'order_list.cos_kode', '=', 'costomer.id_costomer')
            ->whereDate('order_list.created_at', date('Y-m-d'))
            ->whereIn('order_list.trans_status', ['waitingOrder', 'waitingApproval', 'pending'])
            ->orderBy('order_list.created_at', 'DESC')
            ->get();
            
        return view('order.index', $data);
    }

    public function update_status(Request $request)
    {
        $trans_kode = $request->input('trans_kode');
        $status = $request->input('status');

        OrderList::where('trans_kode', $trans_kode)->update(['trans_status' => $status]);
        return redirect()->route('admin.order.index')->with('sukses', 'Status berhasil diupdate');
    }

    public function confirm_order(Request $request)
    {
        $trans_kode = trim($request->input('trans_kode'));
        $kry_kode = $request->input('kry_kode');

        if (!$trans_kode || !$kry_kode) {
            return redirect()->route('admin.order.index', 'pending')->with('gagal', 'Transaksi atau karyawan belum dipilih.');
        }

        $res = OrderList::where('trans_kode', $trans_kode)->update([
            'trans_status' => 'confirm',
            'kry_kode' => $kry_kode
        ]);

        if ($res) {
            return redirect()->route('admin.order.index', 'confirm')->with('sukses', 'Order berhasil dikonfirmasi.');
        } else {
            return redirect()->route('admin.order.index', 'pending')->with('gagal', 'Gagal mengkonfirmasi order.');
        }
    }

    public function approve_order(Request $request)
    {
        $trans_kode = trim($request->input('trans_kode'));
        $tdkn_kode = trim($request->input('tdkn_kode'));
        $subtot = str_replace('.', '', trim($request->input('subtot')));

        if (!$trans_kode || !$tdkn_kode || !is_numeric($subtot)) {
            return redirect()->route('admin.order.index', 'waiting')->with('gagal', 'Data tidak valid.');
        }

        Tindakan::where('trans_kode', $trans_kode)->where('tdkn_kode', $tdkn_kode)->update(['tdkn_subtot' => $subtot]);
        OrderList::where('trans_kode', $trans_kode)->update(['trans_total' => $subtot, 'trans_status' => 'approved']);

        return redirect()->route('admin.order.index', 'waiting')->with('sukses', 'Order berhasil disetujui.');
    }

    public function service_complete(Request $request)
    {
        $trans_kode = trim($request->input('trans_kode'));

        if (!$trans_kode) {
            return redirect()->route('admin.order.index', 'repairing')->with('gagal', 'trans_kode tidak ditemukan.');
        }

        OrderList::where('trans_kode', $trans_kode)->update(['trans_status' => 'service_completed']);
        return redirect()->route('admin.order.index', 'completed')->with('sukses', 'Service berhasil ditandai sebagai selesai.');
    }

    public function service_failed(Request $request)
    {
        $trans_kode = trim($request->input('trans_kode'));

        if (!$trans_kode) {
            return redirect()->route('admin.order.index', 'repairing')->with('gagal', 'trans_kode tidak ditemukan.');
        }

        OrderList::where('trans_kode', $trans_kode)->update(['trans_status' => 'service_failed']);
        return redirect()->route('admin.order.index', 'failed')->with('sukses', 'Service berhasil ditandai sebagai gagal.');
    }

    public function inform_unavailable(Request $request)
    {
        $trans_kode = trim($request->input('trans_kode'));
        $tdkn_kode = trim($request->input('tdkn_kode'));
        $subtot = str_replace('.', '', trim($request->input('subtot')));

        if (!$trans_kode || !$tdkn_kode || !is_numeric($subtot)) {
            return redirect()->route('admin.order.index', 'waiting')->with('gagal', 'Data tidak valid.');
        }

        Tindakan::where('trans_kode', $trans_kode)->where('tdkn_kode', $tdkn_kode)->update(['tdkn_subtot' => $subtot]);
        OrderList::where('trans_kode', $trans_kode)->update(['trans_total' => $subtot, 'trans_status' => 'waitingOrder']);

        return redirect()->route('admin.order.index', 'waiting')->with('sukses', 'Orderan berhasil di submit.');
    }

    public function update_trans_total(Request $request)
    {
        $trans_kode = $request->input('trans_kode');
        $sisa = $request->input('sisa');

        if (!$trans_kode) {
            return redirect()->route('admin.order.index', 'pending')->with('gagal', 'trans_kode tidak ditemukan.');
        }

        $res = OrderList::where('trans_kode', $trans_kode)->update(['trans_total' => $sisa, 'trans_status' => 'approved']);

        if ($res) {
            return redirect()->route('admin.order.index', 'pending')->with('sukses', 'Trans total dan status berhasil diupdate.');
        } else {
            return redirect()->route('admin.order.index', 'pending')->with('gagal', 'Gagal mengupdate trans total dan status.');
        }
    }
}
