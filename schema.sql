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
