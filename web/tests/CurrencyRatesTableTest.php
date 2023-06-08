<?php

namespace Task\Currencies\Tests;

use PHPUnit\Framework\TestCase;
use Task\Currencies\Display\CurrencyRatesTable;
use Task\Currencies\Database\DbConnection;
use Task\Currencies\Repository\CurrencyRatesRepository;
use Task\Currencies\Api\ApiConnect;

class CurrencyRatesTableTest extends TestCase
{
    private $table;
    private $dbConnection;
    private $dbRepository;
    private $apiConnection;

    protected function setUp(): void
    {
        $this->dbConnection = new DbConnection();
        $this->table = new CurrencyRatesTable();
        $this->dbRepository = new CurrencyRatesRepository();
        $this->apiConnection = new ApiConnect();
    }

    public function testGenerateTables()
    {

        $rates = $this->apiConnection->getExchangeRates();

        $this->dbRepository->saveExchangeRates($rates);

        ob_start();
        $this->table->generateTables();
        $output = ob_get_clean();

        $this->assertStringNotContainsString('No data.', $output);
        $this->assertIsString($output);
    }
}
