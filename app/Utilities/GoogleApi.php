<?php

namespace App\Utilities;

use Carbon\Carbon;

class GoogleApi
{
    private $baseUri = 'https://maps.googleapis.com';
    private $client;

    /**
     * undocumented function
     *
     * @return void
     */
    public function __construct()
    {
        $client = new \GuzzleHttp\Client();
        $this->client = $client;
    }

    public function getDistance($origin, $destination)
    {
        $apiKey = env('GOOGLE_API_KEY');
        $response = $this->client->request(
            'GET',
            "$this->baseUri/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$apiKey"
        );
    }
}
