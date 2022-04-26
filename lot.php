<?php
require_once 'init.php';
require_once 'functions.php';
require_once 'data.php';

$lot_id = $_GET['id'];

if (!isset($_GET['id'])) {
    header("Location:/404.php");
}
if (empty($_GET['id'])) {
    header("Location:/404.php");
}

$sqlCat = get_categories($link);
$lot = get_lot_id($link, $lot_id);

$content = include_template('lot.php', ['categories' => $sqlCat, 'lot' => $lot]);

$layout_content = include_template('layout.php', ['categories' => $sqlCat, 'content' => $content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'title' => $title]);

print($layout_content);
