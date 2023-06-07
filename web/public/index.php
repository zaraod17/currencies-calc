<?php


// require_once 'FormHandler.php';

// // Utwórz instancję klasy FormHandler
// $formHandler = new FormHandler();

// // Obsłuż formularz, jeśli został przesłany
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   $formHandler->handleForm();

// }
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Task\Currencies\FormHandler;

$formHandler = new FormHandler();

?>

<form method="POST">
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