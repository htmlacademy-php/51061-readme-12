<?php

session_start();

require_once('helpers/helpers.php');
require_once('queries.php');

date_default_timezone_set('Europe/Moscow');
//В сценарии главной страницы выполните подключение к MySQL.
$con = mysqli_connect('localhost', 'root', '', 'readme');
if (!$con) {
    print('Не удалось подключиться к бд' . mysqli_connect_error());
}
mysqli_set_charset($con, 'utf8');

$is_auth = false;
$user_name = 'Аноним';

if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['login'];
}
$current_time = date_create()->getTimestamp();


