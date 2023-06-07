<?php

use PHPUnit\Framework\TestCase;
use Task\Currencies\DbConnection;
use Task\Currencies\CurrencyRatesRepository;
use Task\Currencies\FormHandler;

use PDO;

class FormHandlerTest extends TestCase
{

    private $formHandler;
    private $currenciesRepository;
    private $dbConnection;

    protected function setUp(): void
    {
        $this->formHandler = new FormHandler();
        $this->dbConnection = new DbConnection();
        $this->currenciesRepository = new CurrencyRatesRepository($this->dbConnection);
    }

    protected function tearDown(): void
    {
        $connection = $this->dbConnection->connectToDatabase();
        $connection->exec("DELETE FROM conversions WHERE amount = 85.88");
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

    public function testHandleForm()
    {
        $amount = $_POST['amount'] = 100.00;
        $sourceCurrency = $_POST['source_currency'] = 'USD';
        $targetCurrency = $_POST['target_currency'] = 'EUR';

        $convertedAmount = $this->formHandler->convertCurrency($amount, $sourceCurrency, $targetCurrency);

        $this->currenciesRepository->createConversionsTable();

        $this->currenciesRepository->saveCurrencyConversion($sourceCurrency, $targetCurrency, $convertedAmount);

        $statement = $this->dbConnection->connectToDatabase()->query('SELECT * FROM conversions WHERE amount = 85.88');
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $this->assertNotNull($result);
    }
}
