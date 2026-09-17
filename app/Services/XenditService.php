<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected string $baseUrl = 'https://api.xendit.co';

    protected ?string $secretKey;

    protected ?string $webhookToken;

    protected bool $sslVerify;

    public function __construct()
    {
        $this->secretKey = config('services.xendit.secret_key');
        $this->webhookToken = config('services.xendit.webhook_token');
        $this->sslVerify = (bool) config('services.xendit.ssl_verify', false);
    }

    /**
     * Build the configured HTTP client.
     */
    protected function client()
    {
        $client = Http::withBasicAuth($this->secretKey, '')
            ->timeout(20)
            ->acceptJson();

        if (! $this->sslVerify) {
            $client->withoutVerifying();
        }

        return $client;
    }

    /**
     * Check if Xendit secret key is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->secretKey);
    }

    /**
     * Create an invoice via Xendit v2 Invoices API.
     *
     * @throws Exception
     */
    public function createInvoice(array $payload): array
    {
        if (! $this->isConfigured()) {
            throw new Exception('Xendit Secret Key belum dikonfigurasi di .env (XENDIT_SECRET_KEY).');
        }

        $url = $this->baseUrl.'/v2/invoices';

        $response = $this->client()->post($url, $payload);

        if ($response->failed()) {
            Log::error('Xendit Create Invoice Failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'payload' => $payload,
            ]);

            $errorMessage = $response->json('message') ?? 'Gagal menghubungi Xendit API ('.$response->status().')';
            throw new Exception($errorMessage);
        }

        return $response->json();
    }

    /**
     * Retrieve an invoice by its Xendit ID.
     *
     * @throws Exception
     */
    public function getInvoice(string $invoiceId): array
    {
        if (! $this->isConfigured()) {
            throw new Exception('Xendit Secret Key belum dikonfigurasi di .env.');
        }

        $url = $this->baseUrl.'/v2/invoices/'.urlencode($invoiceId);

        $response = $this->client()->get($url);

        if ($response->failed()) {
            Log::error('Xendit Get Invoice Failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'invoice_id' => $invoiceId,
            ]);

            $errorMessage = $response->json('message') ?? 'Gagal memeriksa status invoice dari Xendit ('.$response->status().')';
            throw new Exception($errorMessage);
        }

        return $response->json();
    }

    /**
     * Verify the Xendit webhook callback token.
     */
    public function verifyWebhookToken(?string $incomingToken): bool
    {
        if (empty($this->webhookToken)) {
            // If token is not set, allow for development or log warning
            Log::warning('Xendit Webhook Token is not set in config, accepting payload in dev mode.');

            return true;
        }

        return hash_equals($this->webhookToken, (string) $incomingToken);
    }
}
