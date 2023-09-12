<?php

/**
 * Функция получает значения из POST-запроса
 * @param mixed значения POST-запросов
 * @return void возвращает значения из POST-запроса
 */
function get_post_val($name)
{
    return $_POST[$name] ?? "";
}


/**
 * Функция проверяет все данные введенные в форму
 * @param array $lot_form_data Массив с данными из формы
 * @param array $category_ids Проверяет соответствует ли категория к уже существующим
 * @param array $files Передает картинку из формы
 * @return array Возвращает массив с данными и файлом из формы
 */
function validate_form_lot(array $lot_form_data, array $category_ids, array $files): array
{
    $errors = [
        'name' => validate_lot_name($lot_form_data['name']),
        'category_id' => validate_category($lot_form_data['category_id'], $category_ids),
        'description' => validate_description($lot_form_data['description']),
        'img' => validate_img($files),
        'begin_price' => validate_price($lot_form_data['begin_price']),
        'bid_step' => validate_price($lot_form_data['bid_step']),
        'date_completion' => validate_date($lot_form_data['date_completion'])
    ];

    return array_filter($errors);
}


/**
 * Функция проверяет поле с Названием лота
 * @param string $value Проверяет значение на соответствие формату и количеству символов
 * @return string|null Возвращает ошибки, если данные заполнены не верно
 */
function validate_lot_name(string $value): ?string
{
    if ($value === "") {
        return "Поле должно быть заполнено";
    }
    if (mb_strlen($value) > 255) {
        return "Количество не должно превышать 255 символов";
    }

    return null;
}


/**
 * Функция проверяет поле с Описанием лота
 * @param string $value Проверяет значение на соответствие формату и количеству символов
 * @return string|null Возвращает ошибки, если данные заполнены не верно
 */
function validate_description(string $value): ?string
{
    if ($value === "") {
        return "Поле должно быть заполнено";
    }

    return null;
}


/**
 * Функция проверяет поле с Категориями лота
 * @param string $id Проверяет id категории
 * @param array $category_ids Проверка id с массивом существующих категорий
 * @return string|null Возвращает ошибку, если была введена несуществующая категория
 */
function validate_category(string $id, array $category_ids): ?string
{
    if (!in_array($id, $category_ids)) {
        return "Указана несуществующая категория";
    }

    return null;
}


/**
 * Функция проверяет значения введенные в поля Начальная цена и Шаг ставки
 * @param string $price Передает значение поля с введенной суммой
 * @return string|null Возвращает ошибку, если цифра была меньше нуля или введены буквы, вместо цифр
 */
function validate_price(string $price): ?string
{
    if (!is_numeric($price)) {
        return 'Введите целое число';
    }
    if ($price <= 0) {
        return "Значение должно быть больше нуля";
    }
    if ($price > 100000000) {
        return "Цена должна быть не больше 100 000 000 миллионов";
    }
    return null;
}


/**
 * Функция проверяет поле с датой окончания действия лота
 * @param string $date Получает введенную дату
 * @return string|null Возвращает ошибку, если формат даты был введен неверно
 */
function validate_date(string $date): ?string
{
    if (!is_date_valid($date)) {
        return "Значение должно быть датой в формате ГГГГ-ММ-ДД";
    }
    $timer = get_dt_range($date, 'now');

    if ($timer['hour'] < 24) {
        return "Указанная дата должна быть больше текущей даты, хотя бы на один день";
    }

    return null;
}


/**
 * Функция проверяет файл на соответствие формату jpeg или png
 * @param array $files Получает файл
 * @return string|null Возвращает ошибку, если был загружен неверный формат изображения
 */
function validate_img(array $files): ?string
{
    if (!isset($files['img']['name']) || $files['img']['name'] === '') {
        return "Загрузите картинку";
    }

    $tmp_name = $files['img']['tmp_name'];
    $file_type = mime_content_type($tmp_name);

    if ($file_type !== 'image/png' && $file_type !== 'image/jpeg') {
        return "Загрузите картинку в формате png или jpeg";
    }

    return null;
}


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
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}
