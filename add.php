<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name mysqli
 * @var $is_auth boolean
 */
require_once('bootstrap.php');
$title='readme: добавление публикации';

$current_post_type = '';
$add_post=true;

if (isset($_GET['type'])) {
    $current_post_type = mysqli_real_escape_string($con, $_GET['type']);
}

//Отправьте SQL-запрос для получения типов контента
$post_types = get_post_types($con);

//Отправьте SQL-запрос для получения списка постов, объединённых с пользователями и отсортированный по популярности.
$postsData = get_posts($con, $current_post_type);

$content = include_template('adding-post.php', compact( "current_time", "post_types"));
$page = include_template("layout.php", compact("content", "title", "is_auth", "user_name",'add_post'));

print($page);
?>

