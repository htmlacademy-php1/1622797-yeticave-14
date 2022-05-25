<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name();
$user_id = get_user_id_session();
$categories = get_categories($link);

$user_active_lot = get_active_bets($link, $user_id);
$user_win_lot = get_win_bets($link, $user_id);
$user_finish_lot = get_finish_bets($link, $user_id);

$content = include_template('my-bets.php', [
    'categories' => $categories,
    'user_active_lot' => $user_active_lot,
    'user_win_lot' => $user_win_lot,
    'user_finish_lot' => $user_finish_lot
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'user_name' => $user_name,
    'title' => 'Мои ставки'
]);

print($layout_content);
