<?php

/**
 * @var mysqli $config
 * @var mysqli $link
 */

require_once __DIR__ . '/init.php';

$categories = get_categories($link);
$pagination_limit = $config['pagination_limit'];
$user_name = check_session_name();

$cur_page = $_GET['page'] ?? 1;

$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
$search = trim(strip_tags($search));

$search_result = get_lot_by_search($link, $search, $cur_page, $pagination_limit);

$count_lots_from_search = get_count_lots_from_search($link, $search);

if ($search === '' || empty($search_result)) {
    $search = 'Ничего не найдено по вашему запросу';
}

$page_count = ceil($count_lots_from_search / $pagination_limit);
$pages = range(1, $page_count);

if (!in_array($cur_page, $pages)) {
    header("Location: /404.php");
}

$content = include_template('search.php', [
    'categories' => $categories,
    'search' => $search,
    'search_result' => $search_result,
    'cur_page' => $cur_page,
    'page_count' => $page_count,
    'pages' => $pages
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'user_name' => $user_name,
    'title' => 'Результаты поиска'
]);

print($layout_content);
