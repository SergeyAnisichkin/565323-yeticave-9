<?php
$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$lots = [
    [
        'title' => "2014 Rossignol District Snowboard",
        'category' => "Доски и лыжи",
        'cost' => 10999,
        'img' => "img/lot-1.jpg",
    ],
    [
        'title' => "DC Ply Mens 2016/2017 Snowboard",
        'category' => "Доски и лыжи",
        'cost' => 159999,
        'img' => "img/lot-2.jpg",
    ],
    [
        'title' => "Крепления Union Contact Pro 2015 года размер L/XL",
        'category' => "Крепления",
        'cost' => 8000,
        'img' => "img/lot-3.jpg",
    ],
    [
        'title' => "Ботинки для сноуборда DC Mutiny Charocal",
        'category' => "Ботинки",
        'cost' => 10999,
        'img' => "img/lot-4.jpg",
    ],
    [
        'title' => "Куртка для сноуборда DC Mutiny Charocal",
        'category' => "Одежда",
        'cost' => 7500,
        'img' => "img/lot-5.jpg",
    ],
    [
        'title' => "Маска Oakley Canopy",
        'category' => "Разное",
        'cost' => 10999,
        'img' => "img/lot-6.jpg",
    ],
];

require_once "helpers.php";

$page_content = include_template('index.php', ['lots' => $lots, 'categories' => $categories]);

$layout_content = include_template('layout.php',
    ['content' => $page_content,
    'title' => "YetiCave - Главная",
    'categories' => $categories]);

print($layout_content);
