<?php
require_once __DIR__ . '/init.php';

$sql_cat = get_categories($link);
$sql_lots = get_lots($link);

$content = include_template('main.php', ['categories' => $sql_cat, 'lots' => $sql_lots,]);

$layout_content = include_template('layout.php', [
    'categories' => $sql_cat,
    'content' => $content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title
]);

print($layout_content);
