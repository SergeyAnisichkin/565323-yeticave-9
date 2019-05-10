<?php
require_once "helpers.php";
require_once "model.php";

$page_title = "YetiCave";

$categories = getCategories($link);
$lots = getOpenLots($link);

$page_content = include_template('index.php', [
    'lots' => $lots,
    'categories' => $categories,
]);
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => $page_title,
    'categories' => $categories,
]);

print($layout_content);
