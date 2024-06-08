<?php
require_once 'Database.php';

class DatabaseTest extends PHPUnit\Framework\TestCase
{
    private $database;

    protected function setUp(): void
    {
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $charset = 'utf8';
        $dbname = 'test_db';
        $logFile = '/path/to/logs/errors.log';
        $adminEmail = 'admin@example.com';

        $this->database = new Database($servername, $username, $password, $charset, $dbname, $logFile, $adminEmail);
        $this->database->connect();
    }

    protected function tearDown(): void
    {
        $this->database = null;
    }

    public function testConnect()
    {
        $this->assertInstanceOf(PDO::class, $this->database->returnConnection());
    }

    public function testQuery()
    {
        $query = 'SELECT * FROM users';
        $result = $this->database->query($query);
        $this->assertInstanceOf(PDOStatement::class, $result);
    }

    public function testPrepare()
    {
        $query = 'INSERT INTO users (name, email) VALUES (?, ?)';
        $stmt = $this->database->prepare($query);
        $this->assertInstanceOf(PDOStatement::class, $stmt);
    }

    public function testLastInsertId()
    {
        $query = 'INSERT INTO users (name, email) VALUES (?, ?)';
        $stmt = $this->database->prepare($query);
        $stmt->execute(['John Doe', 'john@example.com']);
        $lastInsertId = $this->database->lastInsertId();
        $this->assertIsInt($lastInsertId);
    }
}