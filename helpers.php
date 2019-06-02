<?php
session_start();
$HOST = "localhost";
$USER = "root";
$PWD = "";
$DB = "yeticave";
date_default_timezone_set("Europe/Moscow");
$link = mysqli_connect($HOST, $USER, $PWD, $DB);
mysqli_query($link,'SET CHARACTER SET utf8');
if (!$link) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Округляет до целого и форматирует стоимость, выделяя разряды 1000
 *
 * Примеры использования:
 * format_cost(1234,5); // "1 234"
 * format_cost(1234567); // "1 234 567"
 *
 * @param string $cost Стоимость в виде строки
 *
 * @return string Отформатированная стоимость
 */
function format_cost($cost) {
    $cost = ceil($cost);
    if ($cost >= 1000) {
        $cost = number_format($cost, 0, ',', ' ');
    }
    return $cost;
}

/**
 * Вычисляет интервал между текущей датой и датой завершения
 *
 * Примеры использования:
 * calc_timer('2019-01-01'); // DateInterval
 *
 * @param string $date_end Дата завершения в виде строки
 *
 * @return DateInterval Объект интервал между датами
 */
function calc_timer($date_end) {
    $cur_date = date_create("now");
    $date_end = date_create($date_end);
    return date_diff($cur_date, $date_end);
}

/**
 * Вычисляет количество часов для интервала между текущей датой и датой завершения
 *
 * Примеры использования:
 * calc_hour('2019-01-01'); // 42
 *
 * @param string $date_end Дата завершения в виде строки
 *
 * @return int Количество часов
 */
function calc_hour($date_end) {
    $diff = calc_timer($date_end);
    $d = $diff->d;
    $h = $diff->h;
    return $d * 24 + $h;
}

/**
 * Вычисляет и форматирует таймер времени до даты завершения
 * Выводит в формате "ЧЧЧ:ММ"
 * Примеры использования:
 * format_timer('2019-01-01') ; // "442:59"
 *
 * @param string $date_end Дата завершения в виде строки
 *
 * @return string Отформатированный таймер вида "ЧЧЧ:ММ"
 */
function format_timer($date_end) {
    $diff = calc_timer($date_end);
    $h_sum = calc_hour($date_end);
    $h_sum < 10 ? $h_sum = "0" . $h_sum : $h_sum;
    return $h_sum . date_interval_format($diff, ":%I");
}

/**
 * Проверяет значение стоимости, чтобы было целым и больше 0.
 *
 * Примеры использования:
 * isCostInvalid('2000'); // true
 * isCostInvalid('2019-01-01'); // false
 * isCostInvalid('0'); // false
 *
 * @param string $cost Стоимость в виде строки
 *
 * @return bool true если целое и больше 0, иначе false
 */
function isCostInvalid($cost) {
    return !filter_var($cost, FILTER_VALIDATE_INT, 
        array("options" => array("min_range" => 1)));
}

/**
 * Проверяет, чтобы дата завершения была больше текущей даты.
 * 
 * Примеры использования:
 * isDateEndIncorrect('2019-12-01') ; // true
 * isDateEndIncorrect('2019-01-01') ; // false
 *
 * @param string $date_end Дата завершения в виде строки
 *
 * @return bool true если дата больше текущей, иначе false
 */
function isDateEndIncorrect($date_end) {
    $cur_date = date_create("today");
    $date_end = date_create($date_end);
    return $date_end <= $cur_date;
}

/**
 * Проверяет корректность заполнения полей формы добавления лота
 * Формирует массив сообщений об ошибках заполнения
 * 
 * Примеры использования:
 * checkLotField(array $lot) ; // array $errors
 *
 * @param array $lot Массив значений полей формы добавления лота
 *
 * @return array $errors Массив сообщений об ошибках заполнения
 */
function checkLotField($lot) {
    $errors = [];
    if ($lot['category'] == 'Выберите категорию') {
        $errors['category'] = 'Выберите категорию';
    }
    if (isCostInvalid($lot['lot-rate'])) {
        $errors['lot-rate'] = 'Введите начальную цену';
    }
    if (isCostInvalid($lot['lot-step'])) {
        $errors['lot-step'] = 'Введите шаг ставки';
    }
    if (!is_date_valid($lot['lot-date'])) {
        $errors['lot-date'] = 'Введите дату завершения торгов ГГГГ-ММ-ДД';
    }
    if (isDateEndIncorrect($lot['lot-date'])) {
        $errors['lot-date'] = 'Введите дату завершения больше текущей даты';
    }
    foreach ($lot as $field => $value) {
        if (empty($lot[$field])) {
            $errors[$field] = 'Заполните это поле';
        }
    }
    return $errors;
}

/**
 * Проверяет корректность заполнения полей формы регистрации пользователя
 * Формирует массив сообщений об ошибках заполнения
 * 
 * Примеры использования:
 * checkSignField($link, $sign_up) ; // array $errors
 *
 * @param $link mysqli Ресурс соединения
 * @param array $sign_up Массив значений полей формы регистрации пользователя
 *
 * @return array $errors Массив сообщений об ошибках заполнения
 */
function checkSignField($link, $sign_up) {
    $errors = [];
    if (!filter_var($sign_up['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный Email';
    }      
    if (isEmailExist($link, $sign_up['email'])) {
        $errors['email'] = 'Email существует, укажите другой';
    }
    foreach ($sign_up as $field => $value) {
        if (empty($sign_up[$field])) {
            $errors[$field] = 'Заполните это поле';
        }
    }
    return $errors;
}

/**
 * Форматирование даты и времени добавления ставки
 * 
 * Примеры использования:
 * formatDateBet('2019-06-02 01:23:45') ; // "5 часов назад"
 * formatDateBet('2019-06-01 01:23:45') ; // "Вчера, в 01:23"
 * formatDateBet('2019-05-31 01:23:45') ; // "31.05.19 в 01:23"
 *
 * @param string $date Дата добавления в виде строки
 *
 * @return string $errors Отформатированная дата и время
 */
function formatDateBet($date) {
    $date = date_create($date);
    $date_today = date_create('today');
    $date_yesterday = date_create('yesterday');
    if ($date > $date_today) {
        $date_now = date_create('now');
        $diff = date_diff($date, $date_now);
        if ($diff->h < 1) {
            return $diff->i . get_noun_plural_form($diff->i, ' минута', ' минуты', ' минут') . ' назад';
        } else {
            return $diff->h . get_noun_plural_form($diff->h, ' час', ' часа', ' часов') . ' назад';
        }
    } elseif ($date > $date_yesterday && $date < $date_today) {
        return 'Вчера, в ' . date_format($date, "G:i");
    } else {
        return date_format($date, "d.m.y в G:i");
    }
}

/**
 * Возвращает массив всех категорий с именем, кодом и айди категорий
 * 
 * Примеры использования:
 * getCategories($link) ; // array $сategories
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array $сategories Массив категорий
 */
function getCategories($link) {
    $sql = "SELECT c.name, c.code, c.id FROM categories AS c";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Возвращает массив активных лотов
 * Массив отсортирован по дате добавления, от новых к старым
 * 
 * Примеры использования:
 * getOpenLots($link) ; // array $lots
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array $lots Массив активных лотов
 */
function getOpenLots($link) {
    $sql = "SELECT 
                l.id,
                l.date_add,
                l.title, 
                l.img_url, 
                l.start_cost, 
                l.date_end, 
                c.name AS category
                FROM lots AS l 
                LEFT JOIN categories AS c ON l.category_id = c.id 
                WHERE l.date_end > NOW()
                ORDER BY l.date_add DESC";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Проверяет существование в БД лота по id
 * 
 * Примеры использования:
 * isIdExist($link, 15) ; // true
 * isIdExist($link, -15) ; // false
 *
 * @param $link mysqli Ресурс соединения
 * @param string $id id лота в виде строки
 * 
 * @return bool true если id лота существует в БД, иначе false
 */
function isIdExist($link, $id) {
    $id = intval($id);
    $sql = "SELECT id FROM lots WHERE id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result->num_rows > 0 ?? false;
}

/**
 * Возвращает ассоциативный массив параметров лота по id лота
 * 
 * Примеры использования:
 * getLotById($link, 5) ; // array $lot
 *
 * @param $link mysqli Ресурс соединения
 * @param int $id ID лота в виде целого числа
 *
 * @return array $lot Массив параметров лота
 */
function getLotById($link, $id) {
    $sql = "SELECT 
            l.id,
            l.title, 
            l.img_url, 
            l.start_cost, 
            l.step_bet, 
            l.date_end, 
            l.description,
            l.author_user_id,
            c.name AS category 
            FROM lots AS l 
            LEFT JOIN categories AS c ON l.category_id = c.id 
            WHERE l.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/**
 * Возвращает id категорий по названию категории
 * 
 * Примеры использования:
 * getCategoryIdByName($link, "Разное") ; // 6
 *
 * @param $link mysqli Ресурс соединения
 * @param string $category название категории в виде строки
 *
 * @return int $id ID категории в виде целого числа
 */
function getCategoryIdByName($link, $category) {
    $sql = "SELECT c.id, c.name FROM categories AS c 
                WHERE c.name = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $category, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/**
 * Добавляет в БД новый лот, возвращает ID добавленного лота
 * 
 * Примеры использования:
 * insertLotDB($link, $lot) ; // 6
 *
 * @param $link mysqli Ресурс соединения
 * @param array $lot Массив параметров добавляемого лота
 *
 * @return int $id ID добавленного лота
 */
function insertLotDB($link, $lot) {
    $category = getCategoryIdByName($link, $lot['category']);
    $user_id = $_SESSION['user']['id'];
    $sql = "INSERT INTO lots (
                date_add, 
                author_user_id, 
                category_id, 
                date_end, 
                title, 
                lots.description, 
                img_url,
                start_cost,
                step_bet
            ) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $user_id, 
        $category['id'], 
        $lot['lot-date'], 
        $lot['lot-name'], 
        $lot['message'], 
        $lot['path'], 
        $lot['lot-rate'], 
        $lot['lot-step']
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

/**
 * Проверяет существование email в БД, возвращает массив с данными пользователя
 * 
 * Примеры использования:
 * isEmailExist($link, "email@dot.com") ; // array $user
 *
 * @param $link mysqli Ресурс соединения
 * @param string $email email в виде строки
 *
 * @return array $user Массив id, email пользователя
 */
function isEmailExist($link, $email) {
    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $email, 
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_fetch($stmt);
}

/**
 * Добавляет в БД нового пользователя, возвращает ID пользователя
 * 
 * Примеры использования:
 * insertUserDB($link, $user) ; // 6
 *
 * @param $link mysqli Ресурс соединения
 * @param array $sign_up Массив с данными нового пользователя
 *
 * @return int $id ID нового пользователя
 */
function insertUserDB($link, $sign_up) {
    $sql = "INSERT INTO users (
                date_add, 
                email, 
                users.name, 
                users.password, 
                contacts
            ) VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $sign_up['email'], 
        $sign_up['name'], 
        password_hash($sign_up['password'], PASSWORD_DEFAULT), 
        $sign_up['message']
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

/**
 * Возвращает массив данных пользователя по email
 * 
 * Примеры использования:
 * getUserByEmail($link, "email@dot.com") ; // array $user
 *
 * @param $link mysqli Ресурс соединения
 * @param string $email email в виде строки
 *
 * @return array $user Массив данных пользователя
 */
function getUserByEmail($link, $email) {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $email, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_assoc($result) : null;
}

/**
 * Вычисляет максимальную ставку по лоту
 * Если ставок по лоту нет, возвращает null
 * 
 * Примеры использования:
 * getMaxCostBets($link, 5) ; // "1250"
 *
 * @param $link mysqli Ресурс соединения
 * @param int $lot_id ID лота
 *
 * @return string $cost Максимальная стоимость в виде строки
 */
function getMaxCostBets($link, $lot_id) {
    $sql = "SELECT MAX(cost) AS cost FROM bets 
                WHERE lot_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $lot_id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $array = $result ? mysqli_fetch_assoc($result) : null;
    return $array['cost'] ?? null;
}

/**
 * Возвращает массив ставок по ID лота
 * Если ставок по лоту нет, возвращает null
 * 
 * Примеры использования:
 * getBets($link, 3) ; // array $bets
 *
 * @param $link mysqli Ресурс соединения
 * @param int $lot_id ID лота
 *
 * @return array $bets Массив ставок с параметрами
 */
function getBets($link, $lot_id) {
    $sql = "SELECT 
            b.date_add,
            b.cost,
            b.user_id, 
            u.name 
            FROM bets AS b 
            LEFT JOIN users AS u ON b.user_id = u.id 
            WHERE b.lot_id = ?
            ORDER BY b.date_add DESC";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $lot_id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
}

/**
 * Добавляет ставку в БД и возвращает ID ставки
 * 
 * Примеры использования:
 * insertBet($link, 3, 2, 333) ; // 15
 *
 * @param $link mysqli Ресурс соединения
 * @param int $user_id ID пользователя
 * @param int $lot_id ID лота
 * @param int $bet_cost Стоимость ставки
 *
 * @return int $bet_id ID добавленной ставки
 */
function insertBet($link, $user_id, $lot_id, $bet_cost) {
    $sql = "INSERT INTO bets (
                date_add, 
                cost, 
                user_id, 
                lot_id
            ) VALUES (NOW(), ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $bet_cost, 
        $user_id, 
        $lot_id
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

/**
 * Возвращает массив ставок пользователя
 * отсортированных по дате добавления ставки, от новых к старым
 * 
 * Примеры использования:
 * getUserBets($link, 3) ; // array $bets
 *
 * @param $link mysqli Ресурс соединения
 * @param int $user_id ID пользователя
 *
 * @return array $bets Массив ставок пользователя
 */
function getUserBets($link, $user_id) {
    $sql = "SELECT 
            b.date_add,
            b.cost, 
            l.title,
            l.id,
            l.img_url,
            l.date_end,
            l.winner_user_id,
            u.contacts,
            c.name
            FROM bets AS b 
            LEFT JOIN lots AS l ON b.lot_id = l.id
            LEFT JOIN categories AS c ON l.category_id = c.id
            LEFT JOIN users AS u ON u.id = b.user_id 
            WHERE b.user_id = ? 
            ORDER BY b.date_add DESC";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $user_id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
}

/**
 * Возвращает массив лотов результатов поиска,
 * отсортированных по дате добавления лота, от новых к старым,
 * с учетом лимита пагинации и номера страницы
 * 
 * Примеры использования:
 * getLotBySearch($link, "куртка", 1, 9) ; // array $lots
 *
 * @param $link mysqli Ресурс соединения
 * @param string $find Искомая фраза в виде строки
 * @param int $page_number Номер страницы пагинатора
 * @param int $page_limit Лимит лотов на странице
 *
 * @return array $lots Массив лотов на страницу
 */
function getLotBySearch($link, $find, $page_number, $page_limit) {
    $page_offset = ($page_number - 1) * $page_limit;
    $sql = "SELECT 
            l.title,
            l.id,
            l.img_url,
            l.date_end,
            l.start_cost,
            c.name AS category
            FROM lots AS l 
            LEFT JOIN categories AS c ON l.category_id = c.id 
            WHERE MATCH(l.title, l.description) AGAINST( ? ) 
            AND l.date_end > NOW()
            ORDER BY l.date_add DESC
            LIMIT {$page_limit} OFFSET {$page_offset} ";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $find, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
}

/**
 * Отправляет письмо пользователю
 * 
 * Примеры использования:
 * sendMailWin("email@dot.com", "текст письма") ; // 
 *
 * @param string $email email для отправки письма
 * @param string $body Текст письма
 *
 * @return
 */
function sendMailWin($email, $body) {
    $transport = new Swift_SmtpTransport('phpdemo.ru', 45);
    $transport->setUsername('keks@phpdemo.ru');
    $transport->setPassword('htmlacademy');
  
    $message = new Swift_Message("Ваша ставка победила");
    $message->setTo($email);
    $message->setBody($body, 'text/html');
    $message->setFrom('keks@phpdemo.ru', 'phpdemo');
  
    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}

/**
 * Определяет победителей по завершенным лотам, 
 * возвращает массив с данными победивших пользователей
 * 
 * Примеры использования:
 * getWinners($link) ; // array $winners
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array $winners Массив пользователей-победителей по лотам
 */
function getWinners($link) {
    $winners = [];
    $lots_for_win = getLotsForWinner($link) ?: [];
    if (count($lots_for_win)) {
        foreach ($lots_for_win as $lot) {
            $sql = "SELECT 
                        b.id,
                        b.lot_id,
                        b.date_add,
                        b.user_id,
                        u.name,
                        u.email, 
                        l.title
                    FROM bets AS b
                    JOIN (SELECT lot_id,        
                                MAX(date_add) AS max_date
                                FROM bets
                                WHERE lot_id = {$lot['id']}) AS t
                    ON b.lot_id = t.lot_id AND b.date_add = t.max_date
                    LEFT JOIN users AS u ON b.user_id = u.id
                    LEFT JOIN lots AS l ON b.lot_id = l.id ";
            $result = mysqli_query($link, $sql);
            $err = mysqli_error($link);
            $winner = mysqli_fetch_assoc($result);
            if ($winner) {
                array_push($winners, $winner);  
            }
        }
    }
    
    return $winners;
}

/**
 * Возвращает массив лотов, по которым нет победителя
 * и дата завершения меньше текущей
 * 
 * Примеры использования:
 * getLotsForWinner($link) ; // array $lots
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array $lots Массив лотов
 */
function getLotsForWinner($link) {
    $sql = "SELECT id FROM lots WHERE winner_user_id IS NULL AND NOW() > date_end";
    $result = mysqli_query($link, $sql);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
}

/**
 * Записывает в БД победителя по лоту,
 * при успехе возвращает id лота, иначе null
 * 
 * Примеры использования:
 * setWinnerDB($link, 5, 2) ; // 5
 * setWinnerDB($link, 6, 3) ; // null
 *
 * @param $link mysqli Ресурс соединения
 * @param string $lot_id ID лота
 * @param string $user_id ID пользовтеля
 * 
 * @return int $id ID лота
 */
function setWinnerDB($link, $lot_id, $user_id) {
    $sql = "UPDATE lots SET
            winner_user_id = {$user_id}
            WHERE id = {$lot_id} ";
    $result = mysqli_query($link, $sql);
    return $result ? $lot_id : null;
}

/**
 * Возвращает массив лотов по категории,
 * отсортированных по дате добавления лота, от новых к старым,
 * с учетом лимита пагинации и номера страницы
 * 
 * Примеры использования:
 * getLotsByCategory($link, 5, 1, 9) ; // array $lots
 * getLotsByCategory($link, 55, 1, 9) ; // null
 *
 * @param $link mysqli Ресурс соединения
 * @param int $category_id ID категории
 * @param int $page_number Номер страницы паганатора
 * @param int $page_limit Лимит лотов на странице
 * 
 * @return array $lots Массив лотов
 */
function getLotsByCategory($link, $category_id, $page_number, $page_limit) {
    $page_offset = ($page_number - 1) * $page_limit;
    $sql = "SELECT 
            l.title,
            l.id,
            l.img_url,
            l.date_end,
            l.start_cost,
            c.name AS category
            FROM lots AS l 
            LEFT JOIN categories AS c ON l.category_id = c.id 
            WHERE l.category_id = ? AND l.date_end > NOW()
            ORDER BY l.date_add DESC
            LIMIT {$page_limit}
            OFFSET {$page_offset}";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $category_id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
}