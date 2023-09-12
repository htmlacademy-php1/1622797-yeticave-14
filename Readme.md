# Личный проект «YetiCave»

<p align="left">
<img src="https://img.shields.io/badge/php-%5E8.0-blue">
<img src="https://img.shields.io/badge/mysql-latest-orange">
</p>

---

_Не удаляйте и не обращайте внимание на файлы:_<br>
_`.editorconfig`, `.gitattributes`, `.gitignore`._

---

## О проекте

Интернет-аукцион «YetiCave» — это простой сервис, позволяющий пользователям продать свои личные вещи по максимально выгодной для них цене — на основе аукциона.

Пользователь, предложивший максимальную цену получает вещь по этой стоимости.

## Основные сценарии использования сайта:

- Регистрация на сайте;
- Авторизация;
- Просмотр списка активных лотов;
- Публикация лота;
- Добавление ставок;
- Поиск лотов по категориям и названиям;
- Валидация всех форм;
- Возврат страницы с ошибкой 404, если пользователь пытается открыть страницу с несуществующим проектом;
- Определение победителя на основе максимальной ставки и отправка ему e-mail уведомления

## Обзор проекта

[![Видео](https://up.htmlacademy.ru/assets/intensives/php/14/projects/yeticave/image.jpg)](https://www.youtube.com/watch?v=eP-vjex0Wfw)

## Начало работы

Чтобы развернуть проект локально или на хостинге, выполните последовательно несколько действий:

1. Клонируйте репозиторий:

```bash
git clone git@github.com:kiipod/1622797-yeticave-14.git yeticave
```

2. Перейдите в директорию проекта:

```bash
cd yeticave
```

3. Установите зависимости, выполнив команду:

```bash
composer install
```

4. Создайте базу данных для проекта, используя схему из файла `schema.sql`:

```sql
CREATE DATABASE yeti CHARACTER SET utf8 COLLATE utf8_general_ci;

USE yeti;

CREATE TABLE categories  (
    id int AUTO_INCREMENT PRIMARY KEY,
    name varchar(64) NOT NULL,
    code varchar(64) NOT NULL UNIQUE
);

CREATE TABLE lots (
    id int AUTO_INCREMENT PRIMARY KEY,
    creation_time datetime NOT NULL,
    name varchar(255) NOT NULL,
    description TEXT NOT NULL,
    img varchar(255),
    begin_price int NOT NULL,
    date_completion date,
    bid_step int NOT NULL,
    user_id int NOT NULL REFERENCES users(id),
    winner_id int REFERENCES users(id),
    category_id int NOT NULL REFERENCES categories(id)
);

CREATE TABLE bets (
    id int AUTO_INCREMENT PRIMARY KEY,
    creation_time datetime NOT NULL,
    price int NOT NULL,
    user_id int NOT NULL REFERENCES users(id),
    lot_id int NOT NULL REFERENCES lots(id)
);

CREATE TABLE users (
    id int AUTO_INCREMENT PRIMARY KEY,
    creation_time datetime NOT NULL,
    email varchar(320) NOT NULL UNIQUE,
    name varchar(64) NOT NULL,
    password varchar(64) NOT NULL,
    contact TEXT NOT NULL
);

CREATE FULLTEXT INDEX lots_ft_search ON lots(name, description);
```

4. Заполните базу данных тестовыми данными из файла `queries.sql`:

```sql
/* Используем базу */
USE yeti;

/* Добавляем список категорий */
INSERT INTO categories(name, code)
VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное', 'other');

/* Добавляем пользователей */
INSERT INTO users(creation_time, name, email, password, contact)
VALUES
  ('2022-04-07', 'Vasya', 'vasya@yandex.ru', '123456','89001001010'),
  ('2022-04-03', 'Петрович', 'petrovich@gmail.com', 'qwerty','89012002020'),
  ('2022-03-28', 'Thomasmanz', 'thomasmanz@mail.ru', 'asdfgh','89023003030');

/* Добавляем лоты */
INSERT INTO lots(name, creation_time, description, img, begin_price, date_completion, bid_step, user_id, winner_id, category_id)
VALUES
  ('2014 Rossignol District Snowboard', '2022-04-04', 'Офигенный сноуборд', 'img/lot-1.jpg', '10999', '2022-05-15', '1000', '1', '2', '1'),
  ('DC Ply Mens 2016/2017 Snowboard', '2022-04-04', 'Самый классный сноуборд для профессионалов', 'img/lot-2.jpg', '159999', '2022-05-12', '1000', '2', '2', '1'),
  ('Крепления Union Contact Pro 2015 года размер L/XL', '2022-04-05', 'Крепления для ботинок', 'img/lot-3.jpg', '8000', '2022-05-10', '500', '3', '1', '2'),
  ('Ботинки для сноуборда DC Mutiny Charocal', '2022-04-06', 'Ботинки для сноуборда', 'img/lot-4.jpg', '10999', '2022-05-17', '1000', '1', '2', '3'),
  ('Куртка для сноуборда DC Mutiny Charocal', '2022-04-07', 'Куртка для зимних покатушек', 'img/lot-5.jpg', '7500', '2022-05-14', '500', '2', '3', '4'),
  ('Маска Oakley Canopy', '2022-04-02', 'Маска солнцезащитная', 'img/lot-6.jpg', '5400', '2022-05-15', '500', '1', '2', '6');

/* Добавляем несколько ставок */
INSERT INTO bets(creation_time, price, user_id, lot_id)
VALUES
  (NOW(), '15000', '1', '1'),
  (NOW(), '210000', '2', '2'),
  (NOW(), '7000', '3', '6');
```

6. Настройте подключение к базе данных, создав в корне проекта файл `config.php` и указав параметры своего окружения. Например, это может выглядеть так:

```php
<?php

return [
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => 'root',
    'name' => 'yeti'
],

    'pagination_limit' => 9,

    'base_url' => 'http://test.ru'
];
```

## Техническое задание

[Посмотреть техническое задание проекта](tz.md)
