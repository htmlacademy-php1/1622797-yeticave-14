<?php

/**
 * Функция осуществляет соединение с базой данных
 * @param array config Передает конфиг данных для соединения
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
 * @param mysqli $link Отправляет запрос в БД для получения списка категорий
 * @return array Возвращает массив списка категорий
 */
function get_categories(mysqli $link): array
{
    $sql = 'SELECT * FROM categories';
    $result = mysqli_query($link, $sql);
    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция для выбора последних 6 новых лотов
 * @param mysqli $link Отправляет запрос в БД для получения списка последних открытых лотов
 * @return array Возвращает массив с 6 последними открытыми лотами
 */
function get_lots(mysqli $link): array
{
    $sql = 'SELECT l.id, l.creation_time, l.name as lot_name, l.begin_price, l.img, l.date_completion,
       l.category_id, c.name as cat_name
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE l.date_completion > NOW() GROUP BY (l.id)
    ORDER BY l.creation_time DESC LIMIT 6';
    $result = mysqli_query($link, $sql);
    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция по выбору конкретного лота из строки запроса
 * @param mysqli link Отправлять запрос в БД на получение лота
 * @param int $lot_id Переменная со строкой запроса
 * @return array|null Возвращает лот с конкретным id
 */
function get_lot_id(mysqli $link, int $lot_id): ?array
{
    $sql = 'SELECT lots.id, lots.name, lots.user_id, lots.creation_time, lots.description, lots.img,
       MAX(bets.price) as max_price, lots.begin_price, lots.date_completion, lots.bid_step, categories.name AS category
    FROM lots
    JOIN categories ON lots.category_id = categories.id
    LEFT JOIN bets ON lots.id = bets.lot_id
    WHERE lots.id =' . $lot_id;

    $result = mysqli_query($link, $sql);
    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_array($result);
}


/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
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
            } elseif (is_string($value)) {
                $type = 's';
            } elseif (is_double($value)) {
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
 * @param mysqli link Передает соединение с БД
 * @param array lot_form_data Передает данные введенные из формы
 * @param mixed files Передает картинку из формы
 * @return bool Возвращает удачное добавление лота или ошибку
 */
function add_lot(mysqli $link, array $lot_form_data, int $user_id): bool
{
    $lot_form_data['img'] = upload_image($_FILES);
    $lot_form_data['date_completion'] = date("Y-m-d H:i:s", strtotime($lot_form_data['date_completion']));
    $lot_form_data['user_id'] = $user_id;

    $sql = 'INSERT INTO lots(name, creation_time, category_id, description, img, begin_price, bid_step,
                 date_completion, user_id)
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
 * @param mysqli link Соединение с БД
 * @param string email Передает введеный e-mail
 * @return array|null Возвращает значение e-mail из существующих в таблицу users
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
 * @param mysqli link Соединение с БД
 * @param array signup_form Массив с данными из формы
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
 * @param mysqli link Соединение с БД
 * @param string search Принимает поисковый запрос
 * @param int cur_page Текущая страница
 * @param int pagination_limit Лимит на количество выведенных лотов на странице
 * @return array Возвращает массив с последними новыми лотами или ошибку
 */
function get_lot_by_search(mysqli $link, string $search, int $cur_page, int $pagination_limit): array
{
    $offset = $pagination_limit * ($cur_page - 1);
    $sql = 'SELECT l.id, l.name as lot_name, l.description, l.begin_price, l.img, l.date_completion, c.name as cat_name
    FROM lots l
    JOIN categories c ON c.id = l.category_id
    WHERE MATCH(l.name, l.description) AGAINST(?) AND l.date_completion > NOW()
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
 * @param mysqli link Соединение с БД
 * @param string search Принимает поисковый запрос
 * @return int Возвращает количество лотов из БД или ошибку
 */
function get_count_lots_from_search(mysqli $link, string $search): int
{
    $sql = 'SELECT COUNT(*) as count FROM lots
    WHERE MATCH(name, description) AGAINST(?) AND lots.date_completion > NOW()
    ORDER BY lots.creation_time DESC';

    $stmt = db_get_prepare_stmt($link, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_assoc($result)['count'];
}


/**
 * Функция добавляет ставку по лоту в БД
 * @param mysqli $link Соединение с БД
 * @param array $form_bets Данные из формы по добавлению ставки
 * @param string $user_id id пользователя оставившего ставку
 * @param string $lots_id id лота по которому оставили ставку
 * @return bool Возвращает успешное добавление ставки в БД и на страницу
 */
function add_bets(mysqli $link, array $form_bets, string $user_id, string $lots_id): bool
{
    $form_bets['user_id'] = $user_id;
    $form_bets['lots_id'] = $lots_id;

    $sql = 'INSERT INTO bets(creation_time, price, user_id, lot_id) VALUES (NOW(), ?, ?, ?)';

    $stmt = db_get_prepare_stmt($link, $sql, $form_bets);
    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        print("Ошибка MySQL: " . mysqli_error($link));
        exit();
    }

    return true;
}


/**
 * Функция получает ставки лота по id
 * @param mysqli $link Соединение с БД
 * @param int $lot_id Получает id лота
 * @return array Возвращает массив со ставками по лоту
 */
function get_lots_bets(mysqli $link, int $lot_id): array
{
    $sql = 'SELECT b.id, b.price, b.user_id, b.lot_id, b.creation_time, users.name
    FROM bets b
    JOIN users ON b.user_id = users.id
    WHERE b.lot_id =' . $lot_id . ' ORDER BY creation_time DESC ';

    $result = mysqli_query($link, $sql);

    if (!$result) {
        print("Ошибка MYSQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция получает лоты пользователя по его id
 * @param mysqli $link Соединение с БД
 * @param int $user_id Получает id пользователя
 * @return array Возвращает массив со ставками пользователя
 */
function get_bets_user(mysqli $link, int $user_id): array
{
    $sql = 'SELECT lots.id AS lot_id, lots.name AS lot_name, lots.winner_id, lots.img, lots.date_completion,
       bets.user_id, MAX(bets.price) AS price, bets.creation_time, users.contact, users.id AS user_id,
       lots.user_id AS lot_creator, categories.name AS cat_name
    FROM bets
    JOIN lots ON bets.lot_id = lots.id
    JOIN categories ON lots.category_id = categories.id
    JOIN users ON bets.user_id = users.id
    GROUP BY bets.lot_id, bets.user_id, lots.winner_id, bets.creation_time
    HAVING bets.user_id =' . $user_id . ' ORDER BY bets.creation_time DESC ';

    $result = mysqli_query($link, $sql);

    if (!$result) {
        print("Ошибка MYSQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Фукнция получает лоты по категориям
 * @param mysqli $link Соединение с БД
 * @param string $category_id Получает id категории
 * @param int $cur_page Получает текущую страницу
 * @param int $pagination_limit Получает лимит количества лотов
 * @return array Возвращает лоты по категориям
 */
function get_lot_by_category(mysqli $link, string $category_id, int $cur_page, int $pagination_limit): array
{
    $offset = $pagination_limit * ($cur_page - 1);

    $sql = 'SELECT lots.id, lots.name, lots.begin_price, lots.img, lots.date_completion,
       lots.category_id, lots.creation_time, categories.name AS cat_name
    FROM lots
    JOIN categories ON lots.category_id = categories.id
    WHERE lots.category_id =' . $category_id . ' AND lots.date_completion > NOW() ORDER BY lots.creation_time DESC
    LIMIT ' . $pagination_limit . ' OFFSET '  . $offset;

    $result = mysqli_query($link, $sql);

    if (!$result) {
        print("Ошибка MYSQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция получает количество лотов по категории
 * @param mysqli $link Соединение с БД
 * @param string $category_id
 * @return int Возвращает количество лотов по категории
 */
function get_count_lot_by_category(mysqli $link, string $category_id): int
{
    $sql = 'SELECT count(id) as count FROM lots WHERE category_id =' . $category_id . ' AND lots.date_completion > NOW()
    ORDER BY lots.creation_time DESC';

    $result = mysqli_query($link, $sql);

    $count_lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $count_lots = $count_lots[0]['count'];

    if (!$result) {
        print("Ошибка MYSQL: " . mysqli_error($link));
        exit();
    }

    return $count_lots;
}


/**
 * Функция скрывает блок с добавлением Ставки на лот
 * @param string $date_completion Получает дату завершения лота
 * @param string $cur_date Получает текущую дату
 * @param string $cur_user_id Получает id зарегистрированного пользователя
 * @param string $lot_creator Получает id пользователя, создавшего лот
 * @param string $last_bets_user Получает последнюю ставку сделанную пользователем
 * @return string|null Скрывает блок
 */
function hidden_bets_form(
    string $date_completion,
    string $cur_date,
    string $cur_user_id,
    string $lot_creator,
    string $last_bets_user
): ?string {
    $date_completion = date_create($date_completion);
    $cur_date = date_create($cur_date);

    if ($lot_creator === $cur_user_id || $date_completion <= $cur_date || $last_bets_user === $cur_user_id) {
        return true;
    } else {
        return false;
    }
}


/**
 * Функция получает список лотов без победителей
 * @param mysqli $link Соединение с БД
 * @return array Возвращает массив лотов без победителей
 */
function get_lots_whithout_winners(mysqli $link): array
{
    $sql = 'SELECT l.id AS lot_id, l.name AS lot_name, l.winner_id, l.date_completion
    FROM lots l
    WHERE winner_id IS NULL AND l.date_completion <= CURRENT_DATE()';

    $result = mysqli_query($link, $sql);

    if (!$result) {
        print("Ошибка MYSQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


/**
 * Функция получает последнюю ставку лота
 * @param mysqli $link Соединение с БД
 * @param int $lot_id Получает id лота
 * @return array|null Возвращает последнюю ставку конкретного лота
 */
function get_last_bets(mysqli $link, int $lot_id): ?array
{
    $sql = 'SELECT users.id AS user_id, users.name AS user_name, users.email, bets.price AS max_price,
    bets.lot_id AS lot_id, lots.name AS lot_name
    FROM bets
    JOIN lots ON bets.lot_id = lots.id
    JOIN users ON bets.user_id = users.id
    WHERE bets.lot_id =' . $lot_id . '
    ORDER BY bets.price DESC LIMIT 1';

    $result = mysqli_query($link, $sql);

    if (!$result) {
        print("Ошибка MYSQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_array($result, MYSQLI_ASSOC);
}


/**
 * Функция записывает победителя в лот
 * @param mysqli $link Соединение с БД
 * @param int $user_id Получает id пользователя
 * @param int $lot_id Получает id лота
 * @return bool Записывает пользователя в победители конкретного лота
 */
function add_winner_lot(mysqli $link, int $user_id, int $lot_id): bool
{
    $sql = 'UPDATE lots SET winner_id =' . $user_id . ' WHERE id =' . $lot_id;

    return mysqli_query($link, $sql);
}


/**
 * Функция получает контакты создателя лота
 * @param mysqli $link Соединение с БД
 * @param int $lot_id Передает id лота
 * @return array|null Возвращает контакты пользователя или ошибку
 */
function get_lot_creator_contacts(mysqli $link, int $lot_id): ?array
{
    $sql = 'SELECT users.contact FROM lots JOIN users ON lots.user_id = users.id WHERE lots.id =' . $lot_id;
    $result = mysqli_query($link, $sql);

    if (!$result) {
        print("Ошибка MYSQL: " . mysqli_error($link));
        exit();
    }

    return mysqli_fetch_array($result, MYSQLI_ASSOC);
}
