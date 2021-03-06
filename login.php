<?php
require_once "helpers.php";

$page_content = '';
$page_title = "Вход на сайт";
$login = [];
$errors = [];
$categories = getCategories($link);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login['email'] = strip_tags($_POST['email']);
    $login['password'] = strip_tags($_POST['password']);
    foreach ($login as $field => $value) {
        if (empty($login[$field])) {
            $errors[$field] = 'Заполните это поле';
        }
    }
    $user =  getUserByEmail($link, $login['email']);
    if ($user) {
        if (!password_verify($login['password'], $user['password'])) {
            $errors['password'] = 'Неверный пароль';
        }
    } else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (!empty($errors)) {
        $page_content = include_template('login.php', [
            'categories' => $categories, 
            'errors' => $errors, 
            'login' => $login,
        ]);
    } else {
        $_SESSION['user'] = $user;
        header("Location: index.php");
    }
} else {
    $page_content = include_template('login.php', [
        'categories' => $categories,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $page_title,
    'categories' => $categories,
]);


print($layout_content);



