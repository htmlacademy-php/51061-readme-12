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

$current_post_type='';

if (isset($_GET['type'])) {
    $current_post_type = mysqli_real_escape_string($con,$_GET['type']);
}
//Отправьте SQL-запрос для получения типов контента
$post_types = get_post_types($con);


//Отправьте SQL-запрос для получения списка постов, объединённых с пользователями и отсортированный по популярности.
$postsData = get_posts($con,$current_post_type);

//Используйте эти данные для показа списка постов и списка типов контента на главной странице.
//-- списка постов - преобразуем вывод постов для отображения страницы
$posts= array_map(function ($value) {
    $content = $value['text'];

    if ($value['image_url']) {
        $content = $value['image_url'];
    }
    if ($value['video_url']) {
        $content = $value['video_url'];
    }
    if ($value['url']) {
        $content = $value['url'];
    }
    if ($value['author_quote']) {
        $content = $value['author_quote'];
    }
    return [
        'title' => $value['title'],
        "type" => $value['type'],
        "content" => $content,
        "user_name" => $value['user_name'],
        "avatar" => $value['avatar']
    ];
}, $postsData);

$current_time = date_create()->getTimestamp();

$is_auth = rand(0, 1);
$user_name = 'Aндрей';
$title = 'readme: популярное';

$content = include_template('main.php', compact("posts", "current_time", "post_types","current_post_type"));
$page = include_template("layout.php", compact("content", "title", "is_auth", "user_name"));

print($page);
?>

