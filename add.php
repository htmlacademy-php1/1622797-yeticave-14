<?php
require_once __DIR__ . '/init.php';

$sql_cat = get_categories($link);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $lot_form_data = filter_input_array(INPUT_POST, [
        'name' => FILTER_DEFAULT,
        'category_id' => FILTER_DEFAULT,
        'description' => FILTER_DEFAULT,
        'img' => FILTER_DEFAULT,
        'begin_price' => FILTER_DEFAULT,
        'bid_step' => FILTER_DEFAULT,
        'date_completion' => FILTER_DEFAULT
    ], true);

    $categories_id = array_column($sql_cat, 'id');
    $errors = validate_form_lot($lot_form_data, $categories_id, $_FILES);

    $errors = array_filter($errors);

    if (!$errors) {
        if (add_lot($link, $lot_form_data, $_FILES)) {
            $lot_id = mysqli_insert_id($link);
            header("Location: /lot.php?id=" . $lot_id);
        }
    }
}

$content = include_template('add.php', ['categories' => $sql_cat, 'errors' => $errors]);

$layout_content = include_template('layout.php', [
    'categories' => $sql_cat,
    'content' => $content,
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'title' => $title
]);

print($layout_content);
