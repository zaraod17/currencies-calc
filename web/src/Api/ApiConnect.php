<?php

declare(strict_types=1);

namespace Task\Currencies\Api;

class ApiConnect
{
    private $baseUrl = 'http://api.nbp.pl/api/';

    public function getExchangeRates($date)
    {
        $url = $this->baseUrl . 'exchangerates/tables/A/' . $date . '/?format=json';
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        return $data[0]['rates'];
    }
}
