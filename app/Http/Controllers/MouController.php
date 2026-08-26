<?php

namespace App\Http\Controllers;

use App\Models\Mou;
use App\Models\MouItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MouController extends Controller
{
    public function index()
    {
        $mou_list = Mou::with('items')->orderBy('created_at', 'desc')->paginate(25);

        return view('mou.index', ['mou_list' => $mou_list]);
    }

    public function create_form()
    {
        return view('mou.create', ['google_doc_url' => '']);
    }

    public function create(Request $request)
    {
        $request->validate([
            'file_name' => 'required',
            'lokasi' => 'required',
            'tanggal' => 'required|date',
            'customer' => 'required',
        ]);

        $items = json_decode($request->items, true);
        if (empty($items)) {
            return back()->with('gagal', 'Minimal harus ada 1 item');
        }

        $grand_total = 0;
        $items_processed = [];

        foreach ($items as $item) {
            $qty = floatval($item['qty']);
            $harga = floatval(str_replace(['.', ','], '', $item['harga']));
            $total = $qty * $harga;
            $grand_total += $total;

            $items_processed[] = [
                'spesifikasi' => $item['spesifikasi'],
                'qty' => $qty,
                'harga' => $harga,
                'total' => $total,
            ];
        }

        $mou = Mou::create([
            'file_name' => $request->file_name,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'customer' => $request->customer,
            'intro_text' => $request->intro_text,
            'terms' => $request->terms,
            'grand_total' => $grand_total,
            'kry_kode' => auth()->user()->kry_kode ?? 'SYS',
        ]);

        $item_no = 1;
        foreach ($items_processed as $item) {
            MouItem::create(array_merge($item, ['mou_id' => $mou->mou_id, 'item_no' => $item_no++]));
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'pdf_url' => route('admin.mou.download', $mou->mou_id)]);
        }

        return $this->generateAndDownload($mou->mou_id);
    }

    public function edit_form($id)
    {
        $mou = Mou::with('items')->findOrFail($id);

        return view('mou.edit', ['mou' => $mou, 'items' => $mou->items]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'file_name' => 'required',
            'lokasi' => 'required',
            'tanggal' => 'required|date',
            'customer' => 'required',
        ]);

        $mou = Mou::findOrFail($id);
        $items = json_decode($request->items, true);

        if (empty($items)) {
            return back()->with('gagal', 'Minimal harus ada 1 item');
        }

        $grand_total = 0;
        $items_processed = [];

        foreach ($items as $item) {
            $qty = floatval($item['qty']);
            $harga = floatval(str_replace(['.', ','], '', $item['harga']));
            $total = $qty * $harga;
            $grand_total += $total;

            $items_processed[] = [
                'spesifikasi' => $item['spesifikasi'],
                'qty' => $qty,
                'harga' => $harga,
                'total' => $total,
            ];
        }

        $mou->update([
            'file_name' => $request->file_name,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'customer' => $request->customer,
            'intro_text' => $request->intro_text,
            'terms' => $request->terms,
            'grand_total' => $grand_total,
        ]);

        $mou->items()->delete();

        $item_no = 1;
        foreach ($items_processed as $item) {
            MouItem::create(array_merge($item, ['mou_id' => $mou->mou_id, 'item_no' => $item_no++]));
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'pdf_url' => route('admin.mou.download', $mou->mou_id)]);
        }

        return redirect()->route('admin.mou.download', $mou->mou_id);
    }

    public function delete($id)
    {
        $mou = Mou::findOrFail($id);
        $mou->items()->delete();
        $mou->delete();

        return response()->json(['status' => 'success']);
    }

    public function download($id)
    {
        return $this->generateAndDownload($id);
    }

    private function generateAndDownload($id)
    {
        $mou = Mou::with('items')->findOrFail($id);

        $data = [
            'mou' => $mou,
            'items' => $mou->items,
            'terms' => explode("\n", $mou->terms),
        ];

        $pdf = Pdf::loadView('mou.pdf_template', $data)->setPaper('a4', 'portrait');

        return $pdf->download(preg_replace('/[^a-zA-Z0-9._-]/', '_', $mou->file_name).'.pdf');
    }
}
