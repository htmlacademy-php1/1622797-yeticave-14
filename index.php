<?php
require_once 'init.php';
require_once 'functions.php';
require_once 'data.php';

$sqlCat = get_cat($link);
$sqlLots = get_lots($link);

$content = include_template('main.php', ['categories' => $sqlCat, 'lots' => $sqlLots,]);

$layout_content = include_template('layout.php', ['categories' => $sqlCat, 'content' => $content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'title' => $title]);

print($layout_content);