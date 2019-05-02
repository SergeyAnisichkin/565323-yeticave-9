<?php
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
}

function getOpenLots($link) {
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
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}
function isIdExist($link, $id) {
    $id = intval($id);
    $sql = "SELECT id FROM lots WHERE id = {$id}";
    $result = mysqli_query($link, $sql);
    return $result->num_rows > 0 ?? false;
}
function getLotById($link, $id) {
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
    return mysqli_fetch_assoc($result);
}
