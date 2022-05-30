<?php

/**
 * @var mysqli $link
 * @var mysqli $config
 */

require_once __DIR__ . '/init.php';

$user_name = check_session_name();
$categories = get_categories($link);
$categories_ids = get_categories_ids($categories);
$pagination_limit = $config['pagination_limit'];

$cur_page = intval($_GET['page'] ?? 1);

$category_id = intval($_GET['category']);
$category_id = mysqli_real_escape_string($link, $category_id);

if (!in_array($category_id, $categories_ids)) {
    header("Location: /404.php");
}

$lots = get_lot_by_category($link, $category_id, $cur_page, $pagination_limit);
$count_lots = get_count_lot_by_category($link, $category_id);

$page_count = ceil($count_lots / $pagination_limit);
$pages = range(1, $page_count);

if (!in_array($cur_page, $pages)) {
    header("Location: /404.php");
}

$content = include_template('all-lots.php', [
    'categories' => $categories,
    'category_id' => $category_id,
    'lots' => $lots,
    'pages' => $pages,
    'page_count' => $page_count,
    'cur_page' => $cur_page
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'user_name' => $user_name,
    'title' => 'Лоты по категориям'
]);

print($layout_content);
