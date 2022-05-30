<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name();
$categories = get_categories($link);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_form = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_SPECIAL_CHARS,
        'password' => FILTER_SANITIZE_SPECIAL_CHARS
    ], true);

    $errors = validate_login_form($link, $login_form);
    if (!$errors && authentication($link, $login_form)) {
        header("Location: /");
        exit();
    }
}

$content = include_template('login.php', ['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => 'Вход на сайт'
]);

print($layout_content);
