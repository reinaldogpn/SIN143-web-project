-- SCRIPT CRIAÇÃO DO BANCO DE DADOS --

DROP DATABASE IF EXISTS `pseudoeventim`;
CREATE DATABASE IF NOT EXISTS `pseudoeventim`;
USE `pseudoeventim`;

CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `cpf` VARCHAR(11) NOT NULL UNIQUE,
    `phone` VARCHAR(11),
    `address` TEXT,
    `avatar` TEXT,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(30) NOT NULL
);

CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE `events` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL UNIQUE,
    `description` TEXT,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10, 2),
    `image` TEXT,
    `avg_rating` DECIMAL(10, 2) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created_by` INT NOT NULL
);

CREATE TABLE `registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `event_id` INT NOT NULL,
    `amount` INT NOT NULL,
    `value` DECIMAL(10, 2) NOT NULL,
    `payment_status` BOOLEAN NOT NULL DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `event_id` INT NOT NULL,
    `rating` INT NOT NULL,
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE `users`
    ADD FOREIGN KEY (`role`) REFERENCES `roles`(`role_name`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `events`
    ADD FOREIGN KEY (`category`) REFERENCES `categories`(`category_name`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `events`
    ADD FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `registrations`
    ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `registrations`
    ADD FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `reviews`
    ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `reviews`
    ADD FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- FUNCTIONS & TRIGGERS --

-- Procedure para calcular a média das avaliações de um evento --

DROP PROCEDURE IF EXISTS `calculate_avg_rating`;
DELIMITER $$
CREATE PROCEDURE `calculate_avg_rating`(IN `event_id` INT)
BEGIN
    DECLARE `avg_rating` DECIMAL(10, 2);
    SELECT AVG(`rating`) INTO `avg_rating` FROM `reviews` WHERE `event_id` = `event_id`;
    UPDATE `events` SET `avg_rating` = `avg_rating` WHERE `id` = `event_id`;
END$$
DELIMITER ;

-- Trigger para calcular a média das avaliações de um evento após insert --

DROP TRIGGER IF EXISTS `update_avg_rating_on_insert`;
DELIMITER $$
CREATE TRIGGER `update_avg_rating_on_insert` AFTER INSERT ON `reviews`
FOR EACH ROW
BEGIN
    CALL `calculate_avg_rating`(`NEW`.`event_id`);
END$$
DELIMITER ;

-- Trigger para calcular a média das avaliações de um evento após update --

DROP TRIGGER IF EXISTS `update_avg_rating_on_update`;
DELIMITER $$
CREATE TRIGGER `update_avg_rating_on_update` AFTER UPDATE ON `reviews`
FOR EACH ROW
BEGIN
    CALL `calculate_avg_rating`(`NEW`.`event_id`);
END$$
DELIMITER ;

-- Trigger para calcular a média das avaliações de um evento após delete --

DROP TRIGGER IF EXISTS `update_avg_rating_on_delete`;
DELIMITER $$
CREATE TRIGGER `update_avg_rating_on_delete` AFTER DELETE ON `reviews`
FOR EACH ROW
BEGIN
    CALL `calculate_avg_rating`(`OLD`.`event_id`);
END$$
DELIMITER ;

-- INSERÇÕES --

INSERT INTO `roles` (`role_name`) VALUES ('user');

INSERT INTO `roles` (`role_name`) VALUES ('promoter');

INSERT INTO `roles` (`role_name`) VALUES ('admin');

INSERT INTO `categories` (`category_name`) VALUES ('festas');

INSERT INTO `categories` (`category_name`) VALUES ('bares');

INSERT INTO `categories` (`category_name`) VALUES ('shows');

INSERT INTO `categories` (`category_name`) VALUES ('festivais');

INSERT INTO `categories` (`category_name`) VALUES ('teatros');

INSERT INTO `categories` (`category_name`) VALUES ('cursos');

INSERT INTO `categories` (`category_name`) VALUES ('feiras');

INSERT INTO `users` (`name`, `cpf`, `phone`, `address`, `avatar`, `email`, `password`, `role`) VALUES ('Administrador', '11111111111', '11987654321', 'Rua Exemplo, 01, Bairo Exemplo, Rio Paranaíba - MG', '../assets/default-avatar.png', 'admin@email.com', '$2y$10$U3vDQKKEdC2BFUXzX4K6iupFqNDAoNpW/QwI/y5QhmFUuWk3xLc.W', 'admin');

INSERT INTO `users` (`name`, `cpf`, `phone`, `address`, `avatar`, `email`, `password`, `role`) VALUES ('Promotor', '22222222222', '11987654321', 'Rua Exemplo, 01, Bairo Exemplo, Rio Paranaíba - MG', '../assets/default-avatar.png', 'promoter@email.com', '$2y$10$U3vDQKKEdC2BFUXzX4K6iupFqNDAoNpW/QwI/y5QhmFUuWk3xLc.W', 'promoter');

INSERT INTO `users` (`name`, `cpf`, `phone`, `address`, `avatar`, `email`, `password`, `role`) VALUES ('Participante', '33333333333', '11987654321', 'Rua Exemplo, 01, Bairo Exemplo, Rio Paranaíba - MG', '../assets/default-avatar.png', 'user@email.com', '$2y$10$U3vDQKKEdC2BFUXzX4K6iupFqNDAoNpW/QwI/y5QhmFUuWk3xLc.W', 'user');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`, `created_by`) VALUES ('Djavan Turnê D 2023', 'Descrição exemplo.', '2023-06-20', '19:00', 'Rio Paranaíba, MG', 'shows', 90.00, '../assets/example_1.png', 2);

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`, `created_by`) VALUES ('Roupa Nova 40 Anos', 'Descrição exemplo.', '2024-01-01', '18:30', 'Sete Lagoas, MG', 'shows', 100.00, '../assets/example_2.jpg', 2);

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`, `created_by`) VALUES ('Titãs Encontro Todos ao Mesmo Tempo Agora', 'Descrição exemplo.', '2023-12-23', '20:45', 'Belo Horizonte, MG', 'shows', 80.00, '../assets/example_3.jpg', 2);

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`, `created_by`) VALUES ('Roberto Carlos em São Paulo', 'Descrição exemplo.', '2023-06-20', '19:00', 'São Paulo, SP', 'shows', 200.00, '../assets/example_4.png', 2);

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`, `created_by`) VALUES ('Soy Rebelde Tour', 'Descrição exemplo.', '2024-01-01', '18:30', 'Florianópolis, SC', 'shows', 250.00, '../assets/example_5.jpg', 2);

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`, `created_by`) VALUES ('Red Hot Chili Peppers', 'Descrição exemplo.', '2023-12-23', '20:45', 'Brasília, DF', 'shows', 500.00, '../assets/example_6.png', 2);

INSERT INTO `registrations` (`user_id`, `event_id`, `amount`, `value`, `payment_status`) VALUES (3, 1, 2, 180.00, TRUE);

INSERT INTO `registrations` (`user_id`, `event_id`, `amount`, `value`, `payment_status`) VALUES (3, 2, 1, 100.00, TRUE);

INSERT INTO `registrations` (`user_id`, `event_id`, `amount`, `value`, `payment_status`) VALUES (3, 3, 3, 240.00, TRUE);

INSERT INTO `reviews` (`user_id`, `event_id`, `rating`, `comment`) VALUES (3, 1, 10, 'Comentário exemplo.');	

INSERT INTO `reviews` (`user_id`, `event_id`, `rating`, `comment`) VALUES (3, 2, 8, 'Comentário exemplo.');

INSERT INTO `reviews` (`user_id`, `event_id`, `rating`, `comment`) VALUES (3, 3, 6, 'Comentário exemplo.');

-- 