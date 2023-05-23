-- SCRIPT CRIAÇÃO DO BANCO DE DADOS --

DROP DATABASE IF EXISTS `projeto_final_sin143`;
CREATE DATABASE `projeto_final_sin143`;
USE `projeto_final_sin143`;

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
    `image` VARCHAR(255),
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

-- INSERÇÕES --

INSERT INTO `roles` (`role_name`) VALUES ('organizador');

INSERT INTO `roles` (`role_name`) VALUES ('participante');

INSERT INTO `roles` (`role_name`) VALUES ('administrador');

INSERT INTO `categories` (`category_name`) VALUES ('festas');

INSERT INTO `categories` (`category_name`) VALUES ('bares');

INSERT INTO `categories` (`category_name`) VALUES ('shows');

INSERT INTO `categories` (`category_name`) VALUES ('música ao vivo');

INSERT INTO `categories` (`category_name`) VALUES ('teatros');

INSERT INTO `categories` (`category_name`) VALUES ('cursos');

INSERT INTO `categories` (`category_name`) VALUES ('feiras');

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES ('Administrador', 'admin@ufv.br', '$2y$10$U3vDQKKEdC2BFUXzX4K6iupFqNDAoNpW/QwI/y5QhmFUuWk3xLc.W', 'administrador');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`) VALUES ('Título Exemplo #1', 'Descrição exemplo.', '2023-06-20', '19:00:00', 'Rio Paranaíba, MG', 'festas', 200.00, 'img_path/img_01.png');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`) VALUES ('Título Exemplo #2', 'Descrição exemplo.', '2024-01-01', '18:30:00', 'Sete Lagoas, MG', 'bares', 300.00, 'img_path/img_02.png');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`) VALUES ('Título Exemplo #3', 'Descrição exemplo.', '2023-12-23', '20:45:00', 'Belo Horizonte, MG', 'shows', 80.00, 'img_path/img_03.png');

INSERT INTO `registrations` (`user_id`, `event_id`, `payment_status`) VALUES (1, 1, TRUE);

INSERT INTO `registrations` (`user_id`, `event_id`, `payment_status`) VALUES (1, 2, TRUE);

INSERT INTO `registrations` (`user_id`, `event_id`, `payment_status`) VALUES (1, 3, TRUE);
--