<?php

$config = require_once __DIR__ .'/config.php';

$link = mysqli_connect($config['db']['host'], $config['db']['user'], $config['db']['password'], $config['db']['database']);
mysqli_set_charset($link, "utf8");

if(!$link) {
    $error = mysqli_connect_error();
    print("Ошибка MySQL: " . $error);
}