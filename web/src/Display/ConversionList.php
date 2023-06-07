<?php

declare(strict_types=1);

namespace Task\Currencies\Display;

use Task\Currencies\Repository\CurrencyRatesRepository;


class ConversionList
{
    private $currencyRatesRepository;

    public function __construct()
    {
        $this->currencyRatesRepository = new CurrencyRatesRepository();
    }


    public function generateList()
    {
        $results = $this->currencyRatesRepository->getCurrencyConversions();

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
