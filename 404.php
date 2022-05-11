<?php
require_once __DIR__ . '/init.php';

$categories = get_categories($link);

header("HTTP/1.1 404 Not Found");

$content = include_template('404.php', ['categories' => $categories]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => 'Страница не найдена'
]);

print($layout_content);
