<?php

namespace Task\Currencies;

use Task\Currencies\DbConnection;
use PDO;

class CurrencyRatesRepository
{
    private $connection;

    public function __construct(DbConnection $dbConnection)
    {
        $this->connection = $dbConnection->connectToDatabase();
    }

    private function createCurrenciesTable(): void
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

    public function createConversionsTable(): void
    {
        $createTableQuery = "CREATE TABLE IF NOT EXISTS conversions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            source_currency VARCHAR(3) NOT NULL,
            target_currency VARCHAR(3) NOT NULL,
            amount DECIMAL(10, 2) NOT NULL
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

    public function getEchangeRates(): array
    {
        $query = "SELECT * FROM currencies ORDER BY date DESC";
        $statement = $this->connection->query($query);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function saveCurrencyConversion($sourceCurrency, $targetCurrency, $amount)
    {
        $this->createConversionsTable();

        $insertQuery = "INSERT INTO conversions (source_currency, target_currency, amount) VALUES (:source_currency, :target_currency, :amount)";
        $statement = $this->connection->prepare($insertQuery);

        $statement->bindParam(':source_currency', $sourceCurrency);
        $statement->bindParam(':target_currency', $targetCurrency);
        $statement->bindParam(':amount', $amount);

        $statement->execute();

    }
}
