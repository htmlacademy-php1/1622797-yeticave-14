<?php

/**
 * Функция проверяет заполненость поля Ставка
 * @param array $form_bets Принимает данные из формы
 * @param array $lot_data Проверяет информацию о лоте в БД
 * @return array Возвращает массив с ошибками, если поле было заполнено неверно
 */
function validate_form_bets(array $form_bets, array $lot_data): array
{
    $errors = [
        'price' => validate_bets($form_bets['price'], $lot_data)
    ];
    return array_filter($errors);
}


/**
 * Функция проводит валидацию поля Ставка
 * @param int $price Число введеное пользователем
 * @param array $lot_data Проверяет информацию о лоте в БД
 * @return string|null Возвращает ошибку, если поля были заполнены неверно
 */
function validate_bets($price, array $lot_data): ?string
{
    if ($price === '') {
        return "Введите ставку";
    }
    if ($price <= 0) {
        return "Значение введено некорректно";
    }
    if (strlen($price) > 7) {
        return "Значение должно быть не более 7 символов";
    }

    $cur_price = $lot_data['max_price'] ?? $lot_data['begin_price'];
    $min_bet = $cur_price + $lot_data['bid_step'];
    if ($price < $min_bet) {
        return "Значение должно быть больше или равно " . $min_bet;
    }
    return null;
}
