<?php

use PHPUnit\Framework\TestCase;
use Task\Currencies\DbConnection;
use Task\Currencies\CurrencyRatesRepository;
use Task\Currencies\FormHandler;

class FormHandlerTest extends TestCase
{

    private $formHandler;

    protected function setUp(): void
    {
        $this->formHandler = new FormHandler();
    }

    protected function tearDown(): void
    {
    }

    public function testConvertCurrency()
    {
        ob_start();
        $this->formHandler->generateSelect('source_currency');
        $output = ob_get_clean();

        $amount = 100;
        $sourceCurrency = 'USD';
        $targetCurrency = 'EUR';


        $convertedAmount = $this->formHandler->convertCurrency($amount, $sourceCurrency, $targetCurrency);

        $this->assertStringNotContainsString($output, '');
        $this->assertIsFloat($convertedAmount);
    }
}
