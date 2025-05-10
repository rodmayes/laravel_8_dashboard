<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;
use Throwable;

class GenericApiService
{
    protected string $baseUrl;
    protected array $headers;
    protected PendingRequest $http;

    public function __construct(string $baseUrl, array $headers = [])
    {
        $this->baseUrl = trim($baseUrl);
        $this->headers = $headers;

        $this->http = Http::withOptions([
            'base_uri' => $this->baseUrl,
            'verify' => false,
        ])->withHeaders($this->headers)->asJson();
    }

    public function sendGet(string $endpoint, array $query = []): array|null
    {
        try {
            $response = $this->http->get($endpoint, $query)->json();
            return $this->handleResponse($response);
        } catch (Throwable $e) {
            Log::error('[API][GET] Exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            throw new \Exception('GET request failed: ' . $e->getMessage());
        }
    }

    public function sendPost(array $data = [], string $endpoint): array|null
    {
        try {
            $response = $this->http->post($endpoint, $data)->json();
            dd($response);
            return $this->handleResponse($response);
        } catch (Throwable $e) {
            Log::error('[API][POST] Exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            throw new \Exception('POST request failed: ' . $e->getMessage());
        }
    }

    public function sendPut(array $data = [], string $endpoint): array|null
    {
        try {
            $response = $this->http->put($endpoint, $data)->json();
            return $this->handleResponse($response);
        } catch (Throwable $e) {
            Log::error('[API][PUT] Exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            throw new \Exception('PUT request failed: ' . $e->getMessage());
        }
    }

    public function sendDelete(array $data = [], string $endpoint): array|null
    {
        try {
            $response = $this->http->delete($endpoint, $data)->json();
            return $this->handleResponse($response);
        } catch (Throwable $e) {
            Log::error('[API][DELETE] Exception', ['endpoint' => $endpoint, 'error' => $e->getMessage()]);
            throw new \Exception('DELETE request failed: ' . $e->getMessage());
        }
    }

    protected function handleResponse($response): array|null
    {
        if ($response->successful()) {
            return $response->json();
        }

        Log::warning('[API] Error response', [
            'url' => $response->effectiveUri(),
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('API Error: ' . $response->status() . ' - ' . $response->body());
    }
}

