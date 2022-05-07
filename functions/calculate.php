<?php

/**
 * Функция для посчета времени до закрытия лота
 * 
 * @param string $closetime Время закрытия лота
 * @param string $curtime Настоящее время
 * @return array Возвращает время в формате ЧЧ:ММ до закрытия лота
 */
function get_dt_range(string $closetime, string $curtime): array
{
    $dt_diff = strtotime($closetime) - strtotime($curtime);
    if ($dt_diff < 0) {
        $interval = ['hour' => 0, 'minute' => 0];
        return $interval;
    }
    $hours = floor($dt_diff / 3600);
    $minuts = floor($dt_diff % 3600 / 60);
    $interval = ['hour' => $hours, 'minute' => $minuts];
    return $interval;
}
