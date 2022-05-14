<?php

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


/**
 * Функция для раздение тысячных долей от полной суммы
 * 
 * @param int $price Переменная стоимости лота
 * @return string Если сумма больше 1000 рублей, то отделит тысячные доли от числа
 */
function lot_cost(int $price): string
{
    if ($price < 1000) {
        return ceil($price) . '₽';
    } else {
        return number_format($price, 0, null, ' ') . '₽';
    }
}


/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}


/**
 * Функция приводит тип текущей страницы к целому числу
 *
 * @param string current_page Переменная с текущей страницей
 *
 * @return void Если номер страницы меньше или равен нулю, то переводим на 404 страницу
 */
function check_current_page(string $current_page)
{
    $current_page = (int)$current_page;
    if ($current_page <= 0) {
        header("Location: /404.php");
        exit();
    }
}


/**
 * Функуция получает массив переменных для пагинации
 *
 * @param int cur_page Получает текущую страницу
 * @param mixed count_lots_from_search Получает количество лотов на странице
 * @param mixed pagination_limit Получает количество лотов на одной странице
 *
 * @return array Возвращает массив данных для пагинации на странице
 */
function get_pagination_list(int $cur_page, $count_lots_from_search, $pagination_limit): array
{

    $page_count = ceil($count_lots_from_search / $pagination_limit);
    $pages = range(1, $page_count);

    $prev = ($cur_page > 1) ? $cur_page - 1 : $cur_page;
    $next = ($cur_page < $page_count) ? $cur_page + 1 : $cur_page;

    return [
        'prev_page' => $prev,
        'next_page' => $next,
        'page_count' => $page_count,
        'pages' => $pages,
        'cur_page' => $cur_page,
        'lot_limit' => $count_lots_from_search
    ];
}
