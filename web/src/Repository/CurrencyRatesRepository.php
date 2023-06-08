<?php

declare(strict_types=1);


namespace Task\Currencies\Repository;

use Task\Currencies\Database\DbConnection;
use PDO;

class CurrencyRatesRepository
{
    private $connection;

    public function __construct()
    {
        $dbConnection = new DbConnection();
        $this->connection = $dbConnection->connectToDatabase();
    }

    private function createCurrenciesTable(): void
    {

        $createTableQuery = "CREATE TABLE IF NOT EXISTS currencies (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code VARCHAR(3) NOT NULL,
            rate DECIMAL(10, 2) NOT NULL,
            currency VARCHAR(50) NOT NULL
        )";


        $this->connection->exec($createTableQuery);
    }



    public function saveExchangeRates(array $rates): void
    {
        if (!$rates) {
            echo "Error: No rates provieded";
            return;
        }

        $this->createCurrenciesTable();


        $rowCountQuery = "SELECT COUNT(*) FROM currencies";
        $rowCountStatement = $this->connection->query($rowCountQuery);
        $rowCount = $rowCountStatement->fetchColumn();

        if ($rowCount === 0) {
            // Table is empty, perform an insert
            $insertQuery = "INSERT INTO currencies (code, rate, currency) VALUES (:code, :rate, :currency)";
            $statement = $this->connection->prepare($insertQuery);
        } else {
            // Table has existing rows, perform an update
            $updateQuery = "UPDATE currencies SET rate = :rate, currency = :currency WHERE code = :code";
            $statement = $this->connection->prepare($updateQuery);
        }

        foreach ($rates as $rate) {
            $code = $rate['code'];
            $currencyRate = $rate['mid'];
            $currency = $rate['currency'];

            $statement->bindParam(':code', $code);
            $statement->bindParam(':rate', $currencyRate);
            $statement->bindParam(':currency', $currency);

            $statement->execute();
        }
    }

    public function getExchangeRates(): array
    {
        $query = "SELECT * FROM currencies";
        $statement = $this->connection->query($query);
        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}
