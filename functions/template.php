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
 * Функция приводит значение к человокочитаемому формату времени
 * @param string $date Принимает дату в прошлом
 * @param string $cur_date Принимает текущаю дату
 * @return string|null Возвращает дату и время в человекочитаемом виде
 */
function get_time_bet(string $date, string $cur_date): ?string
{
    $date = date_create($date);
    $cur_date = date_create($cur_date);

    $diff = date_diff($cur_date, $date);

    $days = $diff->days;
    $hours = $diff->h;
    $minutes = $diff->i;

    if ($days === 0 && $hours !== 0) {
        return $hours . ' ' . get_noun_plural_form($hours, 'час назад', 'часа назад', 'часов назад');
    }
    if ($hours === 0) {
        return $minutes . ' ' . get_noun_plural_form($minutes, 'минуту назад', 'минуты назад', 'минут назад');
    }

    if ($days === 1) {
        return date_format($date, 'Вчера в H:i');
    }

    if ($days > 1) {
        return date_format($date, 'd-m-y в H:i');
    }

    return null;
}
