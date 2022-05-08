<?php
require_once __DIR__ . '/init.php';

$sql_cat = get_categories($link);

$lot_id = $_GET['id'];

$lot = get_lot_id($link, $lot_id);
if ($lot === null) {
    header("Location: /404.php");
}

$content = include_template('lot.php', ['categories' => $sql_cat, 'lot' => $lot]);

$layout_content = include_template('layout.php', [
    'categories' => $sql_cat,
    'content' => $content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title
]);

print($layout_content);
