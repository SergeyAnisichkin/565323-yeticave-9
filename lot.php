<?php
require_once "helpers.php";
require_once "model.php";

$page_title = "Лот";

if ($link) {
    $categories = getCategories($link);
    if (isIdExist($link, $_GET['id'])) {
        $lot = getLotById($link, $_GET['id']);
        $page_content = include_template('lot.php', [
            'lot' => $lot,
            'categories' => $categories,
        ]);
    } else {
        $page_content = include_template('404.php', [
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

