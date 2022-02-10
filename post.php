<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $title string
 * @var $user_name mysqli
 * @var $is_auth boolean
 */
require_once('bootstrap.php');

$post_types = get_post_types($con);

$post_id = '';
$content = '';
$post = null;

if (isset($_GET['id'])) {
    $post_id = mysqli_real_escape_string($con, $_GET['id']);

    $post_data = get_post($con, $post_id);

    if (!$post_data) {
        http_response_code(404);
        $content = include_template('404.php');
    } else {
        $author_id = $post_data['author_id'];

        $post = format_post_data($post_data);
        $author_info = get_author_info($con, $author_id);
        $subscribers_count = get_subscribers_count($con, $author_id);
        $posts_count = get_posts_count($con, $author_id);

        $author_info['subscribers_count'] = $subscribers_count;
        $author_info['posts_count'] = $posts_count;

        $content = include_template('post-detail/layout.php', compact("post", "author_info"));
    }
}

$page = include_template("layout.php", compact("content", "title", "is_auth", "user_name"));

print($page);
?>

