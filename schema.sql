CREATE DATABASE yeti CHARACTER SET utf8 COLLATE utf8_general_ci;
USE yeti;

CREATE TABLE categories  (
  id int AUTO_INCREMENT PRIMARY KEY,
  name_cat varchar(64) NOT NULL,
  symbol_code varchar(64) NOT NULL UNIQUE
);

CREATE TABLE lots (
   id int AUTO_INCREMENT PRIMARY KEY,
   creation_time datetime NOT NULL,
   name_lots varchar(122) NOT NULL,
   description varchar(255),
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
   user_id int REFERENCES users(id),
   lot_id int REFERENCES lots(id)
);

CREATE TABLE users (
  id int AUTO_INCREMENT PRIMARY KEY,
  creation_time datetime NOT NULL,
  email varchar(64) NOT NULL UNIQUE,
  user_name varchar(64) NOT NULL,
  password varchar(64) NOT NULL,
  contact varchar(122) NOT NULL,
  lot_id int NOT NULL REFERENCES lots(id),
  bet_id int NOT NULL REFERENCES bets(id)
);