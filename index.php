<?php
require_once ('helpers.php');
require_once ('data.php');

$content = include_template('main.php', ['lots' => $lots, 'categories' => $categories]);

$layout_content = include_template('layout.php', ['content' => $content, 'is_auth' => $is_auth, 'user_name' => $user_name, 'title' => $title, 'categories' => $categories]);

print($layout_content);
?>