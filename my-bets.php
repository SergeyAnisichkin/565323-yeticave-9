<?php
require_once "helpers.php";
require_once "model.php";

if (!isset($_SESSION['user'])) {
    header('HTTP/1.0 403 Forbidden');
    echo 'Доступ заблокирован, необходимо зарегистрироваться!';
    exit();
}
$page_title = "Мои ставки";
$categories = getCategories($link);
$bets = getUserBets($link, $_SESSION['user']['id']);  

$page_content = include_template('my-bets.php', [
    'bets' => $bets,
    'categories' => $categories,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $page_title,
    'categories' => $categories,
]);

print($layout_content);



