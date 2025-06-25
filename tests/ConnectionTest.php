<?php
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase {
    public function testCanConnectToDatabase() {
        $dsn = getenv("DATABASE_DSN");
        $user = getenv("DB_USER");
        $pass = getenv("DB_PASSWORD");

        echo "\nUsing DSN: $dsn\n";

        $pdo = new PDO($dsn, $user, $pass);
        $this->assertInstanceOf(PDO::class, $pdo);
    }
}
