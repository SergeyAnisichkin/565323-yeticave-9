<?php
require_once "helpers.php";

$page_title = "Лот";
$is_auth = rand(0, 1);
$user_name = 'Сергей';
$HOST = "localhost";
$USER = "root";
$PWD = "";
$DB = "yeticave";

$link = mysqli_connect($HOST, $USER, $PWD, $DB);
if (!$link) {
    $layout_content = "Ошибка подключения. " . mysqli_connect_error();
} else {
    $sql = "SELECT c.name, c.code FROM categories AS c";
    $result = mysqli_query($link, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $id = intval($_GET['id']);
    $sql = "SELECT id FROM lots WHERE id = {$id}";
    $result = mysqli_query($link, $sql);
    $result->num_rows == 1 ? $is_id_exist = true : $is_id_exist = false;

    if ($is_id_exist) {
        $sql = "SELECT 
                    l.id,
                    l.title, 
                    l.img_url, 
                    l.start_cost, 
                    l.step_bet, 
                    l.date_end, 
                    l.description,
                    c.name AS category 
                    FROM lots AS l 
                    LEFT JOIN categories AS c ON l.category_id = c.id 
                    WHERE l.id = {$id}";
        $result = mysqli_query($link, $sql);
        $lot = mysqli_fetch_assoc($result);
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

