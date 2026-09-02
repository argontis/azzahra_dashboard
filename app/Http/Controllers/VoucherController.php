<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index(Request $request)
    {
        $vouchers = Voucher::orderBy('voucher_id', 'DESC')->paginate(15);

        $data = [
            'title' => 'Voucher Discount',
            'voucher' => $vouchers,
            'pagination' => $vouchers->links(),
            'total_records' => $vouchers->total(),
            'offset' => ($vouchers->currentPage() - 1) * $vouchers->perPage(),
            'per_page' => $vouchers->perPage(),
        ];

        return view('voucher.read', $data);
    }

    public function ajax_search(Request $request)
    {
        $search = $request->input('search');

        $query = Voucher::query();
        if ($search) {
            $query->where('voucher_code', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $vouchers = $query->orderBy('voucher_id', 'DESC')->paginate(15);

        return view('voucher.ajax_table', [
            'voucher' => $vouchers,
            'pagination' => $vouchers->links(),
            'total_records' => $vouchers->total(),
            'offset' => ($vouchers->currentPage() - 1) * $vouchers->perPage(),
            'per_page' => $vouchers->perPage(),
        ]);
    }

    public function add()
    {
        $data = [
            'title' => 'Tambah Voucher',
        ];

        return view('voucher.add', $data);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'voucher_code' => 'required|unique:vouchers,voucher_code',
            'discount_percent' => 'required|numeric',
            'voucher_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['voucher_code', 'description', 'discount_percent', 'start_date', 'end_date', 'max_usage', 'status']);

        if ($request->hasFile('voucher_gambar')) {
            $path = $request->file('voucher_gambar')->store('vouchers', 'public');
            $data['voucher_gambar'] = basename($path);
        }

        Voucher::create($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Voucher berhasil ditambahkan.']);
        }

        return redirect()->route('admin.voucher.index')->with('sukses', 'Voucher berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);

        $data = [
            'title' => 'Edit Voucher',
            'voucher' => $voucher,
        ];

        return view('voucher.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'voucher_code' => 'required|unique:vouchers,voucher_code,'.$id.',voucher_id',
            'discount_percent' => 'required|numeric',
            'voucher_gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()]);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['voucher_code', 'description', 'discount_percent', 'start_date', 'end_date', 'max_usage', 'status']);

        if ($request->hasFile('voucher_gambar')) {
            // Hapus gambar lama jika ada
            if ($voucher->voucher_gambar) {
                Storage::disk('public')->delete('vouchers/'.$voucher->voucher_gambar);
                Storage::delete('public/vouchers/'.$voucher->voucher_gambar);
            }
            $path = $request->file('voucher_gambar')->store('vouchers', 'public');
            $data['voucher_gambar'] = basename($path);
        }

        $voucher->update($data);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Voucher berhasil diupdate.']);
        }

        return redirect()->route('admin.voucher.index')->with('sukses', 'Voucher berhasil diupdate.');
    }

    public function delete(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        if ($voucher->voucher_gambar) {
            Storage::disk('public')->delete('vouchers/'.$voucher->voucher_gambar);
            Storage::delete('public/vouchers/'.$voucher->voucher_gambar);
        }

        $voucher->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Voucher berhasil dihapus.']);
        }

        return redirect()->route('admin.voucher.index')->with('sukses', 'Voucher berhasil dihapus.');
    }
}
