<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name();
$categories = get_categories($link);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $signup_form = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_SPECIAL_CHARS,
        'password' => FILTER_SANITIZE_SPECIAL_CHARS,
        'first_name' => FILTER_SANITIZE_SPECIAL_CHARS,
        'contact' => FILTER_SANITIZE_SPECIAL_CHARS
    ], true);

    $errors = validate_signup_form($link, $signup_form);
    if (!$errors) {
        add_user($link, $signup_form);
        header("Location: /login.php");
        exit();
    }
}

$content = include_template('sign-up.php', ['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => 'Страница регистрации'
]);

print($layout_content);
