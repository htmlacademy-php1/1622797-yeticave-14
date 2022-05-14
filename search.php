<?php
require_once __DIR__ . '/init.php';

$categories = get_categories($link);
$pagination_limit = $config['pagination_limit'];
$user_name = check_session_name($_SESSION);


$cur_page = $_GET['page'] ?? 1;
check_current_page($cur_page);


$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
$search = trim(strip_tags($search));


$search_result = get_lot_by_search($link, $search, $cur_page, $pagination_limit);


$count_lots_from_search = get_count_lots_from_search($link, $search);


if ($search === '' || empty($search_result)) {
    $search = 'Ничего не найдено по вашему запросу';
}


$pagination = get_pagination_list($cur_page, $count_lots_from_search, $pagination_limit);


$content = include_template('search.php', [
    'categories' => $categories,
    'search' => $search,
    'search_result' => $search_result,
    'cur_page' => $cur_page,
    'pagination' => $pagination
]);

$layout_content = include_template('layout.php', [
    'categories' => $categories,
    'content' => $content,
    'user_name' => $user_name,
    'title' => 'Результаты поиска'
]);

print($layout_content);
