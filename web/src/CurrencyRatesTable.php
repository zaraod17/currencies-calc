<?php

namespace Task\Currencies;

use Task\Currencies\DbConnection;
use PDO;

class CurrencyRatesTable
{
    private $connection;

    public function __construct(DbConnection $dbConnection)
    {
        $this->connection = $dbConnection;
    }

    public function generateTables()
    {
        $query = "SELECT * FROM currencies ORDER BY date DESC";
        $statement = $this->connection->connectToDatabase()->query($query);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            echo '<table>';
            echo '<tr><th>Currency</th><th>Code</th><th>Exchange</th><th>Date</th></tr>';

            foreach ($results as $result) {
                echo '<tr>';
                echo '<td>' . $result['currency'] . '</td>';
                echo '<td>' . $result['code'] . '</td>';
                echo '<td>' . $result['rate'] . '</td>';
                echo '<td>' . $result['date'] . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo "No data.";
        }
    }
}
