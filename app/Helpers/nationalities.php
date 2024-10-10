<?php

use GuzzleHttp\Client;

if (!function_exists('getNationalities')) {
    function getNationalities(){

        $client = new Client();


        $response = $client->get('https://restcountries.com/v3.1/all');
        $countries_body = json_decode($response->getBody(), true);
        foreach (collect($countries_body ) as $key => $country) {
            $countries[] = $country['name']['common'];
        }

        return $countries;
    }
}

?>
