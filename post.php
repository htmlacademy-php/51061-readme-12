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

$is_auth = rand(0, 1);
$user_name = 'Aндрей';
$title = 'readme: популярное';

$current_time = date_create()->getTimestamp();
$post_types = get_post_types($con);

$post_id='';
$post=null;

if (isset($_GET['id'])) {
    $post_id = mysqli_real_escape_string($con,$_GET['id']);

    $postData = get_post($con,$post_id);

    if (!$postData) {
        http_response_code(404);
        $content = include_template('404.php');
    } else {
        $author_id = $postData['author_id'];

        $post = format_post_data($postData);
        $author_info = get_author_info($con,$author_id);
        $subscribers_count = get_subscribers_count($con,$author_id);
        $author_info['subscribers_count'] = $subscribers_count;
        $posts_count = get_posts_count($con,$author_id);
        $author_info['posts_count'] = $posts_count;

        var_dump($author_info['email'] . $subscribers_count . $posts_count);

        $content = include_template('post-detail/layout.php', compact("post",'author_info'));
    }
}

$page = include_template("layout.php", compact("content", "title", "is_auth", "user_name"));

print($page);
?>

