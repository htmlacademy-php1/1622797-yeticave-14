<?php
/**
 * @var mysqli $link
 */
require_once __DIR__ . '/init.php';

$categories = get_categories($link);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $signup_form = filter_input_array(INPUT_POST, [
        'email' => FILTER_DEFAULT,
        'password' => FILTER_DEFAULT,
        'first_name' => FILTER_DEFAULT,
        'contact' => FILTER_DEFAULT
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
