<?php

/**
 * Функция проверяет все данные введенные в форму регистрации
 *
 * @param mysqli link Соединение с БД
 * @param array signup_form Массив с данными из полей формы
 *
 * @return array Возвращает массив с данными из формы
 */
function validate_signup_form(mysqli $link, array $signup_form): array
{
    $errors = [
        'email' => validate_email($link, $signup_form['email']),
        'password' => validate_form_field($signup_form['password']),
        'first_name' => validate_form_field($signup_form['first_name']),
        'contact' => validate_form_field($signup_form['contact'])
    ];

    return array_filter($errors);
}


/**
 * Функция проверяет e-mail на корректность и уникальность
 *
 * @param mysqli link Соединение с БД
 * @param string email Переменная с e-mail
 *
 * @return string Вовзращает ошибки если заполнено неверно или e-mail не уникален
 */
function validate_email(mysqli $link, string $email): ?string
{
    if ($email === '') {
        return "Поле необходимо заполнить";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Некорректно введен e-mail";
    }
    if (get_user_email($link, $email)) {
        return "E-mail используется другим пользователем";
    }

    return null;
}


/**
 * Функция проверяет на заполнение поля формы регистрации
 *
 * @param string value Значение полей
 *
 * @return string Возвращает ошибку в случае незаполненного поля
 */
function validate_form_field(string $value): ?string
{
    if ($value === '') {
        return "Поле необходимо заполнить";
    }

    return null;
}
