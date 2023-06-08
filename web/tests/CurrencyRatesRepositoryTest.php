<?php

use PHPUnit\Framework\TestCase;
use Task\Currencies\Repository\CurrencyRatesRepository;
use Task\Currencies\Api\ApiConnect;
use Task\Currencies\Database\DbConnection;



class CurrencyRatesRepositoryTest extends TestCase
{
    private $repository;
    private $dbConnection;
    private $apiConnection;


    protected function setUp(): void
    {
        $this->dbConnection = new DbConnection();

        $this->apiConnection = new ApiConnect();

        $this->repository = new CurrencyRatesRepository();
    }

    protected function tearDown(): void
    {
        // Drop table currencies after each test

        $this->dropCurrenciesTable();
    }


    public function testSaveExchangeRates()
    {


        $rates = $this->apiConnection->getExchangeRates();

        $this->repository->saveExchangeRates($rates);

        // Verify that the rates were saved correctly
        $statement = $this->dbConnection->connectToDatabase()->query('SELECT * FROM currencies');
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(count($rates), $result);

        foreach ($result as $rate) {
            $this->assertArrayHasKey('currency', $rate);
            $this->assertArrayHasKey('rate', $rate);

            $this->assertIsString($rate['currency']);
            $this->assertIsFloat(floatval($rate['rate']));
        }
    }
    public function dropCurrenciesTable()
    {
        $connection = $this->dbConnection->connectToDatabase();
        $connection->exec("DROP TABLE IF EXISTS currencies");
    }
}
