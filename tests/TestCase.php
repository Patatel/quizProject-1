<?php
namespace QuizProject\Tests;

use PDO;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected PDO $pdo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Provide MySQL NOW() equivalent for SQLite
        if (method_exists($this->pdo, 'sqliteCreateFunction')) {
            $this->pdo->sqliteCreateFunction('NOW', fn() => date('Y-m-d H:i:s'));
        }
        $this->createSchema();
        // Override global pdo used in models
        $GLOBALS['pdo'] = $this->pdo;
    }

    private function createSchema(): void
    {
        $schema = [
            "CREATE TABLE Users (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, email TEXT UNIQUE, password TEXT, role TEXT)",
            "CREATE TABLE quiz (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, description TEXT, user_id INTEGER, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)",
            "CREATE TABLE questions (id INTEGER PRIMARY KEY AUTOINCREMENT, text TEXT)",
            "CREATE TABLE questionquiz (question_id INTEGER, quiz_id INTEGER)",
            "CREATE TABLE answers (id INTEGER PRIMARY KEY AUTOINCREMENT, question_id INTEGER, text TEXT, is_correct INTEGER)",
            "CREATE TABLE useranswers (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, quiz_id INTEGER, question_id INTEGER, user_answer TEXT, is_correct INTEGER)",
            "CREATE TABLE results (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, quiz_id INTEGER, score INTEGER, total_questions INTEGER, percentage REAL, date_passed DATETIME)"
        ];
        foreach ($schema as $sql) {
            $this->pdo->exec($sql);
        }
    }
}
