<?php
/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 * @var $current_url string
 */

require_once('bootstrap.php');

$title = 'readme: результаты поиска';

if (!$is_auth) {
    header('Location: /index.php');
}

$search_text = $_GET['search'] ?? null;

$content = '';

if ($search_text) {
    $posts_data = search_posts($con, $search_text);
    if (empty($posts_data)) {
        $content = include_template(
            'no-results.php',
            ['search_text' => $search_text]
        );
    } else {
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
            'search.php',
            compact('search_text', 'posts', 'current_time')
        );
    }
}

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name')
);

print($page);
