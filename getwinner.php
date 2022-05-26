<?php

/**
 * @var mysqli $link
 * @var mysqli $config
 */

require_once __DIR__ . '/init.php';

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

$dsn = 'smtp://' . $config['dsn']['login'] . ':' . $config['dsn']['password'] . ':' . $config['dsn']['server'] .
    ':' . $config['dsn']['port'];
$transport = Transport::fromDsn($dsn);

$lots = get_lots_whithout_winners($link);

$last_bets_lots = [];
foreach ($lots as $lot) {
    $last_bets_lots[] = get_last_bets($link, $lot['lot_id']);
}
$last_bets_lots = array_filter($last_bets_lots);
foreach ($last_bets_lots as $last_bets) {
    add_winner_lot($link, $last_bets['user_id'], $last_bets['lot_id']);
}

$winners = $last_bets_lots;

$mailer = new Mailer($transport);

$message = new Email();
$message->subject("Ваша ставка победила");
$message->from("keks@phpdemo.ru");

foreach ($winners as $winner) {
    $message->to($winner['email']);
    $message_content = include_template('email.php', ['winner' => $winner]);
    $message->html($message_content);
    $mailer->send($message);
}
