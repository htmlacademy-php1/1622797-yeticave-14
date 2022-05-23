<?php

/**
 * @var mysqli $link
 * @var mysqli $config
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name($_SESSION);
$categories = get_categories($link);
$pagination_limit = $config['pagination_limit'];

$cur_page = $_GET['page'] ?? 1;
check_current_page($cur_page);

$category_id = $_GET['category'];

$lots = get_lot_by_category($link, $category_id, $cur_page, $pagination_limit);
$count_lots = get_count_lot_by_category($link, $category_id);

$pagination = get_pagination_list($cur_page, $count_lots, $pagination_limit);

$content = include_template('all-lots.php', [
    'categories' => $categories,
    'category_id' => $category_id,
    'lots' => $lots,
    'pagination' => $pagination
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'user_name' => $user_name,
    'title' => 'Лоты по категориям'
]);

print($layout_content);
