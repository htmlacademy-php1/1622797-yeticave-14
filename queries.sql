/* Используем базу */
USE yeti;

/* Добавляем список категорий */
INSERT INTO categories(name, symbol_code)
VALUES 
('Доски и лыжи', 'boards')
('Крепления', 'attachment')
('Ботинки', 'boots')
('Одежда', 'clothing')
('Инструменты', 'tools')
('Разное', 'other');

/* Добавляем пользователей */
INSERT INTO users(email, user_name, password, contact)
VALUES
('test@test.ru', 'Vasya', '123456','89001001010')
('test1@test.ru', 'Петрович', 'qwerty','89012002020')
('test2@test,ru', 'Thomasmanz', 'asdfgh','89023003030');

/* Добавляем лоты */
INSERT INTO lots(name, description, img, begin_price, date_completion, bid_step, user_id, winner_id, category_id)
VALUES
('2014 Rossignol District Snowboard', 'Офигенный сноуборд', 'img/lot-1.jpg', '10999', '2022-04-16', '1000', '1', '2', '1')
('DC Ply Mens 2016/2017 Snowboard', 'Самый классный сноуборд для профессионалов', 'img/lot-2.jpg', '159999', '2022-04-17', '1000', '2', '2', '1')
('Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления для ботинок', 'img/lot-3.jpg', '8000', '2022-04-19', '500', '3', '1', '2')
('Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки для сноуборда', 'img/lot-4.jpg', '10999', '2022-04-18', '1000', '1', '2', '3')
('Куртка для сноуборда DC Mutiny Charocal', 'Куртка для зимних покатушек', 'img/lot-5.jpg', '7500', '2022-04-20', '500', '2', '3', '4')
('Маска Oakley Canopy', 'Маска солнцезащитная', 'img/lot-6.jpg', '5400', '2022-04-21', '500', '1', '2', '6');

/* Добавляем несколько ставок */
INSERT INTO bets(price, user_id, lot_id)
VALUES
('15000', '1', '1')
('210000', '2', '1')
('7000', '3', '6');

/* Получаем все категории */
SELECT name FROM categories;

/* Получаем самые новые, открытые лоты. Каждый лот включает название, стартовую цену, ссылку на изображение, цену, название категории */
SELECT l.name, l.begin_price, l.img, MAX(b.price), c.name
FROM lots AS l
JOIN bets AS b ON l.id = b.lot_id
JOIN categories as c ON c.id = l.category_id
WHERE l.date_completion > NOW()
GROUP BY b.lot_id
ORDER BY l.creation_time DESC;

/* Показываем лот по его ID. Получаем также название категории, к которой принадлежит лот */
SELECT l.id FROM lots l LEFT JOIN categories c ON  l.category_id = c.id WHERE l.id = 1;

/* Обновляем название лота по его идентификатору */
UPDATE lots SET name_lots = 'Маска DC Shoe' WHERE id = 6;

/* Получаем список ставок для лота по его идентификатору с сортировкой по дате */
SELECT * FROM bets WHERE lot_id = 1 ORDER BY creation_time;