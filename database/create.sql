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
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(30) NOT NULL
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
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category_name` VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE `registrations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `event_id` INT NOT NULL,
    `payment_status` BOOLEAN NOT NULL DEFAULT FALSE
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
    ADD FOREIGN KEY (`role`) REFERENCES `roles`(`role_name`);

ALTER TABLE `events`
    ADD FOREIGN KEY (`category`) REFERENCES `categories`(`category_name`);

ALTER TABLE `registrations`
    ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);

ALTER TABLE `registrations`
    ADD FOREIGN KEY (`event_id`) REFERENCES `events`(`id`);

ALTER TABLE `reviews`
    ADD FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);

ALTER TABLE `reviews`
    ADD FOREIGN KEY (`event_id`) REFERENCES `events`(`id`);

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