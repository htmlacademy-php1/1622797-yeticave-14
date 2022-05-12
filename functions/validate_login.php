<?php

/**
 * Функция проверяет все данные введенные в форму входа в учетную запись
 *
 * @param mysqli link Соединение с БД
 * @param array login_form Массив с данными из формы
 *
 * @return array Возвращает массив с данными из формы
 */
function validate_login_form(mysqli $link, array $login_form): array
{
    $errors = [
        'email' => validate_login_email($login_form['email']),
        'password' => validate_password($link, $login_form['email'], $login_form['password'])
    ];

    return array_filter($errors);
}


/**
 * Функция проверяет на правильное заполнение поля с e-mail
 *
 * @param string email Данные из поля с e-mail
 *
 * @return string Возвращает ошибки, если форма заполнена неверно
 */
function validate_login_email(string $email): ?string
{
    if ($email === '') {
        return "Поле необходимо заполнить";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Некорректно введен e-mail";
    }

    return null;
}


/**
 * Функция проверяет на правильное заполнение поля с паролем
 *
 * @param mysqli link Соединение с БД
 * @param string password Поле с паролем для проверки на совпадение с данными из БД
 *
 * @return string Возвращает ошибку, если пароль не совпадает
 */
function validate_password(mysqli $link, string $email, string $password): ?string
{
    if ($password === '') {
        return "Поле необходимо заполнить";
    }
    $user = get_user_by_email($link, $email);
    if ($user === null) {
        return "Неверный e-mail или пароль";
    }
    if (!password_verify($password, $user['password'])) {
        return "Введен неверный пароль";
    }

    return null;
}
