<?php

declare(strict_types=1);

namespace Task\Currencies;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class DbConnection
{
    private $host;
    private $dbname;
    private $username;
    private $password;

    public function __construct()
    {   
        $dotenv = Dotenv::createImmutable((dirname(__DIR__)));
        $dotenv->load();

        $this->host = $_ENV['MYSQL_DB_HOST'];
        $this->dbname = $_ENV['MYSQL_DATABASE'];
        $this->username = $_ENV['MYSQL_USER'];
        $this->password = $_ENV['MYSQL_PASSWORD'];
    }

    public function connectToDatabase()
    {
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $pdo = new PDO($dsn, $this->username, $this->password, $options);

            return $pdo;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}
