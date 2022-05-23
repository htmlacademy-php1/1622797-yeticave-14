<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name();
$categories = get_categories($link);
$lots = get_lots($link);
$title = 'YetiCave - интернет-аукцион сноубордического снаряжения';

$content = include_template('main.php', ['categories' => $categories, 'lots' => $lots]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'user_name' => $user_name,
    'title' => $title
]);

print($layout_content);
