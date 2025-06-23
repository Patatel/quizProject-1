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
  $pdo = new PDO($dsn, $user, $pass, $options); // ← INDISPENSABLE

  $pdo->exec("CREATE DATABASE IF NOT EXISTS $db");
  $pdo->exec("USE $db");

  // ensuite ton bloc $sql = <<<SQL ... etc.


  // 1. CRÉER LES TABLES (sans contraintes)
  $sql = <<<SQL
DROP TABLE IF EXISTS `answers`, `questionquiz`, `questions`, `quiz`, `results`, `useranswers`, `users`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `quiz` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `questions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `question_id` int NOT NULL,
  `text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `questionquiz` (
  `question_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  PRIMARY KEY (`question_id`, `quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `score` int NOT NULL,
  `total_questions` int NOT NULL,
  `percentage` float NOT NULL,
  `date_passed` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `useranswers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `question_id` int NOT NULL,
  `user_answer` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. INSÉRER LES DONNÉES DANS L’ORDRE LOGIQUE
INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(2, 'Jean', 'jean@example.com', 'motdepasse');

INSERT INTO `quiz` (`id`, `title`, `description`, `user_id`, `created_at`) VALUES
(1, 'Test 2', 'Quizz sur les ours', 2, NOW()),
(2, 'lea', 'jun', 2, NOW());

INSERT INTO `questions` (`id`, `text`) VALUES
(9, 'Un grizzly est en majorité ?'),
(10, 'test');

INSERT INTO `answers` (`id`, `question_id`, `text`, `is_correct`) VALUES
(24, 9, 'Noir', 0),
(25, 9, 'Brun', 1),
(26, 9, 'Roux', 0),
(27, 10, '1', 0),
(28, 10, '2', 1);

INSERT INTO `questionquiz` (`question_id`, `quiz_id`) VALUES
(9, 1),
(10, 2);

INSERT INTO `results` (`id`, `user_id`, `quiz_id`, `score`, `total_questions`, `percentage`, `date_passed`) VALUES
(1, 2, 2, 1, 1, 100, NOW()),
(2, 2, 2, 0, 1, 0, NOW());

INSERT INTO `useranswers` (`id`, `user_id`, `quiz_id`, `question_id`, `user_answer`, `is_correct`) VALUES
(1, 2, 2, 9, 'Brun', 1),
(2, 2, 2, 9, 'Noir', 0);

-- 3. AJOUTER LES CLÉS ÉTRANGÈRES EN DERNIER
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

ALTER TABLE `questionquiz`
  ADD CONSTRAINT `questionquiz_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `questionquiz_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;

ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE;

ALTER TABLE `useranswers`
  ADD CONSTRAINT `useranswers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `useranswers_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `useranswers_ibfk_3` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
SQL;
  $pdo->exec($sql);
  echo "✅ Migration exécutée avec succès.";
} catch (PDOException $e) {
  die("❌ Erreur de migration : " . $e->getMessage());
}
