USE yeticave;

INSERT INTO `categories` (`name`, `code`) VALUES
    ('Доски и лыжи','boards'),
    ('Крепления','attachment'),
    ('Ботинки','boots'),
    ('Одежда','clothing'),
    ('Инструменты','tools'),
    ('Разное','other');

INSERT INTO `users` (`date_add`, `email`, `name`, `password`) VALUES
    ('2019-03-01 22:00:00','user1@dom.com', 'Сергей', 'password1'),
    ('2019-03-01 22:00:00','user2@dom.com', 'Вадим', 'password');

INSERT INTO `lots` ( `title`, `date_add`, `img_url`, `start_cost`, `author_user_id`, `category_id`, `winner_user_id`)
    VALUES
        ('2014 Rossignol District Snowboard', '2019-03-01 22:00:00', 'img/lot-1.jpg', 10999, 1, 1, 1),
        ('DC Ply Mens 2016/2017 Snowboard', '2019-04-01 22:00:00', 'img/lot-2.jpg', 159999, 2, 1, 1),
        ('Крепления Union Contact Pro 2015 года размер L/XL', '2019-04-01 22:00:00', 'img/lot-3.jpg', 8000, 1, 2),
        ('Ботинки для сноуборда DC Mutiny Charocal', '2019-03-01 22:00:00', 'img/lot-4.jpg', 10999, 2, 3),
        ('Куртка для сноуборда DC Mutiny Charocal', '2019-04-01 22:00:00', 'img/lot-5.jpg', 7500, 1, 4),
        ('Маска Oakley Canopy', '2019-03-01 22:00:00', 'img/lot-6.jpg', 10999, 2, 6);

INSERT INTO `bets` (`date_add`, `cost`, `user_id`, `lot_id`) VALUES
    ('2019-04-01 22:00:00', 1000, 1, 2),
    ('2019-04-01 22:00:00', 2000, 1, 2);

/* получить все категории */
SELECT * FROM `categories`;

/* получить самые новые, открытые лоты */
SELECT `title`, `img_url`, `start_cost`, `name` FROM `lots`
    RIGHT JOIN `categories` ON `lots`.`category_id` = `categories`.`id`
    WHERE `winner_user_id` IS NULL;

/* показать лот по его id */
SELECT `lots`.`id`, `title`, `name` FROM `lots`
    RIGHT JOIN `categories` ON `lots`.`category_id` = `categories`.`id`
    WHERE `lots`.`id` = 1;

/* обновить название лота по его идентификатору */
UPDATE `lots` SET  `title` = 'Новое название лота'
    WHERE `id` = 1;

/* получить список самых свежих ставок для лота по его идентификатору */
SELECT `id`, `cost` FROM `bets`
    WHERE `lot_id`= 1;

    
