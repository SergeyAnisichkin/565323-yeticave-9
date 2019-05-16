<?php
require_once "helpers.php";
require_once "model.php";

$page_title = "Результаты поиска";
$categories = getCategories($link);
$find = strip_tags($_GET['search'] ?? "");
$lots = [];

if ($find) {
    $lots = getLotBySearch($link, $find);
}

$page_content = include_template('search.php', [
    'lots' => $lots,
    'categories' => $categories,
    'find' => $find,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $page_title,
    'categories' => $categories,
]);

print($layout_content);