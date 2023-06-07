<?php

namespace Task\Currencies\Tests;

use PHPUnit\Framework\TestCase;
use Task\Currencies\DbConnection;
use Task\Currencies\ConversionList;

class ConversionListTest extends TestCase
{
    private $dbConnection;
    private $conversionList;

    protected function setUp(): void
    {
        $this->dbConnection = new DbConnection();
        $this->conversionList = new ConversionList($this->dbConnection);
    }

    public function testGenerateTables()
    {
        ob_start();
        $this->conversionList->generateList();
        $output = ob_get_clean();

        $this->assertNotNull($output);
        $this->assertIsString($output);
    }
}
