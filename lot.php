<?php
/**
 * @var mysqli $link
 */
require_once __DIR__ . '/init.php';

$user_id = get_user_id_session();
$categories = get_categories($link);

$lot_id = $_GET['id'];

$lot_data = get_lot_id($link, $lot_id);
if ($lot_data === null) {
    header("Location: /404.php");
    exit();
}

$content = include_template('lot.php', ['categories' => $categories, 'lot' => $lot_data, 'user_id' => $user_id]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => $lot_data['name']
]);

print($layout_content);
