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
$current_post_type = '';

if (!$is_auth) {
    header('Location: /index.php');
}

if (isset($_GET['type'])) {
    $current_post_type = mysqli_real_escape_string($con, $_GET['type']);
}


//Отправьте SQL-запрос для получения типов контента
$post_types = get_post_types($con);

//Отправьте SQL-запрос для получения списка постов, объединённых с пользователями и отсортированный по популярности.
$posts_data = get_posts($con, $current_post_type);

//Используйте эти данные для показа списка постов и списка типов контента на главной странице.
//-- списка постов - преобразуем вывод постов для отображения страницы
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
        'current_post_type'
    )
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name', 'current_url')
);

print($page);


