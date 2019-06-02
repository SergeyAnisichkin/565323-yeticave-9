<?php
require_once "helpers.php";

$page_title = "Лот";
$lot = [];
$bet = [];
$bets_lot = [];
$categories = getCategories($link);
$lot_id = $_GET['id'] ?? "";
$display_form_bet = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bet['cost'] = strip_tags($_POST['cost']);
    $user_id = $_SESSION['user']['id'];
    $lot_id = $_SESSION['lot']['id'];
    $bet_min = $_SESSION['lot']['bet_min'];
    if (isCostInvalid($bet['cost'])) {
         $bet['errors'] = 'Ставка - целое положительное число'; 
    } elseif ($bet['cost'] < $bet_min) {
        $bet['errors'] = 'Ставка меньше минимальной';
    }
    if (!isset($bet['errors'])) {
        insertBet($link, $user_id, $lot_id, $bet['cost']);
    }
}

if (isIdExist($link, $lot_id)) {
    $lot = getLotById($link, $lot_id);
    $lot['cost'] = getMaxCostBets($link, $lot_id) ?: $lot['start_cost'];
    $lot['bet_min'] = $lot['cost'] + $lot['step_bet'];
    $bets_lot = getBets($link, $lot_id);
    $last_bet = array_search($lot['cost'], array_column($bets_lot, 'cost'));
    if (isset($_SESSION['user'])) {
        $display_form_bet = $lot['author_user_id'] !== $_SESSION['user']['id'];
        $display_form_bet = $display_form_bet && date_create($lot['date_end']) > date_create('today');
        $display_form_bet = $display_form_bet && $bets_lot[$last_bet]['user_id'] !== $_SESSION['user']['id'];
    }
    
    $page_content = include_template('lot.php', [
        'lot' => $lot,
        'categories' => $categories,
        'bet' => $bet,
        'bets_lot' => $bets_lot,
        'display_form_bet' => $display_form_bet,
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
]);

print($layout_content);

