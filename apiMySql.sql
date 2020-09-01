CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `id_groups` int UNIQUE,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255),
  `birthday` timestamp,
  `password` varchar(255) NOT NULL,
  `token` varchar(255),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `contacts` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `images` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `slug` varchar(255),
  `title` varchar(255) NOT NULL,
  `url_image` varchar(255) NOT NULL,
  `id_user` int NOT NULL,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `permissions` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `description` text,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `groups` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `permissions_id` varchar(255),
  `id_user` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `messages` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `id_contact` int NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `history` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `description` text,
  `created_at` timestamp DEFAULT (now())
);

ALTER TABLE `messages` ADD FOREIGN KEY (`id_contact`) REFERENCES `contacts` (`id`);

ALTER TABLE `history` ADD FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

ALTER TABLE `images` ADD FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);
