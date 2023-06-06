<?php

namespace Task\Currencies\Tests;

use PHPUnit\Framework\TestCase;
use Task\Currencies\CurrencyRatesTable;
use Task\Currencies\DbConnection;
use Task\Currencies\CurrencyRatesRepository;
use Task\Currencies\ApiConnect;

class CurrencyRatesTableTest extends TestCase
{
    private $table;
    private $dbConnection;
    private $dbRepository;
    private $apiConnection;

    protected function setUp(): void
    {
        $this->dbConnection = new DbConnection();
        $this->table = new CurrencyRatesTable($this->dbConnection);
        $this->dbRepository = new CurrencyRatesRepository($this->dbConnection);
        $this->apiConnection = new ApiConnect();
    }

    public function testGenerateTables()
    {
        $date = date('Y-m-d');

        $rates = $this->apiConnection->getExchangeRates($date);

        $this->dbRepository->saveExchangeRates($rates);

        ob_start();
        $this->table->generateTables();
        $output = ob_get_clean();

        $this->assertStringNotContainsString('No data.', $output);
        $this->assertIsString($output);
    }
}
