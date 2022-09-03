<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 */
require_once('bootstrap.php');
require_once('helpers/validate-functions.php');

$title = '';
$post_types = get_post_types($con);

$post_id = '';
$content = '';
$post = null;

if (!$is_auth) {
    header('Location: /index.php');
}


if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    $post_data = get_post($con, $post_id);
    increment_post_view($con, $post_id);
    $post_comments = get_post_comments($con, $post_id);

    $has_subscription = check_subscribe_to_user(
        $con,
        $post_data['author_id'],
        $_SESSION['user']['id']
    );

    if (!$post_data) {
        http_response_code(404);
        $title = 'readme: данные не найдены';
        $content = include_template('404.php');
    } else {
        $comment_error = '';
        if (isset($_POST['comment'])) {
            $comment_error = validate_post_text(get_post_val('comment'));
            if (!$comment_error) {
                $comment_id = save_post_comment(
                    $con,
                    $_SESSION['user']['id'],
                    $post_id,
                    $_POST['comment']
                );
                header('location: /' . 'post.php?id=' . $post_id);
            }
        }

        $author_id = $post_data['author_id'];

        $post = format_post_data($post_data);
        $post['comments'] = $post_comments;
        $post['hashtags'] = get_post_hashtags($con, $post['id']);
        $template = get_post_template_by_type($post_data['type']);

        $post['template'] = include_template(
            $template,
            [
                'title' => $post['title'],
                'content' => $post['content'],
                'id' => $post['id'],
                'author' => $post['author_quote'],
                'full_mode' => true
            ]
        );

        $subscribers_count = get_user_subscriptions_count($con, $author_id);
        $posts_count = get_posts_count_by_author($con, $author_id);

        $author_info = get_user_by_id($con, $author_id);
        $author_info['subscribers_count'] = $subscribers_count;
        $author_info['posts_count'] = $posts_count;

        $title = 'readme:' . $post['title'];

        $content = include_template(
            'post-detail.php',
            compact('post', 'author_info', 'comment_error', 'has_subscription')
        );
    }
}


$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name')
);

print($page);
