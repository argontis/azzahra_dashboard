<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Costomer; // Pastikan model ini sudah dibuat

class CustomerScoringController extends Controller
{
    /**
     * Menambahkan tipe "float" pada parameter (Solusi P1132)
     */
    public function hitungSkorAI(float $frequency, float $recency)
    {
        try {
            $flaskUrl = 'http://127.0.0.1:5000/api/scoring';

            $response = Http::timeout(5)->post($flaskUrl, [
                'frequency' => $frequency,
                'recency' => $recency
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['skor_pelanggan']; 
            } else {
                Log::error("Flask API merespons dengan error: " . $response->body());
                return null;
            }

        } catch (\Exception $e) {
            Log::error("Gagal menghubungi server AI: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Menambahkan tipe "string" atau "int" pada parameter $id_costomer (Solusi P1132)
     */
    public function updateSkorPelanggan(Request $request, string $id_costomer)
    {
        // 1. Ambil data pelanggan dari database (Solusi P1009 teratasi jika Model Costomer ada)
        $pelanggan = Costomer::findOrFail($id_costomer);

        $jumlahServis = 12; 
        $hariBelumDatang = 3; 

        $skorBaru = $this->hitungSkorAI($jumlahServis, $hariBelumDatang);

        if ($skorBaru !== null) {
            $pelanggan->cos_poin = $skorBaru; 
            $pelanggan->save();

            return response()->json([
                'pesan' => 'Skor AI berhasil diperbarui!',
                'skor_baru' => $skorBaru
            ]);
        }

        return response()->json(['pesan' => 'Gagal memperbarui skor, periksa log Laravel.'], 500);
    }
}