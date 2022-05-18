<?php
/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 */

require_once('bootstrap.php');
require_once('authentication.php');

$title = 'readme: моя лента';

$content = include_template(
    'feed.php'
);
$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name')
);

print($page);
?>
