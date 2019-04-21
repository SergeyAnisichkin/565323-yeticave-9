CREATE DATABASE yeticave character set UTF8;
USE yeticave;

CREATE TABLE `categories` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` CHAR(32) NOT NULL UNIQUE,
    `code` CHAR(32) NOT NULL UNIQUE
) ENGINE=InnoDB CHARACTER SET utf8;

CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date_add` DATETIME NOT NULL,
    `email` CHAR(128) NOT NULL UNIQUE,
    `name` CHAR(128) NOT NULL,
    `password` CHAR(64) NOT NULL,
    `avatar_url` CHAR(255),
    `contacts` CHAR(255)
) ENGINE=InnoDB CHARACTER SET utf8;

CREATE TABLE `lots` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` CHAR(128) NOT NULL,
    `date_add` DATETIME NOT NULL,
    `date_completion` DATETIME,
    `description` CHAR(255),
    `img_url` CHAR(128) NOT NULL,
    `start_cost` INT NOT NULL,
    `step_bet` INT,
    `author_user_id` INT NOT NULL,
    `winner_user_id` INT,
    `category_id` INT NOT NULL,
    KEY `author_user_id` (`author_user_id`),
    KEY `winner_user_id` (`winner_user_id`),
    KEY `category_id` (`category_id`),
    CONSTRAINT `lots_author_fk_users` FOREIGN KEY (`author_user_id`)
        REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lots_winner_fk_users` FOREIGN KEY (`winner_user_id`)
        REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `lots_fk_categories` FOREIGN KEY (`category_id`) 
        REFERENCES `categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8;

CREATE TABLE `bets` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date_add` DATETIME NOT NULL,
    `cost` INT NOT NULL,
    `user_id` INT NOT NULL,
    `lot_id` INT NOT NULL,
    KEY `user_id` (`user_id`),
    KEY `lot_id` (`lot_id`),
    CONSTRAINT `bets_fk_users` FOREIGN KEY (`user_id`)
        REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `bets_fk_lots` FOREIGN KEY (`lot_id`)
        REFERENCES `lots`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8;

CREATE UNIQUE INDEX `user_email` ON `users`(`email`);
CREATE INDEX `user_contacts` ON `users`(`contacts`);
CREATE INDEX `lot_description` ON `lots`(`description`);