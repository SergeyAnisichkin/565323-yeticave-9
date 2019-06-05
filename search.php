<?php
require_once "helpers.php";

$page_title = "Результаты поиска";
$page_number = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page_number = $page_number > 0 ? $page_number : 1;
$count_pages = 1;
$page_limit = 9;

$categories = getCategories($link);
$find = trim(strip_tags($_GET['search'] ?? ""));
$lots = [];

if ($find) {
    $lots_all = getLotBySearch($link, $find, 1, 1000);
    $end_page = (count($lots_all) % 9) ? 1 : 0;
    $count_pages = floor(count($lots_all) / 9) + $end_page;
    $lots = getLotBySearch($link, $find, $page_number, $page_limit);
}

$page_content = include_template('search.php', [
    'lots' => $lots,
    'categories' => $categories,
    'find' => $find,
    'page_number' => $page_number,
    'count_pages' => $count_pages,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $page_title,
    'categories' => $categories,
]);

print($layout_content);
