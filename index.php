<?php
require_once "helpers.php";
require_once "model.php";

$page_title = "YetiCave";

if ($link) {
    $lots = getOpenLots($link);
    $page_content = include_template('index.php', [
        'lots' => $lots,
        'categories' => $categories,
    ]);
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'title' => $page_title,
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
    ]);
}

print($layout_content);
