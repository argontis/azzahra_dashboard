<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerScoringController extends Controller
{
    /**
     * Mengirim data frequency dan recency ke Flask API server untuk menghitung skor AI
     */
    public function hitungSkorAI(float $frequency, float $recency)
    {
        try {
            $flaskUrl = config('services.flask.scoring_url', env('FLASK_SCORING_URL', 'http://127.0.0.1:5000/api/scoring'));

            $response = Http::timeout(5)->post($flaskUrl, [
                'frequency' => $frequency,
                'recency' => $recency,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $score = $data['skor_pelanggan'] ?? $data['skor'] ?? $data['score'] ?? $data['prediction'] ?? null;

                return $score !== null ? (float) $score : null;
            } else {
                Log::error('Flask API merespons dengan error: ' . $response->body());

                return null;
            }
        } catch (\Exception $e) {
            Log::error('Gagal menghubungi server AI Flask: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Memperbarui skor & tier pelanggan secara otomatis melalui Flask AI
     */
    public function syncCustomerScore(Customer $pelanggan, ?float $customFrequency = null, ?float $customRecency = null): ?array
    {
        $frequency = $customFrequency ?? (float) $pelanggan->transaksi()->count();

        if ($customRecency !== null) {
            $recency = $customRecency;
        } else {
            $lastTxDate = $pelanggan->transaksi()->max('created_at');
            $recency = $lastTxDate ? (float) Carbon::parse($lastTxDate)->diffInDays(now()) : 999.0;
        }

        $skorBaru = $this->hitungSkorAI($frequency, $recency);

        if ($skorBaru !== null) {
            $tierBaru = match (true) {
                $skorBaru >= 5 => 'prioritas',
                $skorBaru >= 3 => 'loyal',
                default => 'reguler',
            };

            $pelanggan->update([
                'cos_score' => $skorBaru,
                'cos_tier' => $tierBaru,
            ]);

            return [
                'id_costomer' => $pelanggan->id_costomer,
                'skor_baru' => $skorBaru,
                'tier_baru' => $tierBaru,
                'frequency' => $frequency,
                'recency' => $recency,
            ];
        }

        return null;
    }

    /**
     * Memperbarui skor & tier pelanggan menggunakan Flask AI (Single)
     */
    public function updateSkorPelanggan(Request $request, string $id_costomer)
    {
        $pelanggan = Customer::findOrFail($id_costomer);

        $customFreq = $request->filled('frequency') ? (float) $request->input('frequency') : null;
        $customRec = $request->filled('recency') ? (float) $request->input('recency') : null;

        $result = $this->syncCustomerScore($pelanggan, $customFreq, $customRec);

        if ($result !== null) {
            return response()->json([
                'status' => 'success',
                'pesan' => 'Skor AI berhasil diperbarui!',
                'skor_baru' => $result['skor_baru'],
                'tier_baru' => $result['tier_baru'],
                'frequency' => $result['frequency'],
                'recency' => $result['recency'],
            ]);
        }

        return response()->json([
            'status' => 'error',
            'pesan' => 'Gagal memperbarui skor. Pastikan server Flask AI (http://127.0.0.1:5000) berjalan.',
        ], 500);
    }

    /**
     * Memperbarui skor & tier beberapa/semua pelanggan secara otomatis (Batch Auto-Sync)
     */
    public function updateSkorSemuaPelanggan(Request $request)
    {
        $customerIds = $request->input('customer_ids', []);

        if (empty($customerIds)) {
            $customers = Customer::orderBy('id_costomer', 'desc')->take(20)->get();
        } else {
            $customers = Customer::whereIn('id_costomer', $customerIds)->get();
        }

        $results = [];

        foreach ($customers as $customer) {
            $res = $this->syncCustomerScore($customer);
            if ($res) {
                $results[] = $res;
            }
        }

        return response()->json([
            'status' => 'success',
            'pesan' => 'Berhasil memperbarui skor AI untuk ' . count($results) . ' pelanggan.',
            'data' => $results,
        ]);
    }
}
