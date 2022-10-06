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
$order = null;
$sort = null;

function add_param($url)
{
    $new_url = $url;
    if (isset($_GET['type'])) {
        $new_url = $new_url . '&type=' . $_GET['type'];
    }
    if (isset($_GET['order']) && isset($_GET['sort'])) {
        $new_url = $new_url . '&sort=' . $_GET['sort'] . '&order=' . $_GET['order'];
    }
    return $new_url;
}

function create_new_sort_link($url, $type)
{
    $new_url = $url;
    $prev_type = '';
    if (isset($_GET['sort'])) {
        $prev_type = $_GET['sort'];
        $new_url = preg_replace(
            '/sort=(views|likes_count|created_at)/',
            'sort=' . $type,
            $new_url
        );
    }
    if (isset($_GET['order'])) {
        if ($prev_type === $type) {
            $new_order = $_GET['order'] === 'asc' ? 'desc' : 'asc';
        } else {
            $new_order = 'asc';
        }

        $new_url = preg_replace(
            '/order=(asc|desc)/',
            'order=' . $new_order,
            $new_url
        );
    }
    return $new_url;
}

if (!$is_auth) {
    header('Location: /index.php');
}

if (isset($_GET['type'])) {
    $current_post_type = mysqli_real_escape_string($con, $_GET['type']);
}
if (isset($_GET['order'])) {
    $order = $_GET['order'];
}
if (isset($_GET['sort'])) {
    $sort = $_GET['sort'];
}

$has_url_params = strpos($current_url, '?') != false;

$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = $_GET['limit'] ?? 9;
$max_page = intval(ceil(get_posts_count($con, $current_post_type) / $limit));
$is_last_page = $current_page === $max_page;
$is_first_page = $current_page === 1;
$next_page_url = '/popular.php?page=' . ($current_page + 1);
$next_page_url = add_param($next_page_url);
$prev_page_url = '/popular.php?page=' . ($current_page - 1);
$prev_page_url = add_param($prev_page_url);

$new_sort_link_views = create_new_sort_link($current_url, 'views');
$new_sort_link_likes = create_new_sort_link($current_url, 'likes_count');
$new_sort_link_created_at = create_new_sort_link($current_url, 'created_at');

$post_types = get_post_types($con);
$posts_data = get_posts($con, [
    'type' => $current_post_type,
    'page' => $current_page,
    'order' => $order,
    'sort' => $sort,
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
            'id' => $post['id'],
            'author' => $post['author_quote'],
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
        'is_first_page',
        'has_url_params',
        'current_url',
        'order',
        'sort',
        'new_sort_link_views',
        'new_sort_link_likes',
        'new_sort_link_created_at',
    )
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name', 'current_url')
);

print($page);


