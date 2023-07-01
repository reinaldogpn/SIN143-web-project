-- INSERÇÕES --

INSERT INTO `roles` (`role_name`) VALUES ('user');

INSERT INTO `roles` (`role_name`) VALUES ('admin');

INSERT INTO `categories` (`category_name`) VALUES ('festas');

INSERT INTO `categories` (`category_name`) VALUES ('bares');

INSERT INTO `categories` (`category_name`) VALUES ('shows');

INSERT INTO `categories` (`category_name`) VALUES ('música ao vivo');

INSERT INTO `categories` (`category_name`) VALUES ('teatros');

INSERT INTO `categories` (`category_name`) VALUES ('cursos');

INSERT INTO `categories` (`category_name`) VALUES ('feiras');

INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES ('Administrador', 'admin@email.com', '$2y$10$U3vDQKKEdC2BFUXzX4K6iupFqNDAoNpW/QwI/y5QhmFUuWk3xLc.W', 'admin');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`) VALUES ('Título Exemplo #1', 'Descrição exemplo.', '2023-06-20', '19:00:00', 'Rio Paranaíba, MG', 'festas', 200.00, 'path/examples/img1.png');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`) VALUES ('Título Exemplo #2', 'Descrição exemplo.', '2024-01-01', '18:30:00', 'Sete Lagoas, MG', 'bares', 300.00, 'path/examples/img2.jpg');

INSERT INTO `events` (`title`, `description`, `date`, `time`, `location`, `category`, `price`, `image`) VALUES ('Título Exemplo #3', 'Descrição exemplo.', '2023-12-23', '20:45:00', 'Belo Horizonte, MG', 'shows', 80.00, 'path/examples/img3.jpg');

INSERT INTO `registrations` (`user_id`, `event_id`, `payment_status`) VALUES (1, 1, TRUE);

INSERT INTO `registrations` (`user_id`, `event_id`, `payment_status`) VALUES (1, 2, TRUE);

INSERT INTO `registrations` (`user_id`, `event_id`, `payment_status`) VALUES (1, 3, TRUE);

INSERT INTO `reviews` (`user_id`, `event_id`, `rating`, `comment`) VALUES (1, 1, 10, 'Comentário exemplo.');	

INSERT INTO `reviews` (`user_id`, `event_id`, `rating`, `comment`) VALUES (1, 2, 8, 'Comentário exemplo.');

INSERT INTO `reviews` (`user_id`, `event_id`, `rating`, `comment`) VALUES (1, 3, 6, 'Comentário exemplo.');

-- 