<?php

use PHPUnit\Framework\TestCase;
use Task\Currencies\Api\ApiConnect;

class ApiConnectTest extends TestCase
{
    private $apiConnect;

    protected function setUp(): void
    {
        $this->apiConnect = new ApiConnect();
    }

    public function testGetExchangeRates()
    {

        $rates = $this->apiConnect->getExchangeRates();

        $this->assertIsArray($rates);  // Ensure the returned value is an array

        foreach ($rates as $rate) {
            $this->assertArrayHasKey('currency', $rate);  // Check if each rate has the 'currency' key
            $this->assertArrayHasKey('code', $rate);  // Check if each rate has the 'code' key
            $this->assertArrayHasKey('mid', $rate);  // Check if each rate has the 'mid' key

            $this->assertIsString($rate['currency']);  // Ensure 'currency' value is a string
            $this->assertIsString($rate['code']);  // Ensure 'code' value is a string
            $this->assertIsFloat($rate['mid']);  // Ensure 'mid' value is a float
        }
    }
}