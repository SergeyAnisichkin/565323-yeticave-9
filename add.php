<?php
require_once "helpers.php";
require_once "model.php";
if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    echo 'Доступ заблокирован, необходимо зарегистрироваться!';
    exit();
}
$page_title = "Добавление лота";
$categories = getCategories($link);
$lot = [];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $field => $value) {
        $lot[$field] = strip_tags($_POST[$field]);
    }
    $errors = checkLotField($lot);
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

print($layout_content);



