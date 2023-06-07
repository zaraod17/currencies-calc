<?php

namespace Task\Currencies;

use Task\Currencies\DbConnection;
use Task\Currencies\CurrencyRatesRepository;


class ConversionList
{
    private $connection;
    private $currencyRatesRepository;

    public function __construct(DbConnection $dbConnection)
    {
        $this->connection = $dbConnection;
        $this->currencyRatesRepository = new CurrencyRatesRepository($this->connection);
    }


    public function generateList()
    {
        $results = $this->currencyRatesRepository->getCurrencyConversions();

        foreach ($results as $result) {
            echo 'Waluta źródłowa:' . $result['source_currency'] . '<br>';
            echo 'Waluta docelowa:' . $result['target_currency'] . '<br>';
            echo 'Przewalutowana kwota:' . $result['amount'] . '<br>';
            echo "<br>";
        }
    }
}
