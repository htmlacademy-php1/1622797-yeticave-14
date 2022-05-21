<?php

/**
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$user_id = get_user_id_session();
$categories = get_categories($link);
$errors = [];
$lot_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$lot_data = get_lot_id($link, (int)$lot_id);
if (!isset($lot_data['id'])) {
    header("Location: /404.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form_bets = filter_input_array(INPUT_POST, ['price' => FILTER_DEFAULT], true);
    $errors = validate_form_bets($form_bets, $lot_data);

    if (!$errors) {
        if (add_bets($link, $form_bets, $user_id, $lot_id)) {
            header("Location: /lot.php?id=" . $lot_id);
        }
    }
}

$lot_bets = get_lots_bets($link, $lot_id);

$date_completion = $lot_data['date_completion'];
$lot_creator = $lot_data['user_id'];

$content = include_template('lot.php', [
    'categories' => $categories,
    'lot' => $lot_data,
    'lot_bets' => $lot_bets,
    'user_id' => $user_id,
    'date_completion' => $date_completion,
    'lot_creator' => $lot_creator,
    'lot_id' => $lot_id,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'title' => $lot_data['name']
]);

print($layout_content);
