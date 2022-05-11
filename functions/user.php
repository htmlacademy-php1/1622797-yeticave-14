<?php

/**
 * Функция получает id пользователя по сессии
 *
 * @return string Находит совпадение или не возвращает ничего
 */
function get_user_id_session(): ?string
{
    return $_SESSION['user_id'] ?? null;
}


/**
 * Функция записывает в массив SESSION id и имя пользователя
 *
 * @param mysqli link Соединение с БД
 * @param array login_form Массив с данными из формы входа
 *
 * @return bool Если пользователь найден, то в массив SESSION записывается id и имя пользователя или выдает ошибку
 */
function authentication(mysqli $link, array $login_form): bool
{
    $user = get_user_by_email($link, $login_form['email']);
    if ($user === null) {
        return false;
    }
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];

    return true;
}


/**
 * Функция проверяет массив SESSION на наличие в нем name
 *
 * @param array session Определяет массив SESSION
 *
 * @return string Возвращает имя пользователя или ничего
 */
function check_session_name(): ?string
{
    return $_SESSION['name'] ?? null;
}
