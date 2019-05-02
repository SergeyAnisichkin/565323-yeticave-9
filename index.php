<?php
require_once "helpers.php";

$is_auth = rand(0, 1);
$user_name = 'Сергей';

$link = mysqli_connect("localhost", "root", "", "yeticave");
if (!$link) {
    $layout_content = "Ошибка подключения. " . mysqli_connect_error();
}
else {
    $sql = "SELECT c.name, c.code FROM categories AS c";
    $result = mysqli_query($link, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $sql = "SELECT 
                l.id,
                l.title, 
                l.img_url, 
                l.start_cost, 
                l.date_end, 
                c.name AS category 
                FROM lots AS l 
                LEFT JOIN categories AS c ON l.category_id = c.id 
                WHERE l.winner_user_id IS NULL";
    $result = mysqli_query($link, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $page_content = include_template('index.php', [
        'lots' => $lots,
        'categories' => $categories,
    ]);
    
    $layout_content = include_template('layout.php', [
        'content' => $page_content,
        'title' => "YetiCave - Главная",
        'categories' => $categories,
        'is_auth' => $is_auth,
        'user_name' => $user_name,
    ]);
}

print($layout_content);
