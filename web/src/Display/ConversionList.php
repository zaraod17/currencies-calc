<?php

declare(strict_types=1);

namespace Task\Currencies\Display;

use Task\Currencies\Repository\ConversionRatesRepository;


class ConversionList
{
    private $conversionRatesRepository;

    public function __construct()
    {
        $this->conversionRatesRepository = new ConversionRatesRepository();
    }


    public function generateList()
    {
        $results = $this->conversionRatesRepository->getCurrencyConversions();

        if ($results) {
            foreach ($results as $result) {
                echo 'Waluta źródłowa:' . $result['source_currency'] . '<br>';
                echo 'Waluta docelowa:' . $result['target_currency'] . '<br>';
                echo 'Przewalutowana kwota:' . $result['amount'] . '<br>';
                echo "<br>";
            }
        }

        else {
            echo "Brak danych.";
        }
    }
}
