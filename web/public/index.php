<?php

// require_once 'FormHandler.php';

// // Utwórz instancję klasy FormHandler
// $formHandler = new FormHandler();

// // Obsłuż formularz, jeśli został przesłany
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//   $formHandler->handleForm();
// }

?>

<form method="POST">
  Kwota: <input type="number" name="amount" required><br>
  Waluta źródłowa: <input type="text" name="source_currency" required><br>
  Waluta docelowa: <input type="text" name="target_currency" required><br>
  <input type="submit" value="Przewalutuj">
</form>