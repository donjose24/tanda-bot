<?php

namespace App\Utilities;

use Carbon\Carbon;

class TandaApi
{
    private $user;
    private $client;
    private $baseUri = 'https://my.tanda.co/api/v2';

    public function __construct($token)
    {
        $client = new \GuzzleHttp\Client([
            'headers' => [
                'content-type' => 'application/json',
                'Authorization' => 'bearer ' . $token,
            ]
        ]);

        $response = $client->request('GET', $this->baseUri . '/users/me');

        $this->client = $client;
        $this->user = json_decode($response->getBody());

        return $this;
    }

    /**
     * Clock in function
     *
     * @return void
     */
    public function clockIn()
    {
        $now = Carbon::now()->timestamp;
        $this->client->request('POST', $this->baseUri . '/clockins', [
            'json' => [
                'user_id' => $this->user->id,
                'type' => 'clockin',
                'time' => $now,
            ]
        ]);
    }

    /**
     * Clockout Function
     *
     * @return void
     */
    public function clockOut()
    {
        $now = Carbon::now()->timestamp;
        $this->client->request('POST', $this->baseUri . '/clockins', [
            'json' => [
                'user_id' => $this->user->id,
                'type' => 'clockout',
                'time' => $now,
            ]
        ]);
    }
}
