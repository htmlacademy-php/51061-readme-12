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

if (!$is_auth) {
    header('Location: /index.php');
}

$content = include_template(
    'feed.php'
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name', 'current_url')
);

print($page);
