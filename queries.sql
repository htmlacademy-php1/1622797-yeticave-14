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
INSERT INTO users(creation_time, username, password, contact)
VALUES
('2022-04-07', 'Vasya', '123456','89001001010'),
('2022-04-03', 'Петрович', 'qwerty','89012002020'),
('2022-03-28', 'Thomasmanz', 'asdfgh','89023003030');

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

/* Получаем все категории */
SELECT name FROM categories;

/* Получаем самые новые, открытые лоты. Каждый лот включает название, стартовую цену, ссылку на изображение, цену, название категории */
SELECT l.name, l.begin_price, l.img, MAX(b.price), c.name
FROM lots l
JOIN bets b ON l.id = b.lot_id
JOIN categories c ON c.id = l.category_id
WHERE l.creation_time < NOW()
GROUP BY b.lot_id
ORDER BY l.creation_time DESC;

/* Показываем лот по его ID. Получаем также название категории, к которой принадлежит лот */
SELECT l.id, c.name FROM lots l LEFT JOIN categories c ON l.category_id = c.id WHERE l.id = 1;

/* Обновляем название лота по его идентификатору */
UPDATE lots SET name = 'Маска DC Shoe' WHERE id = 6;

/* Получаем список ставок для лота по его идентификатору с сортировкой по дате */
SELECT * FROM bets WHERE lot_id = 1 ORDER BY creation_time;