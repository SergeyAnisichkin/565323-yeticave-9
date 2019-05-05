<?php
require_once "helpers.php";
require_once "model.php";
$page_title = "Добавление лота";

if ($link) {
    $categories = getCategories($link);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST;
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
        if (!empty($_FILES['lot-img']['name'])) {
            $tmp_name = $_FILES['lot-img']['tmp_name'];
            $path = $_FILES['lot-img']['name'];
            $f_type = mime_content_type($tmp_name);
            if ($f_type !== "image/png" && $f_type !== "image/jpeg") {
                $errors['file'] = 'Загрузите изображение в формате PNG или JPG';
            } else {
                move_uploaded_file($tmp_name, 'uploads/' . $path);
                $lot['path'] = 'uploads/' . $path;
            }
        } else {
            $errors['file'] = 'Вы не загрузили файл изображения';
        }
        if (!empty($errors)) {
            $page_content = include_template('add.php', [
                'categories' => $categories, 
                'errors' => $errors, 
                'lot' => $lot,
            ]);
        } else {
            $lot_id = insertLotDB($link, $lot);
            header("Location: lot.php?id=" . $lot_id);
        }
    } else {
        $page_content = include_template('add.php', [
            'categories' => $categories,
        ]);
    }

    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'title' => $page_title,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
    ]);
}

print($layout_content);



