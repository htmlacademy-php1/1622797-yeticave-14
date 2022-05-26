<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name();
$user_id = get_user_id_session();
$categories = get_categories($link);

$user_bets = get_bets_user($link, $user_id);

$content = include_template('my-bets.php', [
    'categories' => $categories,
    'user_bets' => $user_bets,
    'link' => $link
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'user_name' => $user_name,
    'title' => 'Мои ставки'
]);

print($layout_content);
