<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        return view('produk.index', ['title' => 'Produk']);
    }

    public function ajax_search(Request $request)
    {
        $search = $request->input('search');

        $query = Produk::query();

        if ($search) {
            $query->where('nama_produk', 'like', "%{$search}%")
                ->orWhere('deskripsi', 'like', "%{$search}%")
                ->orWhere('kode_barang', 'like', "%{$search}%")
                ->orWhere('harga', 'like', "%{$search}%");
        }

        $produks = $query->orderBy('kode_barang', 'desc')->paginate(15);

        return view('produk.ajax_table', compact('produks'));
    }

    public function create()
    {
        // Generate kode barang
        $prefix = 'PRD';
        $date = date('Ymd');

        $last_produk = Produk::where('kode_barang', 'like', "{$prefix}-{$date}-%")->orderBy('kode_barang', 'desc')->first();

        if ($last_produk) {
            $last_number = (int) substr($last_produk->kode_barang, -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        $kode_barang = $prefix.'-'.$date.'-'.str_pad($new_number, 4, '0', STR_PAD_LEFT);

        return view('produk.form', ['title' => 'Tambah Produk', 'kode_barang' => $kode_barang, 'produk' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:produk,kode_barang',
            'nama_produk' => 'required',
            'harga' => 'required',
        ]);

        $harga = str_replace('.', '', $request->harga);

        // Handle images
        $uploaded_images = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = 'produk_'.time().'_'.rand(1000, 9999).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/produk'), $filename);
                $uploaded_images[] = $filename;
            }
        }

        Produk::create([
            'kode_barang' => $request->kode_barang,
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $harga,
            'gambar' => ! empty($uploaded_images) ? implode(',', $uploaded_images) : null,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan.']);
        }

        return redirect('/Produk')->with('sukses', 'Produk berhasil ditambahkan.');
    }

    public function edit($kode_barang)
    {
        $produk = Produk::findOrFail($kode_barang);

        return view('produk.form', ['title' => 'Edit Produk', 'produk' => $produk, 'kode_barang' => $produk->kode_barang]);
    }

    public function update(Request $request, $kode_barang)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga' => 'required',
        ]);

        $produk = Produk::findOrFail($kode_barang);
        $harga = str_replace('.', '', $request->harga);

        // Simple image handling for now (append new ones)
        // Note: Full replacement logic like in CI can be added later if needed
        $final_images = $produk->gambar ? explode(',', $produk->gambar) : [];

        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = 'produk_'.time().'_'.rand(1000, 9999).'.'.$file->getClientOriginalExtension();
                $file->move(public_path('uploads/produk'), $filename);
                $final_images[] = $filename;
            }
        }

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $harga,
            'gambar' => ! empty($final_images) ? implode(',', $final_images) : null,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil diupdate.']);
        }

        return redirect('/Produk')->with('sukses', 'Produk berhasil diupdate.');
    }

    public function destroy($kode_barang)
    {
        $produk = Produk::findOrFail($kode_barang);
        $produk->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus.']);
        }

        return redirect('/Produk')->with('sukses', 'Produk berhasil dihapus.');
    }
}
