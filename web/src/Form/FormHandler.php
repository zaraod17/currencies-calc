<?php

declare(strict_types=1);


namespace Task\Currencies\Form;

use Task\Currencies\Database\DbConnection;
use Task\Currencies\Repository\CurrencyRatesRepository;

class FormHandler
{
    private $dbRepository;

    public function __construct()
    {
        $this->dbRepository = new CurrencyRatesRepository();
    }
    public function handleForm()
    {
        $amount = $_POST['amount'];

        if (!$amount || ($amount <= 0)) {
            throw new \Exception('Amount is not valid! It must be greater that 0.');
        }

        $sourceCurrency = $_POST['source_currency'];
        $targetCurrency = $_POST['target_currency'];

        $convertedAmount = $this->convertCurrency($amount, $sourceCurrency, $targetCurrency);

        $this->dbRepository->saveCurrencyConversion($sourceCurrency, $targetCurrency, $convertedAmount);
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

        $convertedAmount = ($amount / $sourceRate) * $targetRate;
        $convertedAmount = round($convertedAmount, 2);

        return $convertedAmount;
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
