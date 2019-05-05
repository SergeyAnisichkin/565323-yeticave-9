<?php
$is_auth = rand(0, 1);
$user_name = 'Сергей';
$HOST = "localhost";
$USER = "root";
$PWD = "";
$DB = "yeticave";

$link = mysqli_connect($HOST, $USER, $PWD, $DB);
mysqli_query($link,'SET CHARACTER SET utf8');
if (!$link) {
    $layout_content = "Ошибка подключения: " . mysqli_connect_error();
}

function getCategories($link) {
    $sql = "SELECT c.name, c.code FROM categories AS c";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
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
                WHERE l.date_end > NOW()";
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
function getCategoryIdByName($link, $cat) {
    $sql = "SELECT c.id, c.name FROM categories AS c WHERE c.name = '{$cat}'";
    $result = mysqli_query($link, $sql);
    return mysqli_fetch_assoc($result);
}
function insertLotDB($link, $lot) {
    $category = getCategoryIdByName($link, $lot['category']);
    $user_id = 1;
    $sql = "INSERT INTO lots (
                date_add, 
                author_user_id, 
                category_id, 
                date_end, 
                title, 
                lots.description, 
                img_url,
                start_cost,
                step_bet
            ) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $user_id, 
        $category['id'], 
        $lot['lot-date'], 
        $lot['lot-name'], 
        $lot['message'], 
        $lot['path'], 
        $lot['lot-rate'], 
        $lot['lot-step']
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}