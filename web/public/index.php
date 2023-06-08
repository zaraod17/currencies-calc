<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Task\Currencies\Form\FormHandler;
use Task\Currencies\Api\ApiConnect;
use Task\Currencies\Repository\CurrencyRatesRepository;
use Task\Currencies\Display\CurrencyRatesTable;
use Task\Currencies\Display\ConversionList;

$formHandler = new FormHandler();
$api = new ApiConnect();
$currenciesRepository = new CurrencyRatesRepository();
$currenciesTable = new CurrencyRatesTable();
$conversionList = new ConversionList();


$rates = $api->getExchangeRates();

if (!$rates) {
  return;
}
$currenciesRepository->saveExchangeRates($rates);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $formHandler->handleForm();
}

?>

<div style="display: flex; justify-content: space-around;">

  <?php

  $currenciesTable->generateTables();

  ?>

  <form method="POST" style="display: flex; flex-direction:column;">
    Kwota: <input type="number" name="amount" min="0" required><br>
    <label for="soruce_currency">
      Waluta źródłowa:
    </label> <?php

              $formHandler->generateSelect('source_currency'); ?>
    <label for="target_currency">Waluta docelowa:</label></label></label>
    <?php
    $formHandler->generateSelect('target_currency'); ?>
    <input type="submit" value="Przewalutuj">
  </form>

  <?php
  echo '<div>';


  $conversionList->generateList();

  echo  '</div>';
  ?>
</div>