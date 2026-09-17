<?php

namespace App\Http\Controllers;

use App\Models\TransaksiDetail;
use App\Services\XenditService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XenditPaymentController extends Controller
{
    public function __construct(protected XenditService $xenditService) {}

    /**
     * Create a Xendit Invoice for a TransaksiDetail item.
     */
    public function createInvoice(Request $request): JsonResponse
    {
        $request->validate([
            'dtl_kode' => 'required|integer',
        ]);

        $dtlKode = $request->input('dtl_kode');

        $detail = TransaksiDetail::with(['transaksi.customer'])->find($dtlKode);

        if (! $detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detail transaksi tidak ditemukan.',
            ], 404);
        }

        if ($detail->dtl_stt_stor === 'Disetorkan') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ini sudah disetorkan / lunas.',
            ], 400);
        }

        // If an active pending invoice already exists, return it directly unless forced
        if (! empty($detail->xendit_invoice_url) && $detail->xendit_status === 'PENDING' && ! $request->boolean('force_new')) {
            $customer = $detail->transaksi?->customer;
            $waUrl = $this->generateWhatsAppUrl(
                $customer?->cos_hp,
                $customer?->cos_nama ?? 'Pelanggan',
                $detail->trans_kode,
                (int) $detail->dtl_jml_bayar,
                $detail->xendit_invoice_url
            );

            return response()->json([
                'success' => true,
                'message' => 'Invoice Xendit aktif sudah tersedia.',
                'data' => [
                    'invoice_id' => $detail->xendit_invoice_id,
                    'invoice_url' => $detail->xendit_invoice_url,
                    'status' => $detail->xendit_status,
                    'whatsapp_url' => $waUrl,
                ],
            ]);
        }

        $transaksi = $detail->transaksi;
        $customer = $transaksi?->customer;

        $amount = (int) round((float) $detail->dtl_jml_bayar);
        if ($amount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah pembayaran tidak valid.',
            ], 400);
        }

        $externalId = 'INV-'.$detail->trans_kode.'-'.$detail->dtl_kode.'-'.time();
        $customerPhone = $this->formatIndonesianPhone($customer?->cos_hp ?? '');

        $payload = [
            'external_id' => $externalId,
            'amount' => $amount,
            'description' => 'Pembayaran Transaksi '.$detail->trans_kode.' - Azzahra Computer',
            'currency' => 'IDR',
            'invoice_duration' => 86400, // 24 hours
            'customer' => [
                'given_names' => $customer?->cos_nama ?? 'Customer',
                'mobile_number' => ! empty($customerPhone) ? '+'.$customerPhone : null,
            ],
            'success_redirect_url' => url('/Admin/cus_konf_bank?status=success&trans='.$detail->trans_kode),
            'failure_redirect_url' => url('/Admin/cus_konf_bank?status=failed&trans='.$detail->trans_kode),
        ];

        // Remove null customer phone if empty
        if (empty($payload['customer']['mobile_number'])) {
            unset($payload['customer']['mobile_number']);
        }

        try {
            $xenditInvoice = $this->xenditService->createInvoice($payload);

            $detail->update([
                'xendit_invoice_id' => $xenditInvoice['id'] ?? null,
                'xendit_invoice_url' => $xenditInvoice['invoice_url'] ?? null,
                'xendit_status' => $xenditInvoice['status'] ?? 'PENDING',
                'dtl_payment_method' => 'XENDIT',
            ]);

            $waUrl = $this->generateWhatsAppUrl(
                $customer?->cos_hp,
                $customer?->cos_nama ?? 'Pelanggan',
                $detail->trans_kode,
                $amount,
                $detail->xendit_invoice_url
            );

            return response()->json([
                'success' => true,
                'message' => 'Invoice Xendit berhasil dibuat.',
                'data' => [
                    'invoice_id' => $detail->xendit_invoice_id,
                    'invoice_url' => $detail->xendit_invoice_url,
                    'status' => $detail->xendit_status,
                    'whatsapp_url' => $waUrl,
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Gagal membuat Xendit Invoice', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat tagihan Xendit: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check real-time payment status with Xendit API.
     */
    public function checkStatus($dtlKode): JsonResponse
    {
        $detail = TransaksiDetail::with(['transaksi.customer'])->find($dtlKode);

        if (! $detail) {
            return response()->json([
                'success' => false,
                'message' => 'Detail transaksi tidak ditemukan.',
            ], 404);
        }

        if (empty($detail->xendit_invoice_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice Xendit belum dibuat untuk transaksi ini.',
            ], 400);
        }

        try {
            $invoice = $this->xenditService->getInvoice($detail->xendit_invoice_id);
            $status = strtoupper($invoice['status'] ?? 'PENDING');

            $isPaid = in_array($status, ['PAID', 'SETTLED']);

            DB::transaction(function () use ($detail, $status, $isPaid, $invoice) {
                $detail->xendit_status = $status;

                if ($isPaid) {
                    $paymentChannel = $invoice['payment_channel'] ?? $invoice['payment_method'] ?? 'ONLINE';
                    $detail->dtl_stt_stor = 'Disetorkan';
                    $detail->dtl_bank = 'XENDIT ('.$paymentChannel.')';
                    $detail->xendit_payment_method = $paymentChannel;
                    $detail->xendit_paid_at = now();

                    // Update parent transaction to Lunas
                    if ($detail->transaksi) {
                        $detail->transaksi->update(['trans_status' => 'Lunas']);

                        // Recalculate customer score automatically
                        if ($detail->transaksi->customer) {
                            $detail->transaksi->customer->recalculateScore();
                        }
                    }
                }

                $detail->save();
            });

            return response()->json([
                'success' => true,
                'status' => $status,
                'is_paid' => $isPaid,
                'message' => $isPaid ? 'Pembayaran Xendit telah LUNAS dan disetorkan!' : "Status saat ini: {$status}",
                'data' => [
                    'invoice_id' => $detail->xendit_invoice_id,
                    'invoice_url' => $detail->xendit_invoice_url,
                    'status' => $detail->xendit_status,
                    'stt_stor' => $detail->dtl_stt_stor,
                ],
            ]);
        } catch (Exception $e) {
            Log::error('Gagal mengecek status Xendit', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status Xendit: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook callback handler from Xendit.
     */
    public function webhook(Request $request): JsonResponse
    {
        $incomingToken = $request->header('x-callback-token');

        if (! $this->xenditService->verifyWebhookToken($incomingToken)) {
            Log::warning('Xendit Webhook Token Invalid', ['token' => $incomingToken]);

            return response()->json(['error' => 'Unauthorized token'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit Webhook Received', $payload);

        $invoiceId = $payload['id'] ?? null;
        $externalId = $payload['external_id'] ?? null;
        $status = strtoupper($payload['status'] ?? '');

        if (! $invoiceId && ! $externalId) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        $detail = TransaksiDetail::where('xendit_invoice_id', $invoiceId)->first();

        if (! $detail && $externalId) {
            // Attempt extraction from external_id: INV-{trans_kode}-{dtl_kode}-{time}
            $parts = explode('-', $externalId);
            if (count($parts) >= 3) {
                $dtlKode = $parts[2];
                $detail = TransaksiDetail::find($dtlKode);
            }
        }

        if (! $detail) {
            Log::warning('TransaksiDetail not found for Xendit webhook', [
                'invoice_id' => $invoiceId,
                'external_id' => $externalId,
            ]);

            return response()->json(['message' => 'Transaction not found, but callback acknowledged.'], 200);
        }

        $isPaid = in_array($status, ['PAID', 'SETTLED']);

        DB::transaction(function () use ($detail, $status, $isPaid, $payload, $invoiceId) {
            $detail->xendit_invoice_id = $invoiceId ?? $detail->xendit_invoice_id;
            $detail->xendit_status = $status;

            if ($isPaid) {
                $paymentChannel = $payload['payment_channel'] ?? $payload['payment_method'] ?? 'ONLINE';
                $detail->dtl_stt_stor = 'Disetorkan';
                $detail->dtl_bank = 'XENDIT ('.$paymentChannel.')';
                $detail->xendit_payment_method = $paymentChannel;
                $detail->xendit_paid_at = now();

                if ($detail->transaksi) {
                    $detail->transaksi->update(['trans_status' => 'Lunas']);

                    if ($detail->transaksi->customer) {
                        $detail->transaksi->customer->recalculateScore();
                    }
                }
            }

            $detail->save();
        });

        return response()->json(['status' => 'success']);
    }

    /**
     * Format phone number to standard Indonesian MSISDN (without leading 0 or +).
     */
    private function formatIndonesianPhone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            return '62'.substr($cleaned, 1);
        }

        if (str_starts_with($cleaned, '8')) {
            return '62'.$cleaned;
        }

        return $cleaned;
    }

    /**
     * Generate WhatsApp click-to-chat URL with pre-composed invoice message.
     */
    private function generateWhatsAppUrl(?string $phone, string $customerName, string $transKode, int $amount, ?string $invoiceUrl): string
    {
        $formattedPhone = $this->formatIndonesianPhone($phone);
        if (empty($formattedPhone)) {
            return '';
        }

        $amountFmt = number_format($amount, 0, ',', '.');
        $msg = "Halo Kak *{$customerName}*,\n\n"
            ."Berikut adalah rincian tagihan servis dari *Azzahra Computer*:\n\n"
            ."• No. Transaksi: *{$transKode}*\n"
            ."• Total Tagihan: *Rp {$amountFmt},-*\n\n"
            ."Untuk kemudahan pembayaran, Kakak dapat melakukan pembayaran online (Transfer Bank / Virtual Account BCA, Mandiri, BRI, BNI, QRIS, atau E-Wallet) melalui link resmi Xendit berikut:\n"
            ."👉 {$invoiceUrl}\n\n"
            .'Konfirmasi pembayaran akan terverifikasi secara otomatis oleh sistem kami. Terima kasih! 🙏';

        return 'https://wa.me/'.$formattedPhone.'?text='.urlencode($msg);
    }
}
