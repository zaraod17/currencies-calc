<?php

namespace Task\Currencies;

use Task\Currencies\DbConnection;
use Task\Currencies\CurrencyRatesRepository;

class CurrencyRatesTable
{
    private $connection;
    private $currencyRatesRepository;

    public function __construct(DbConnection $dbConnection)
    {
        $this->connection = $dbConnection;
        $this->currencyRatesRepository = new CurrencyRatesRepository($this->connection);
    }

    public function generateTables()
    {

        $results = $this->currencyRatesRepository->getExchangeRates();

        if ($results) {
            echo '<table>';
            echo '<tr><th>Currency</th><th>Code</th><th>Exchange</th><th>Date</th></tr>';

            foreach ($results as $result) {
                echo '<tr>';
                echo '<td>' . $result['currency'] . '</td>';
                echo '<td>' . $result['code'] . '</td>';
                echo '<td>' . $result['rate'] . '</td>';
                echo '<td>' . $result['date'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "No data.";
        }
    }
}
