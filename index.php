<?php

require_once('helpers.php');
require_once('queries.php');

date_default_timezone_set('Europe/Moscow');
//В сценарии главной страницы выполните подключение к MySQL.
$con = mysqli_connect("localhost", "root", "", "readme");
if (!$con) {
    print("Не удалось подключиться к бд" . mysqli_connect_error());
}
mysqli_set_charset($con, "utf8");

$queries = get_queries();
//Отправьте SQL-запрос для получения типов контента
$post_types = $queries['postTypes']($con);
//Отправьте SQL-запрос для получения списка постов, объединённых с пользователями и отсортированный по популярности.
$posts = $queries['posts']($con);

$current_time = date_create()->getTimestamp();

$is_auth = rand(0, 1);
$user_name = 'Aндрей';
$title = 'readme: популярное';

$content = include_template('main.php', compact("posts", "current_time", "post_types"));
$page = include_template("layout.php", compact("content", "title", "is_auth", "user_name"));

print($page);
?>

