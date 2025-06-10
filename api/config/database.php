<?php
$host = 'localhost';
$db = 'quizproject'; // Nom de ta base
$user = 'root';
$pass = ''; // selon ta config

// Allow overriding connection settings via environment variables for testing
$dsn = getenv('DATABASE_DSN');
$userEnv = getenv('DATABASE_USER');
$passEnv = getenv('DATABASE_PASS');

if ($dsn === false || $dsn === '') {
    $dsn = "mysql:host=$host;dbname=$db";
}
if ($userEnv !== false) {
    $user = $userEnv;
}
if ($passEnv !== false) {
    $pass = $passEnv;
}

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
