<?php

declare(strict_types=1);


namespace Task\Currencies\Form;

use Task\Currencies\Repository\CurrencyRatesRepository;
use Task\Currencies\Repository\ConversionRatesRepository;

class FormHandler
{
    private $dbRepository;
    private $conversionRatesRepository;

    public function __construct()
    {
        $this->dbRepository = new CurrencyRatesRepository();
        $this->conversionRatesRepository = new ConversionRatesRepository();
    }
    public function handleForm()
    {
        $amount = $_POST['amount'];

        if (!$amount || ($amount <= 0)) {
            throw new \Exception('Amount is not valid! It must be greater that 0.');
        }

        $sourceCurrency = $_POST['source_currency'];
        $targetCurrency = $_POST['target_currency'];

        $convertedAmount = $this->conversionRatesRepository->convertCurrency($amount, $sourceCurrency, $targetCurrency);

        $this->conversionRatesRepository->saveCurrencyConversion($sourceCurrency, $targetCurrency, $convertedAmount);
    }


    public function generateSelect(string $name)
    {

        $results = $this->dbRepository->getExchangeRates();

        echo '<select name="' . $name . '" required>';

        foreach ($results as $result) {
            echo '<option value="' . $result['code'] . '">' . $result['currency'] . '</option>';
        }

        echo '</select>';
    }
}
