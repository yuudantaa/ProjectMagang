<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Session;

class ApiService
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'http://localhost:5022';
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout'  => 30,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function request($method, $endpoint, $data = [])
    {
        try {
            $options = [];

            if (!empty($data)) {
                if ($method === 'GET') {
                    $options['query'] = $data;
                } else {
                    $options['json'] = $data;
                }
            }

            $response = $this->client->request($method, $endpoint, $options);
            return json_decode($response->getBody(), true);

        } catch (RequestException $e) {
            $errorMessage = 'API Error: ';

            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $errorMessage .= $response->getStatusCode() . ' - ' . $response->getReasonPhrase();
            } else {
                $errorMessage .= $e->getMessage();
            }

            Session::flash('alert', $errorMessage);
            return null;
        }
    }
}
