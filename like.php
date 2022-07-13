<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $current_url string
 * @var $user_name string
 * @var $is_auth bool
 */
require_once('bootstrap.php');

if (!$is_auth) {
    header('Location: /index.php');
}

if (isset($_GET['post_id'])) {
    $user_id = $_SESSION['user']['id'];
    $post_id = $_GET['post_id'];

    $has_post = get_post($con, $post_id);

    if ($has_post) {
        $id = like_post($con, $user_id, $post_id);
    }
}

header('Location:' . $_SERVER[HTTP_REFERER]);
