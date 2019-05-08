<?php
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

function format_cost($cost) {
    $cost = ceil($cost);
    if ($cost >= 1000) {
        $cost = number_format($cost, 0, ',', ' ');
    }
    return $cost . " ₽";
}
function calc_timer($date_end) {
    $cur_date = date_create("now");
    $date_end = date_create($date_end);
    return date_diff($cur_date, $date_end);
}
function calc_hour($date_end) {
    $diff = calc_timer($date_end);
    $d = $diff->d;
    $h = $diff->h;
    return $d * 24 + $h;
}
function format_timer($date_end) {
    $diff = calc_timer($date_end);
    $h_sum = calc_hour($date_end);
    $h_sum < 10 ? $h_sum = "0" . $h_sum : $h_sum;
    return $h_sum . date_interval_format($diff, ":%I:%S");
}
function isCostInvalid($cost) {
    return !filter_var($cost, FILTER_VALIDATE_INT, 
        array("options" => array("min_range" => 1)));
}
function isDateEndIncorrect($date_end) {
    $cur_date = date_create("today");
    $date_end = date_create($date_end);
    return $date_end <= $cur_date;
}
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