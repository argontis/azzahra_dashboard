<?php

namespace App\Http\Controllers;

use App\Models\Mou;
use App\Models\MouItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MouController extends Controller
{
    private string $defaultIntro = "Dengan hormat,\nKami AZZAHRA COMPUTER & AUTHORIZED SERVICE CENTER, mengajukan penawaran harga sebagai berikut:";

    private string $defaultTerms = "Semua barang diatas inden 1-2 hari\nHarga diatas sudah termasuk biaya instalasi\nGaransi perangkat selama 2 tahun\nPembayaran min DP 50% dr total biaya\nPembayaran bisa di transfer ke Rek BCA No.Rek 0470727705 (ferry juanda)";

    public function index()
    {
        $mou_list = Mou::with(['items', 'karyawan'])->orderBy('created_at', 'desc')->paginate(25);

        return view('mou.index', ['mou_list' => $mou_list]);
    }

    public function create_form()
    {
        return view('mou.create', ['google_doc_url' => '']);
    }

    public function create(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string|max:255',
            'lokasi' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'customer' => 'required|string|max:255',
        ]);

        $items = is_array($request->items) ? $request->items : json_decode($request->items, true);
        if (empty($items)) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['status' => 'error', 'message' => 'Minimal harus ada 1 item'], 422);
            }

            return back()->with('gagal', 'Minimal harus ada 1 item');
        }

        $grand_total = 0;
        $items_processed = [];

        foreach ($items as $item) {
            $qty = floatval($item['qty'] ?? 1);
            $hargaRaw = is_string($item['harga'] ?? 0) ? str_replace(['.', ',', ' '], '', $item['harga']) : ($item['harga'] ?? 0);
            $harga = floatval($hargaRaw);
            $total = $qty * $harga;
            $grand_total += $total;

            $items_processed[] = [
                'spesifikasi' => $item['spesifikasi'] ?? '-',
                'qty' => $qty,
                'harga' => $harga,
                'total' => $total,
            ];
        }

        $intro_text = ! empty($request->intro_text) ? $request->intro_text : $this->defaultIntro;
        $terms = ! empty($request->terms) ? $request->terms : $this->defaultTerms;

        $mou = Mou::create([
            'file_name' => $request->file_name,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'customer' => $request->customer,
            'intro_text' => $intro_text,
            'terms' => $terms,
            'grand_total' => $grand_total,
            'kry_kode' => auth()->user()->kry_kode ?? auth()->user()->kry_username ?? 'SYS',
        ]);

        $item_no = 1;
        foreach ($items_processed as $item) {
            MouItem::create(array_merge($item, ['mou_id' => $mou->mou_id, 'item_no' => $item_no++]));
        }

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'status' => 'success',
                'message' => 'Mou berhasil dibuat!',
                'redirect_url' => route('admin.mou.index'),
                'pdf_url' => route('admin.mou.download', $mou->mou_id),
            ]);
        }

        return redirect()->route('admin.mou.index')
            ->with('sukses', 'Mou berhasil dibuat!')
            ->with('download_pdf', route('admin.mou.download', $mou->mou_id));
    }

    public function edit_form($id)
    {
        $mou = Mou::with('items')->findOrFail($id);

        return view('mou.edit', ['mou' => $mou, 'items' => $mou->items]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'file_name' => 'required|string|max:255',
            'lokasi' => 'required|string|max:100',
            'tanggal' => 'required|date',
            'customer' => 'required|string|max:255',
        ]);

        $mou = Mou::findOrFail($id);
        $items = is_array($request->items) ? $request->items : json_decode($request->items, true);

        if (empty($items)) {
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['status' => 'error', 'message' => 'Minimal harus ada 1 item'], 422);
            }

            return back()->with('gagal', 'Minimal harus ada 1 item');
        }

        $grand_total = 0;
        $items_processed = [];

        foreach ($items as $item) {
            $qty = floatval($item['qty'] ?? 1);
            $hargaRaw = is_string($item['harga'] ?? 0) ? str_replace(['.', ',', ' '], '', $item['harga']) : ($item['harga'] ?? 0);
            $harga = floatval($hargaRaw);
            $total = $qty * $harga;
            $grand_total += $total;

            $items_processed[] = [
                'spesifikasi' => $item['spesifikasi'] ?? '-',
                'qty' => $qty,
                'harga' => $harga,
                'total' => $total,
            ];
        }

        $intro_text = ! empty($request->intro_text) ? $request->intro_text : ($mou->intro_text ?: $this->defaultIntro);
        $terms = ! empty($request->terms) ? $request->terms : ($mou->terms ?: $this->defaultTerms);

        $mou->update([
            'file_name' => $request->file_name,
            'lokasi' => $request->lokasi,
            'tanggal' => $request->tanggal,
            'customer' => $request->customer,
            'intro_text' => $intro_text,
            'terms' => $terms,
            'grand_total' => $grand_total,
        ]);

        $mou->items()->delete();

        $item_no = 1;
        foreach ($items_processed as $item) {
            MouItem::create(array_merge($item, ['mou_id' => $mou->mou_id, 'item_no' => $item_no++]));
        }

        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'status' => 'success',
                'message' => 'Mou berhasil diperbarui!',
                'redirect_url' => route('admin.mou.index'),
                'pdf_url' => route('admin.mou.download', $mou->mou_id),
            ]);
        }

        return redirect()->route('admin.mou.index')
            ->with('sukses', 'Mou berhasil diperbarui!')
            ->with('download_pdf', route('admin.mou.download', $mou->mou_id));
    }

    public function delete($id)
    {
        $mou = Mou::findOrFail($id);
        $mou->items()->delete();
        $mou->delete();

        return response()->json(['status' => 'success', 'message' => 'Mou berhasil dihapus']);
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
            'terms' => explode("\n", $mou->terms ?? $this->defaultTerms),
        ];

        $pdf = Pdf::loadView('mou.pdf_template', $data)->setPaper('a4', 'portrait');

        return $pdf->download(preg_replace('/[^a-zA-Z0-9._-]/', '_', $mou->file_name).'.pdf');
    }
}
