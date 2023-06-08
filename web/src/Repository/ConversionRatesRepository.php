<?php

declare(strict_types=1);

namespace Task\Currencies\Repository;
use Task\Currencies\Database\DbConnection;
use PDO;


class ConversionRatesRepository
{

    private $dbRepository;
    private $connection;


    public function __construct()
    {
        $this->dbRepository = new CurrencyRatesRepository();
        $dbConnection = new DbConnection();
        $this->connection = $dbConnection->connectToDatabase();
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


    public function convertCurrency($amount, $sourceCurrency, $targetCurrency)
    {
        $exchangeRates = $this->dbRepository->getExchangeRates();

        $sourceRate = 0;
        $targetRate = 0;

        foreach ($exchangeRates as $rate) {
            if ($sourceCurrency === $rate['code']) {
                $sourceRate = $rate['rate'];
            } else if ($targetCurrency === $rate['code']) {
                $targetRate = $rate['rate'];
            }
        }

        if ($sourceCurrency === $targetCurrency) {
            return $amount;
        }

        if ($amount <= 0) {
            return 0;
        }

        $convertedAmount = ($sourceRate / $targetRate) * $amount;
        $convertedAmount = round($convertedAmount, 2);

        return $convertedAmount;
    }

    public function getCurrencyConversions()
    {
        $this->createConversionsTable();

        $query = "SELECT * FROM conversions";
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
