<?php
/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 * @var $current_url string
 */

require_once('bootstrap.php');
$title = 'readme: моя лента';
$current_post_type = $_GET['type'] ?? null;

if (!$is_auth) {
    header('Location: /index.php');
}

$post_types = get_post_types($con);
$posts_data = get_posts_by_subscription($con, [
    'type' => $current_post_type,
    'current_user_id' => $_SESSION['user']['id'],
]);

if (isset($current_post_type)) {
    $current_post_type = explode('-', $current_post_type)[1];
}

$posts = array_map(function ($post_data) {
    $post = format_post_data($post_data);
    $template = get_post_template_by_type($post_data['type']);

    $post['template'] = include_template(
        $template,
        [
            'title' => $post['title'],
            'content' => $post['content'],
            'id' => $post['id']
        ]
    );
    return $post;
}, $posts_data);


$content = include_template(
    'feed.php',
    compact(
        'posts',
        'current_time',
        'post_types',
        'current_post_type'
    )
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name', 'current_url')
);

print($page);
