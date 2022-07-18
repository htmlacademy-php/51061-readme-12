<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $current_url string
 * @var $user_name string
 * @var $is_auth bool
 */
require_once('bootstrap.php');
$title = 'readme: популярное';
$current_post_type = null;

function add_type_param($url)
{
    if (!isset($_GET['type'])) {
        return $url;
    }
    return $url . '&type=' . $_GET['type'];
}

if (!$is_auth) {
    header('Location: /index.php');
}

if (isset($_GET['type'])) {
    $current_post_type = mysqli_real_escape_string($con, $_GET['type']);
}

$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = $_GET['limit'] ?? 9;
$max_page = intval(ceil(get_posts_count($con, $current_post_type) / $limit));
$is_last_page = $current_page === $max_page;
$is_first_page = $current_page === 1;

$next_page_url = '/popular.php?page=' . ($current_page + 1);
$next_page_url = add_type_param($next_page_url);
$prev_page_url = '/popular.php?page=' . ($current_page - 1);
$prev_page_url = add_type_param($current_post_type);

$post_types = get_post_types($con);
$posts_data = get_posts($con, [
    'type' => $current_post_type,
    'page' => $current_page,
    'limit' => $limit
]);

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

if (!empty($current_post_type)) {
    $current_post_type = explode('-', $current_post_type)[1];
}

$content = include_template(
    'popular.php',
    compact(
        'posts',
        'current_time',
        'post_types',
        'current_post_type',
        'next_page_url',
        'prev_page_url',
        'is_last_page',
        'is_first_page'
    )
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name', 'current_url')
);

print($page);


