<?php
$host = 'db';
$db   = 'quizproject';
$user = 'user';
$pass = 'password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Créez la base de données si elle n'existe pas
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
    $pdo->exec("USE $db");

    // Créez les tables et insérez les données
    $sql = <<<SQL
    DROP TABLE IF EXISTS `answers`;
    CREATE TABLE IF NOT EXISTS `answers` (
      `id` int NOT NULL AUTO_INCREMENT,
      `question_id` int NOT NULL,
      `text` text NOT NULL,
      `is_correct` tinyint(1) NOT NULL DEFAULT '0',
      PRIMARY KEY (`id`),
      KEY `question_id` (`question_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

    INSERT INTO `answers` (`id`, `question_id`, `text`, `is_correct`) VALUES
    (24, 9, 'Noir', 0),
    (25, 9, 'Brun', 1),
    (26, 9, 'Roux', 0),
    (27, 10, '1', 0),
    (28, 10, '2', 1);

    DROP TABLE IF EXISTS `questionquiz`;
    CREATE TABLE IF NOT EXISTS `questionquiz` (
      `question_id` int NOT NULL,
      `quiz_id` int NOT NULL,
      PRIMARY KEY (`question_id`,`quiz_id`),
      KEY `quiz_id` (`quiz_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

    INSERT INTO `questionquiz` (`question_id`, `quiz_id`) VALUES
    (9, 2),
    (10, 3);

    DROP TABLE IF EXISTS `questions`;
    CREATE TABLE IF NOT EXISTS `questions` (
      `id` int NOT NULL AUTO_INCREMENT,
      `text` text NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

    INSERT INTO `questions` (`id`, `text`) VALUES
    (9, 'Un grizzly est en majorité ?'),
    (10, 'test');

    DROP TABLE IF EXISTS `quiz`;
    CREATE TABLE IF NOT EXISTS `quiz` (
      `id` int NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `description` text,
      `user_id` int NOT NULL,
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

    INSERT INTO `quiz` (`id`, `title`, `description`, `user_id`, `created_at`) VALUES
    (1, 'Test', 'test', 2, '2025-05-29 12:15:44'),
    (2, 'Test 2', 'Quizz sur les ours', 2, '2025-05-29 13:42:20'),
    (3, 'lea', 'jun', 2, '2025-05-29 14:32:24');

    DROP TABLE IF EXISTS `results`;
    CREATE TABLE IF NOT EXISTS `results` (
      `id` int NOT NULL AUTO_INCREMENT,
      `user_id` int NOT NULL,
      `quiz_id` int NOT NULL,
      `score` int NOT NULL,
      `total_questions` int NOT NULL,
      `percentage` float NOT NULL,
      `date_passed` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `quiz_id` (`quiz_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

    INSERT INTO `results` (`id`, `user_id`, `quiz_id`, `score`, `total_questions`, `percentage`, `date_passed`) VALUES
    (1, 2, 2, 1, 1, 100, '2025-05-29 15:58:06'),
    (2, 2, 2, 0, 1, 0, '2025-05-29 15:58:18');

    DROP TABLE IF EXISTS `useranswers`;
    CREATE TABLE IF NOT EXISTS `useranswers` (
      `id` int NOT NULL AUTO_INCREMENT,
      `user_id` int NOT NULL,
      `quiz_id` int NOT NULL,
      `question_id` int NOT NULL,
      `user_answer` text NOT NULL,
      `is_correct` tinyint(1) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `quiz_id` (`quiz_id`),
      KEY `question_id` (`question_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

    INSERT INTO `useranswers` (`id`, `user_id`, `quiz_id`, `question_id`, `user_answer`, `is_correct`) VALUES
    (1, 2, 2, 9, 'Brun', 1),
    (2, 2, 2, 9, 'Noir', 0);

    DROP TABLE IF EXISTS `users`;
    CREATE TABLE IF NOT EXISTS `users` (
      `id` int NOT NULL AUTO_INCREMENT,
      `name` varchar(255) NOT NULL,
      `email` varchar(255) NOT NULL,
      `password` varchar(255) NOT NULL,
      `role` varchar(50) DEFAULT 'user',
      `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

    INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
    (2, 'Lukas', 'lukasbouhlel@gmail.com', "\$2y\$10\$SJIiwA.ibMIgaKhSqGWEU.eHEI7tnpFgSi8mhrLLQHX1KCmGnxG1G", 'user', '2025-05-29 12:15:05');

    ALTER TABLE `answers`
      ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

    ALTER TABLE `questionquiz`
      ADD CONSTRAINT `questionquiz_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
      ADD CONSTRAINT `questionquiz_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;

    ALTER TABLE `quiz`
      ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

    ALTER TABLE `results`
      ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;

    ALTER TABLE `useranswers`
      ADD CONSTRAINT `useranswers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      ADD CONSTRAINT `useranswers_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE,
      ADD CONSTRAINT `useranswers_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
    SQL;

    $pdo->exec($sql);

    echo "Migration exécutée avec succès.";
} catch (PDOException $e) {
    die("Erreur de migration : " . $e->getMessage());
}
?>