<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    public function sendMessage(string $phone, string $message): bool
    {
        try {

            $response = Http::withHeaders([
                'Authorization' => config('fonnte.token'),
            ])->post(config('fonnte.url'), [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $json = $response->json();

            if ($response->successful() && data_get($json, 'status') === true) {

                Log::channel('whatsapp')->info('WhatsApp berhasil dikirim', [
                    'phone' => $phone,
                    'response' => $json,
                ]);

                return true;
            }

            Log::channel('whatsapp')->error('WhatsApp gagal dikirim', [
                'phone' => $phone,
                'response' => $json ?? $response->body(),
            ]);

            return false;

        } catch (\Throwable $e) {

            Log::channel('whatsapp')->error('Fonnte error', [
                'phone' => $phone,
                'message' => $e->getMessage(),
            ]);

            return false;
        }
    }
}