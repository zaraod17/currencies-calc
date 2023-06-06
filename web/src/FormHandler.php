<?php

namespace Task\Currencies;

use Task\Currencies\DbConnection;
use Task\Currencies\CurrencyRatesRepository;

class FormHandler
{
    private $dbConnection;
    private $dbRepository;

    public function __construct()
    {
        $this->dbConnection = new DbConnection();
        $this->dbRepository = new CurrencyRatesRepository($this->dbConnection);
    }
    public function handleForm()
    {
        $amount = $_POST['amount'];
        $sourceCurrency = $_POST['source_currency'];
        $targetCurrency = $_POST['target_currency'];

        $convertedAmount = $this->convertCurrency($amount, $sourceCurrency, $targetCurrency);

        $this->dbRepository->saveCurrencyConversion($sourceCurrency, $targetCurrency, $convertedAmount);
    }

    public function convertCurrency($amount, $sourceCurrency, $targetCurrency)
    {
        $exchangeRates = $this->dbRepository->getEchangeRates();

        $sourceRate = null;
        $targetRate = null;

        foreach ($exchangeRates as $rate) {
            if ($sourceCurrency === $rate['code']) {

                $sourceRate = $rate['rate'];
            } else if ($targetCurrency === $rate['code']) {

                $targetRate = $rate['rate'];
            }
        }

        $convertedAmount = ($amount / $sourceRate) * $targetRate;

        $convertedAmount = round($convertedAmount, 2);

        return $convertedAmount;
    }
    public function generateSelect(string $name)
    {

        $results = $this->dbRepository->getEchangeRates();

        echo '<select name="' . $name . '" required>';

        foreach ($results as $result) {
            echo '<option value="' . $result['code'] . '">' . $result['currency'] . '</option>';
        }

        echo '</select>';
    }
}