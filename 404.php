<?php
require_once 'init.php';
require_once 'functions.php';
require_once 'data.php';

$sqlCat = get_categories($link);

header("HTTP/1.1 404 Not Found");

$content = include_template('404.php', ['categories' => $sqlCat]);

$layout_content = include_template('layout.php', ['categories' => $sqlCat, 'content' => $content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'title' => 'Страница не найдена']);

print($layout_content);