<?php

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
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
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
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
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
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
function lot_cost(int $price): string {
    if ($price < 1000) {
        return ceil($price) . '₽';
    } else {
        return number_format($price, 0, null, ' ') . '₽';
    }
}

/**
 * Функция для посчета времени до закрытия лота
 * 
 * @param string $closetime Время закрытия лота
 * @param string $curtime Настоящее время
 * @return array Возвращает время в формате ЧЧ:ММ до закрытия лота
 */
function get_dt_range(string $closetime, string $curtime): array {
    $dt_diff = strtotime($closetime) - strtotime($curtime);
    if($dt_diff < 0) {
        $interval = ['hour' => 0, 'minute' => 0];
        return $interval;
    }
    $hours = floor($dt_diff / 3600);
    $minuts = floor($dt_diff % 3600 / 60);
    $interval = ['hour' => $hours, 'minute' => $minuts];
    return $interval;
}

/**
 * Функция для работы с категориями из MySQL
 *
 * @param mysqli $link Отправляет запрос в БД для получения списка категорий
 * @return array Возвращает массив списка категорий
 */
function get_categories(mysqli $link): array {
    $sqlCat = 'SELECT * FROM categories';
    $result = mysqli_query($link, $sqlCat);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        print('Ошибка MySQL: ' . $error);
    }
}

/**
 * Функция для работы с лотами
 *
 * @param mysqli $link Отправляет запрос в БД для получения списка последних открытых лотов
 * @return array Возвращает массив с 6 последними открытыми лотами
 */
function get_lots(mysqli $link): array {
    $sqlLots = 'SELECT l.creation_time, l.name as lot_name, l.begin_price, l.img, l.date_completion, l.category_id, c.name as cat_name
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE l.date_completion > NOW()
    ORDER BY l.creation_time DESC LIMIT 6';
    $result = mysqli_query($link, $sqlLots);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $error = mysqli_error($link);
        print('Ошибка MySQL: ' . $error);
    }
}

function get_lot_id(mysqli $link, int $lot_id): array {
    $sql = 'SELECT lots.name, creation_time, description, img, begin_price, date_completion, bid_step, categories.name as category, categories.id 
    FROM lots
    JOIN categories on lots.category_id=categories.id
    WHERE categories.id=' . $lot_id;
    $result = mysqli_query($link, $sql);
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        $error = mysqli_error($link);
        print('Ошибка MySQL: ' . $error);
    }
}