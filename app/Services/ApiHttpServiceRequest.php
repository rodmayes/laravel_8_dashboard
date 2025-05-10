<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiHttpServiceRequest extends Http
{
    public bool $debug = false;

    protected string $baseUri;
    private $httpd;

    public function __construct($baseUri = '', $token = null)
    {
        if (!$token) {
            Log::error('[API] Token no proporcionado');
            throw new \Exception('Token ausente en el constructor de ApiHttpServiceRequest');
        }

        $this->baseUri = rtrim($baseUri);

        $this->httpd = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent' => 'application/postscript',
                "Authorization" => "Bearer {$token}",
            ])
            ->withOptions([
                'base_uri' => $this->baseUri,
                'verify' => false,
            ])
            ->asJson();
    }

    public function sendPost($data, $endpoint = '', $retry = false)
    {
        $this->debugRequest('POST', $this->baseUri.$endpoint, $data);

        try {
            $response = $retry
                ? $this->httpd->retry(2, 100)->post($endpoint, $data)
                : $this->httpd->post($endpoint, $data);

            return $response->json();
        } catch (\Throwable $e) {
           throw new \Exception('POST request failed: ' . $e->getMessage());
        }
    }


    public function sendGet($endpoint = null, $retry = false)
    {
        $this->debugRequest('GET', $this->baseUri.$endpoint);

        try {
            return $retry
                ? $this->httpd->retry(2, 100)->get($endpoint)
                : $this->httpd->get($endpoint);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function sendPut($data, string $endpoint = '', $retry = false)
    {
        $this->debugRequest('PUT', $this->baseUri . $endpoint, $data);

        try {
            $this->returnResponse($this->httpd->put($endpoint, $data));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function sendPatch($data, string $endpoint = ''): void
    {
        $this->debugRequest('PATCH', $this->baseUri . $endpoint, $data);

        try {
            $this->returnResponse($this->httpd->patch($endpoint, $data));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function sendDelete($data, string $endpoint = ''): void
    {
        $this->debugRequest('DELETE', $this->baseUri . $endpoint, $data);

        try {
            $this->returnResponse($this->httpd->delete($endpoint, $data));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function seUrl($baseUri): string
    {
        $this->baseUri = $baseUri;
        return $this->baseUri;
    }

    private function returnResponse($response)
    {
        if (isset($response->CodigoError) && $response->CodigoError > 0){
            throw new \Exception($response->CodigoError . ' - ' . $response->MensajeError);
        }
        if (isset($response->Message) && $response->Message === 'Error.'){
            throw new \Exception($response->Message);
        }

        return $response;
    }

    private function debugRequest(string $method, string $url, array $data = []): void
    {
        if ($this->debug) {
            Log::warning("[API DEBUG] {$method} {$url}", [
                'body' => $data,
                'headers' => $this->httpd->getOptions()['headers'] ?? [],
            ]);
        }
    }
}
