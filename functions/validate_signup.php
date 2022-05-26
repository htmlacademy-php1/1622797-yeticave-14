<?php

/**
 * Функция проверяет все данные введенные в форму регистрации
 * @param mysqli link Соединение с БД
 * @param array signup_form Массив с данными из полей формы
 * @return array Возвращает массив с данными из формы
 */
function validate_signup_form(mysqli $link, array $signup_form): array
{
    $errors = [
        'email' => validate_signup_email($link, $signup_form['email']),
        'password' => validate_signup_password($signup_form['password']),
        'first_name' => validate_name($signup_form['first_name']),
        'contact' => validate_contact($signup_form['contact'])
    ];

    return array_filter($errors);
}


/**
 * Функция проверяет e-mail на корректность и уникальность
 * @param mysqli link Соединение с БД
 * @param string email Переменная с e-mail
 * @return string|null Вовзращает ошибки если заполнено неверно или e-mail не уникален
 */
function validate_signup_email(mysqli $link, string $email): ?string
{
    if ($email === '') {
        return "Поле необходимо заполнить";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Некорректно введен e-mail";
    }
    if (get_user_by_email($link, $email)) {
        return "E-mail используется другим пользователем";
    }
    if (mb_strlen($email) > 64) {
        return "Длина не должна превышать 64 символов";
    }

    return null;
}


/**
 * Функция проверяет на заполнение поля Контакты
 * @param string value Значение полей
 * @return string|null Возвращает ошибку в случае незаполненного поля
 */
function validate_contact(string $value): ?string
{
    if ($value === '') {
        return "Поле необходимо заполнить";
    }
    if (mb_strlen($value) > 122) {
        return "Длина не должна превышать 122 символов";
    }

    return null;
}


/**
 * Функция проверяет на правильное заполнение поля с паролем
 * @param string $password
 * @return string|null Возвращает ошибку, если пароль не совпадает
 */
function validate_signup_password(string $password): ?string
{
    if ($password === '') {
        return "Поле необходимо заполнить";
    }
    if (mb_strlen($password) > 64) {
        return "Длина не должна превышать 64 символов";
    }

    return null;
}
