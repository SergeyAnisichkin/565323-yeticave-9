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
    (NOW(),'user2@dom.com', 'Вадим', 'password');

INSERT INTO `lots` ( `title`, `date_add`, `img_url`, `start_cost`, `author_user_id`, `category_id`, `winner_user_id`)
    VALUES
        ('2014 Rossignol District Snowboard', NOW(), 'img/lot-1.jpg', 10999, 1, 1, 1),
        ('DC Ply Mens 2016/2017 Snowboard', '2019-04-01 22:00:00', 'img/lot-2.jpg', 159999, 2, 1, 1),
        ('Крепления Union Contact Pro 2015 года размер L/XL', '2019-04-01 22:00:00', 'img/lot-3.jpg', 8000, 1, 2, NULL),
        ('Ботинки для сноуборда DC Mutiny Charocal', '2019-03-01 22:00:00', 'img/lot-4.jpg', 10999, 2, 3, NULL),
        ('Куртка для сноуборда DC Mutiny Charocal', '2019-04-01 22:00:00', 'img/lot-5.jpg', 7500, 1, 4, NULL),
        ('Маска Oakley Canopy', '2019-03-01 22:00:00', 'img/lot-6.jpg', 10999, 2, 6, NULL);

INSERT INTO `bets` (`date_add`, `cost`, `user_id`, `lot_id`) VALUES
    ('2019-04-01 22:00:00', 13000, 1, 1),
    ('2019-04-01 22:00:00', 14000, 2, 1);

/* получить все категории */
SELECT * FROM categories;

/* получить самые новые, открытые лоты */
SELECT title, img_url, start_cost, `name` FROM lots AS l
    LEFT JOIN categories AS c ON l.category_id = c.id
    WHERE winner_user_id IS NULL;

/* показать лот по его id и название категории*/
SELECT l.id, title, `name` FROM lots AS l
    LEFT JOIN categories AS c ON l.category_id = c.id
    WHERE l.id = 1;

/* обновить название лота по его идентификатору */
UPDATE lots SET  title = 'Новое название лота'
    WHERE id = 6;

/* получить список самых свежих ставок для лота по его идентификатору */
SELECT id, cost FROM bets
    WHERE lot_id = 1;

    
