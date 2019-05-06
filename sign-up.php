<?php
require_once "helpers.php";
require_once "model.php";
$page_title = "Регистрация";

if ($link) {
    $categories = getCategories($link);
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $sign_up = $_POST;
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
        if (!empty($errors)) {
            $page_content = include_template('sign-up.php', [
                'categories' => $categories, 
                'errors' => $errors, 
                'sign_up' => $sign_up,
            ]);
        } else {
            insertUserDB($link, $sign_up);
            header("Location: pages\login.html");
        }
    } else {
        $page_content = include_template('sign-up.php', [
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



