<?php
session_start();
$HOST = "localhost";
$USER = "root";
$PWD = "";
$DB = "yeticave";

$link = mysqli_connect($HOST, $USER, $PWD, $DB);
mysqli_query($link,'SET CHARACTER SET utf8');
if (!$link) {
    $layout_content = "Ошибка подключения: " . mysqli_connect_error();
    die();
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
    $sql = "SELECT id FROM lots WHERE id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
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
            WHERE l.id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}
function getCategoryIdByName($link, $category) {
    $sql = "SELECT c.id, c.name FROM categories AS c 
                WHERE c.name = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $category, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
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
function isEmailExist($link, $email) {
    $sql = "SELECT id, email FROM users WHERE email = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $email, 
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_fetch($stmt);
}
function insertUserDB($link, $sign_up) {
    $sql = "INSERT INTO users (
                date_add, 
                email, 
                users.name, 
                users.password, 
                contacts
            ) VALUES (NOW(), ?, ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $sign_up['email'], 
        $sign_up['name'], 
        password_hash($sign_up['password'], PASSWORD_DEFAULT), 
        $sign_up['message']
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}
function getCostFromBets($link, $lot_id) {
    $sql = "SELECT MAX(cost) AS cost FROM bets 
                WHERE lot_id = ?";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $lot_id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $array = $result ? mysqli_fetch_assoc($result) : null;
    return $array['cost'] ?? null;
}
function getBets($link, $lot_id) {
    $sql = "SELECT 
            b.date_add,
            b.cost, 
            u.name 
            FROM bets AS b 
            LEFT JOIN users AS u ON b.user_id = u.id 
            WHERE b.lot_id = ? ";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $lot_id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
}
function insertBet($link, $user_id, $lot_id, $bet_cost) {
    $sql = "INSERT INTO bets (
                date_add, 
                cost, 
                user_id, 
                lot_id
            ) VALUES (NOW(), ?, ?, ?)";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $bet_cost, 
        $user_id, 
        $lot_id
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($link);
}

function getUserBets($link, $user_id) {
    $sql = "SELECT 
            b.date_add,
            b.cost, 
            l.title,
            l.id,
            l.img_url,
            l.date_end,
            c.name
            FROM bets AS b 
            LEFT JOIN lots AS l ON b.lot_id = l.id
            LEFT JOIN categories AS c ON l.category_id = c.id 
            WHERE b.user_id = ? ";
    $stmt = db_get_prepare_stmt($link, $sql, [
        $user_id, 
    ]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : null;
}