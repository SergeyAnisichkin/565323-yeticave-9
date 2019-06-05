<?php
require_once "helpers.php";
require_once "getwinner.php";

$page_title = "YetiCave";

$categories = getCategories($link);
$lots = getOpenLots($link);
if (count($lots)) {
    foreach ($lots as &$lot) {
        $lot['cost'] = getMaxCostBets($link, $lot['id']) ?: $lot['start_cost'];
        $lot['bets'] = "Стартовая цена";
        $bets_count = count(getBets($link, $lot['id']));
        if ($bets_count) {
            $lot['bets'] = $bets_count . get_noun_plural_form($bets_count, ' ставка', ' ставки', ' ставок');
        }
    }
}

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
