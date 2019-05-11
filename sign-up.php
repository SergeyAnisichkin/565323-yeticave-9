<?php
require_once "helpers.php";
require_once "model.php";

$page_title = "Регистрация";
$categories = getCategories($link);
$sign_up = [];  
$errors = [];  
  
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $field => $value) {
        $sign_up[$field] = strip_tags($_POST[$field]);
    }
    $errors = checkSignField($link, $sign_up);
    if (!empty($errors)) {
        $page_content = include_template('sign-up.php', [
            'categories' => $categories, 
            'errors' => $errors, 
            'sign_up' => $sign_up,
        ]);
    } else {
        insertUserDB($link, $sign_up);
        header("Location: login.php");
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
]);


print($layout_content);



