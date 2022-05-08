<?php
require_once __DIR__ . '/init.php';

$sql_cat = get_categories($link);

header("HTTP/1.1 404 Not Found");

$content = include_template('404.php', ['categories' => $sql_cat]);

$layout_content = include_template('layout.php', [
    'categories' => $sql_cat,
    'content' => $content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => 'Страница не найдена'
]);

print($layout_content);
