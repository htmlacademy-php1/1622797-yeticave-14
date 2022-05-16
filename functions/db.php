<?php

/**
 * Функция осуществляет соединение с базой данных
 *
 * @param array config Передает конфиг данных для соединения
 *
 * @return mysqli Возвращает удачное соединение или ошибку
 */
function connect_db(array $config): mysqli
{
    $link = mysqli_connect($config['host'], $config['user'], $config['password'], $config['database']);
    mysqli_set_charset($link, "utf8");

    if (!$link) {
        $error = mysqli_connect_error();
        exit("Ошибка MySQL: " . $error);
    }

    return $link;
}


/**
 * Функция для работы с категориями из MySQL
 *
 * @param mysqli $link Отправляет запрос в БД для получения списка категорий
 * @return array Возвращает массив списка категорий
 */
function get_categories(mysqli $link): array
{
    $sqlCat = 'SELECT * FROM categories';
    $result = mysqli_query($link, $sqlCat);
    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция для выбора последних 6 новых лотов
 *
 * @param mysqli $link Отправляет запрос в БД для получения списка последних открытых лотов
 * @return array Возвращает массив с 6 последними открытыми лотами
 */
function get_lots(mysqli $link): array
{
    $sqlLots = 'SELECT l.id, l.creation_time, l.name as lot_name, l.begin_price, l.img, l.date_completion, l.category_id, c.name as cat_name
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE l.date_completion > NOW() GROUP BY (l.id)
    ORDER BY l.creation_time DESC LIMIT 6';
    $result = mysqli_query($link, $sqlLots);
    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция по выбору конкретного лота из строки запроса
 *
 * @param mysqli link Отправлять запрос в БД на получение лота
 * @param int $lot_id Переменная со строкой запроса
 *
 * @return array Возвращает лот с конкретным id
 */
function get_lot_id(mysqli $link, int $lot_id): ?array
{
    $sql = 'SELECT lots.id, lots.name, creation_time, description, img, begin_price, date_completion, bid_step, categories.name as category
    FROM lots
    JOIN categories on lots.category_id=categories.id
    WHERE lots.id=' . $lot_id;

    $result = mysqli_query($link, $sql);
    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_assoc($result);
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
function db_get_prepare_stmt($link, $sql, $data = [])
{
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
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
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

        if (mysqli_error($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}


/**
 * Функция осуществляет добавление нового лота
 *
 * @param mysqli link Передает соединение с БД
 * @param array lot_form_data Передает данные введенные из формы
 * @param mixed files Передает картинку из формы
 *
 * @return bool Возвращает удачное добавление лота или ошибку
 */
function add_lot(mysqli $link, array $lot_form_data, $user_id): bool
{
    $lot_form_data['img'] = upload_image($_FILES);
    $lot_form_data['date_completion'] = date("Y-m-d H:i:s", strtotime($lot_form_data['date_completion']));
    $lot_form_data['user_id'] = $user_id;

    $sql = 'INSERT INTO lots(name, creation_time, category_id, description, img, begin_price, bid_step, date_completion, user_id)
    VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($link, $sql, $lot_form_data);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return true;
}


/**
 * Функция проверяет email на повторение с уже сущестующим в БД
 *
 * @param mysqli link Соединение с БД
 * @param string email Передает введеный e-mail
 *
 * @return array Возвращает значение e-mail из существующих в таблицу users
 */
function get_user_by_email(mysqli $link, string $email): ?array
{
    $sql = 'SELECT id, name, email, password FROM users WHERE email= ?';

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $array_from_db = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $array_from_db[0] ?? null;
}


/**
 * Функция добавляет нового зарегистрированого юзера в БД
 *
 * @param mysqli link Соединение с БД
 * @param array signup_form Массив с данными из формы
 *
 * @return bool Возвращает удачное добавление юзера в БД или ошибку
 */
function add_user(mysqli $link, array $signup_form): bool
{
    $signup_form['password'] = password_hash($signup_form['password'], PASSWORD_DEFAULT);

    $sql = 'INSERT INTO users(creation_time, email, password, name, contact) VALUES (NOW(), ?, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($link, $sql, $signup_form);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return true;
}


/**
 * Функция осуществляет поиск по столбцам name, description в таблице лотов с ограничением по количеству элементов
 *
 * @param mysqli link Соединение с БД
 * @param string search Принимает поисковый запрос
 * @param int cur_page Текущая страница
 * @param int pagination_limit Лимит на количество выведенных лотов на странице
 *
 * @return array Возвращает массив с последними новыми лотами или ошибку
 */
function get_lot_by_search(mysqli $link, string $search, int $cur_page, int $pagination_limit): array
{
    $offset = $pagination_limit * ($cur_page - 1);
    $sql = 'SELECT l.id, l.name as lot_name, l.description, l.begin_price, l.img, l.date_completion, c.name as cat_name
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE MATCH(l.name, l.description) AGAINST(?)
    ORDER BY l.creation_time DESC LIMIT ' . $pagination_limit . ' OFFSET ' . $offset;

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция получает количество лотов найденное поиском
 *
 * @param mysqli link Соединение с БД
 * @param string search Принимает поисковый запрос
 *
 * @return int Возвращает количество лотов из БД или ошибку
 */
function get_count_lots_from_search(mysqli $link, string $search): int
{
    $sql = 'SELECT COUNT(*) as count FROM lots
    WHERE MATCH(name, description) AGAINST(?)';

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_assoc($result)['count'];
}
