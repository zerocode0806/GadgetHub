<?php
/** Bootstrap ringan bergaya Laravel untuk aplikasi PHP procedural yang sudah ada. */
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__, 2));
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', APP_ROOT . '/public');
}
chdir(APP_ROOT);
