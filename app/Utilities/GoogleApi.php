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

    /**
     * Computes for the distance
     */
    public function getDistance($origin, $destination)
    {
        $apiKey = env('GOOGLE_API_KEY');
        $response = $this->client->request(
            'GET',
            "$this->baseUri/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$apiKey"
        );

        return json_decode($response->getBody(), true);
    }

    /**
     * Translates a string destination to lat/long
     */
    public function geocode($location)
    {
        $apiKey = env('GOOGLE_API_KEY');
        $response = $this->client->request(
            'GET',
            "$this->baseUri/maps/api/geocode/json?address=$location&key=$apiKey"
        );

        return json_decode($response->getBody(), true);
    }
}
