<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FptTtsService
{
    public function getVoices(): array
    {
        $apiUrl = env('FPT_AI_API_URL');
        $apiKey = env('FPT_AI_API_KEY');

        $response = Http::withHeaders([
            'api-key' => $apiKey,
        ])->get($apiUrl);

        return $response->json();
    }
}
