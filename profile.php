<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $current_url string
 * @var $user_name string
 * @var $is_auth bool
 */
require_once('bootstrap.php');

$title = 'readme: профиль';

if (!$is_auth) {
    header('Location: /index.php');
}

$user = '';
$posts = [];
$has_subscription = false;
$get_param_user_id = $_GET['id'] ?? null;
$content_type = $_GET['type'] ?? 'posts';

if ($get_param_user_id) {
    $user = get_user_by_id($con, $get_param_user_id);

    $has_subscription = check_subscribe_to_user(
        $con,
        $_SESSION['user']['id'],
        $get_param_user_id
    );
} else {
    $user = $_SESSION['user'];
}

$total_subscriptions = get_user_subscriptions_count($con, $user['id']);
$passed_time = get_passed_time_title($user['created_at']);
$total_posts = get_posts_count_by_author($con, $user['id']);

$data = [];

switch ($content_type) {
    case 'posts':
        $posts = get_user_posts($con, $user['id']);
        foreach ($posts as $post_data) {
            $post = format_post_data($post_data);
            $template = get_post_template_by_type($post['type']);

            $post['template'] = include_template(
                $template,
                [
                    'title' => $post['title'],
                    'content' => $post['content'],
                    'id' => $post['id']
                ]
            );
            $post['hashtags'] = get_post_hashtags($con, $post['id']);
            $data[] = $post;
        }
        break;
    case 'likes':
        $data = get_user_posts_with_likes($con, $user['id']);
        break;
    case 'subscriptions':
        $data = get_user_subscribers_full_info($con, $user['id']);
        break;
}


$content = include_template(
    'profile.php',
    compact(
        'user',
        'passed_time',
        'get_param_user_id',
        'total_subscriptions',
        'total_posts',
        'has_subscription',
        'data',
        'current_time',
        'content_type'
    )
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name', 'current_url')
);

print($page);
