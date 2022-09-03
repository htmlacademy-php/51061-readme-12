<?php
/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 * @var $send_email - функция отправки email
 */

require_once('bootstrap.php');

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    header('Location: /feed.php');
}
$post = get_post($con, $post_id);
if (!$post['id']) {
    header('Location: /feed.php');
}

$post['repost'] = true;
$post['original_author_id'] = $post['author_id'];
$post['original_post_id'] = $post['id'];
$post['author_id'] = $_SESSION['user']['id'];


$new_post_id = save_post($con, $post);

if ($new_post_id) {
    header('Location: /profile.php');
} else {
    header('Location: /feed.php');
}




