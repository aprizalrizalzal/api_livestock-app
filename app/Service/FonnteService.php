<?php

namespace App\Service;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class FonnteService
{
    protected $client;
    protected $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->client = new Client();

        $apiKey = 'bEjiPAP2AArSgPT4DmQG';

        $this->apiKey = $apiKey;
    }

    public function sendMessage($to, $message, $options = [])
    {
        if (empty($this->apiKey)) {
            throw new \Exception('API key is not set.');
        }

        $response = $this->client->post('https://api.fonnte.com/send', [
            'headers' => [
                'Authorization' => $this->apiKey,
                'Accept' => 'application/json',
            ],
            'form_params' => array_merge([
                'target' => $to,
                'message' => $message,
            ], $options),
        ]);

        return json_decode($response->getBody(), true);
    }
}
