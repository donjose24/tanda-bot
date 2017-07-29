<?php

namespace App\Utilities;

class UberApi
{
    private $client;
    private $baseUri='https://api.uber.com/v1.2';

    public function __construct()
    {
        $token = env('UBER_SERVER_TOKEN');
        $client = new \GuzzleHttp\Client([
            'headers' => [
                'Authorization' => 'Token ' . $token,
            ]
        ]);

        $this->client = $client;
    }

    public function getQuotes($location, $endLat, $endLng)
    {
        $token = env('UBER_SERVER_TOKEN');
        $response = $this->client->request('GET', "$this->baseUri/estimates/price?start_latitude=" .$location->getLatitude() . "&start_longitude=" . $location->getLongitude() . "&end_latitude=$endLat&end_longitude=$endLng&token=$token");

        return json_decode($response->getBody(), true);
    }
}
