<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$categories = get_categories($link);

header("HTTP/1.1 403 Forbidden");

$content = include_template('403.php', ['categories' => $categories]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => 'Доступ запрещен'
]);

print($layout_content);
