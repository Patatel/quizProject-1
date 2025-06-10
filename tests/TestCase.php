<?php
namespace QuizProject\Tests;

use PDO;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected PDO $pdo;    protected function setUp(): void
    {
        parent::setUp();
        $dsn = getenv('DATABASE_DSN') ?: 'mysql:host=localhost;dbname=quizproject_test';
        $user = getenv('DATABASE_USER') ?: 'root';
        $pass = getenv('DATABASE_PASS') ?: '';
       
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
        // Clean up existing tables
        $this->dropTables();
        $this->createSchema();
        // Override global pdo used in models
        $GLOBALS['pdo'] = $this->pdo;
    }    private function createSchema(): void
    {
        $schema = [
            "CREATE TABLE Users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(191),
                email VARCHAR(191) UNIQUE,
                password VARCHAR(191),
                role VARCHAR(50)
            )",
            "CREATE TABLE quiz (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(191),
                description TEXT,
                user_id INT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
            )",
            "CREATE TABLE questions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                text TEXT
            )",
            "CREATE TABLE questionquiz (
                question_id INT,
                quiz_id INT,
                FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE,
                FOREIGN KEY (quiz_id) REFERENCES quiz(id) ON DELETE CASCADE
            )",
            "CREATE TABLE answers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                question_id INT,
                text TEXT,
                is_correct TINYINT(1),
                FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
            )",
            "CREATE TABLE useranswers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                quiz_id INT,
                question_id INT,
                user_answer TEXT,
                is_correct TINYINT(1),
                FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
                FOREIGN KEY (quiz_id) REFERENCES quiz(id) ON DELETE CASCADE,
                FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
            )",
            "CREATE TABLE results (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                quiz_id INT,
                score INT,
                total_questions INT,
                percentage DECIMAL(5,2),
                date_passed DATETIME
            )"
        ];
        foreach ($schema as $sql) {
            $this->pdo->exec($sql);
        }
    }
    private function dropTables(): void
    {
        $tables = ['results', 'useranswers', 'answers', 'questionquiz', 'questions', 'quiz', 'Users'];
       
        // Disable foreign key checks temporarily
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
       
        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS $table");
        }
       
        // Re-enable foreign key checks
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    }
}
