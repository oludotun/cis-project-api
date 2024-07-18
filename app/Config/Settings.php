<?php

namespace App\Config;

class Settings
{
    public static function get(string $key = '', $key2 = '') {
        $config = include __DIR__ . '/../../env.php';
        return empty($key)? $config : (empty($key2)? $config[$key] : $config[$key][$key2]);
    }

}