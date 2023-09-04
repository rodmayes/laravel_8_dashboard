<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiHttpServiceRequest extends Http
{
    /**
     * @var bool - Active debug mode for view WrongResponses
     */
    public $debug;

    /**
     * key for connection to api
     *
     * @var [type]
     */
    protected $url;
    private $httpd;

    /**
     * Construct a Guzzle Client with some API configurations preload
     * ApiTcmService constructor.
     * @param array $config
     */
    public function __construct($url = '', $headers = [])
    {
        $this->url = $url;
        $this->httpd = Http::withOptions(['base_uri' => $url, 'verify' => false])->withHeaders($headers);
    }

    /**
     * @param $data
     * @param $url
     * @return object
     * @throws \Exception
     */
    public function sendPost($data, $url = '')
    {
        try{
            return $this->httpd->post($this->url.$url, $data)->json();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * @param $url
     * @return object
     * @throws \Exception
     */
    public function sendGet($url = null)
    {
        try {
            return $this->httpd->get($url)->json();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Simplified PUT request with only the form data that always returns the response
     * @param $url
     * @param $data
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function sendPut($data, $url = '')
    {
        try {
            $this->returnResponse($this->httpd->put($this->url.$url, $data)->json());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Simplified PATCH request with only the form data that always returns the response
     * @param $url
     * @param $data
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function sendPatch($data, $url = '')
    {
        try {
            $this->returnResponse($this->httpd->patch($this->url.$url, $data)->json());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * Simplified DELETE request with only the form data that always returns the response
     * @param $url
     * @param $data
     * @return null|\Psr\Http\Message\ResponseInterface
     */
    public function sendDelete($data, $url = '')
    {
        try {
            $this->returnResponse($this->httpd->delete($this->url.$url, $data)->json());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function seUrl($url)
    {
        $this->url = $url;
    }

    private function returnResponse($response)
    {
        if(isset($response->CodigoError) && $response->CodigoError > 0) throw new \Exception($response->CodigoError.' - '.$response->MensajeError);
        if(isset($response->Message) && $response->Message === 'Error.') throw new \Exception($response->Message);
        return $response;
    }
}
