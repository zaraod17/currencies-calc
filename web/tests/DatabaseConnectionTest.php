<?php

use PHPUnit\Framework\TestCase;
use Task\Currencies\Database\DbConnection;

class DatabaseConnectionTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {

        $this->pdo = new DbConnection();
    }

    public function testConnection()
    {
        $this->assertInstanceOf(PDO::class, $this->pdo->connectToDatabase());
    }
}
