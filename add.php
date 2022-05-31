<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name();
$categories = get_categories($link);
$errors = [];

$user_id = get_user_id_session();
if ($user_id === null) {
    header("Location: /403.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot_form_data = filter_input_array(INPUT_POST, [
        'name' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'category_id' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'description' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'img' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'begin_price' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'bid_step' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        'date_completion' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
    ], true);

    $categories_id = array_column($categories, 'id');
    $errors = validate_form_lot($lot_form_data, $categories_id, $_FILES);

    $errors = array_filter($errors);

    if (!$errors) {
        if (add_lot($link, $lot_form_data, $user_id)) {
            $lot_id = mysqli_insert_id($link);
            header("Location: /lot.php?id=" . $lot_id);
            exit();
        }
    }
}

$content = include_template('add.php', ['categories' => $categories, 'errors' => $errors]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => 'Добавление лота'
]);

print($layout_content);
