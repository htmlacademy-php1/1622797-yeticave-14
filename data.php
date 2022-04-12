<?php
$is_auth = rand(0, 1);
$user_name = 'Кирилл';
$title = 'YetiCave - Интернет-аукцион сноубордического и горнолыжного снаряжения';
$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
$lots = [
                [
                'name' => '2014 Rossignol District Snowboard',
                'categories' => 'Доски и лыжи',
                'price' => '10999',
                'url' => 'img/lot-1.jpg',
                'closetime' => '2022-04-13'
                ],
                [
                'name' => 'DC Ply Mens 2016/2017 Snowboard',
                'categories' => 'Доски и лыжи',
                'price' => '159999',
                'url' => 'img/lot-2.jpg',
                'closetime' => '2022-04-13'
                ],
                [
                'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
                'categories' => 'Крепления',
                'price' => '8000',
                'url' => 'img/lot-3.jpg',
                'closetime' => '2022-04-13'
                ],
                [
                'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
                'categories' => 'Ботинки',
                'price' => '10999',
                'url' => 'img/lot-4.jpg',
                'closetime' => '2022-04-14'
                ],
                [
                'name' => 'Куртка для сноуборда DC Mutiny Charocal',
                'categories' => 'Одежда',
                'price' => '7500',
                'url' => 'img/lot-5.jpg',
                'closetime' => '2022-04-15'
                 ],
                 [
                'name' => 'Маска Oakley Canopy',
                'categories' => 'Разное',
                'price' => '5400',
                'url' => 'img/lot-6.jpg',
                'closetime' => '2022-04-15'
                ],
            ];
            
function lotcost(int $price): string {
    if ($price < 1000) {
        return ceil($price) . '₽';
    } else {
        return number_format($price, 0, null, ' ') . '₽';
    }
}
?>