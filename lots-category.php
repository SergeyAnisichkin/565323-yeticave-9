<?php
require_once "helpers.php";

$page_title = "Лоты по категории";
$page_number = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page_number = $page_number > 0 ? $page_number : 1;
$page_limit = 9;

$categories = getCategories($link);
$category_id = $_GET['id'] ? intval($_GET['id']) : "";
$check_category_id = array_search($category_id, array_column($categories, 'id'));

if ($check_category_id === false) {
    $page_content = include_template('404.php', [
        'categories' => $categories,
    ]);
} else {
    $lots_category_all = getLotsByCategory($link, $category_id, 1, 1000);
    $end_page = (count($lots_category_all) % 9) ? 1 : 0;
    $count_pages = floor(count($lots_category_all) / 9) + $end_page;
    $lots_category = getLotsByCategory($link, $category_id, $page_number, $page_limit);
    $category_name = $categories[$check_category_id]['name'];

    $page_content = include_template('lots-category.php', [
        'categories' => $categories,
        'lots_category' => $lots_category,
        'category_name' => $category_name,
        'page_number' => $page_number,
        'count_pages' => $count_pages,
        'cat_id' => $category_id,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $page_title,
    'categories' => $categories,
]);

print($layout_content);