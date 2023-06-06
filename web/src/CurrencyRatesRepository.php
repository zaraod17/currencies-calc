<?php

namespace Task\Currencies;

use Task\Currencies\DbConnection;

class CurrencyRatesRepository
{
    private $connection;

    public function __construct(DbConnection $dbConnection)
    {
        $this->connection = $dbConnection->connectToDatabase();
    }

    private function createCurrenciesTable()
    {

        $createTableQuery = "CREATE TABLE IF NOT EXISTS currencies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(3) NOT NULL,
            rate DECIMAL(10, 2) NOT NULL,
            currency VARCHAR(50) NOT NULL,
            date DATE NOT NULL
        )";

        $this->connection->exec($createTableQuery);
    }

    public function saveExchangeRates(array $rates): void
    {
        $this->createCurrenciesTable();

        $date = date('Y-m-d');

        $insertQuery = "INSERT INTO currencies (code, rate, currency , date) VALUES (:code, :rate, :currency, :date)";
        $statement = $this->connection->prepare($insertQuery);

        foreach ($rates as $rate) {
            $code = $rate['code'];
            $currencyRate = $rate['mid'];
            $currency = $rate['currency'];

            $statement->bindParam(':code', $code);
            $statement->bindParam(':rate', $currencyRate);
            $statement->bindParam(':currency', $currency);
            $statement->bindParam(':date', $date);

            $statement->execute();
        }
    }
}