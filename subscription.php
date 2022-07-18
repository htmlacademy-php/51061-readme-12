<?php
/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 */

require_once('bootstrap.php');

$get_param_user_id = $_GET['user_id'] ?? null;

if ($_GET['has_subscription'] === 'true') {
    unsubscribe_to_user(
        $con,
        $get_param_user_id,
        $_SESSION['user']['id']
    );
} else {
    subscribe_to_user(
        $con,
        $get_param_user_id,
        $_SESSION['user']['id']

    );
}

header('Location:' . $_SERVER[HTTP_REFERER]);

