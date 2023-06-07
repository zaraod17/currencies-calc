<?php

namespace Task\Currencies\Tests;

use PHPUnit\Framework\TestCase;
use Task\Currencies\Display\ConversionList;

class ConversionListTest extends TestCase
{
    private $conversionList;

    protected function setUp(): void
    {
        $this->conversionList = new ConversionList();
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
