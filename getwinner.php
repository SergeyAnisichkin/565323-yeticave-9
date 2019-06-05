<?php
require_once "vendor/autoload.php";
$site_address = 'http://565323-yeticave-9/';

$winners = getWinners($link) ?: [];
if (count($winners)) {
    foreach ($winners as $winner) {
        $setWinner = setWinnerDB($link, $winner['lot_id'], $winner['user_id']);
        $body = include_template('email.php', [
            'winner' => $winner,
            'site_address' => $site_address,
        ]);
        if ($setWinner && $body) {
            sendMailWin($winner['email'], $body);
        }
    }
}



