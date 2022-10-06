<?php
/**
 * @var $con mysqli
 * @var $current_time string
 * @var $referer string
 * @var $user_name string
 * @var $is_auth bool
 * @var $send_email - функция отправки email
 */

require_once('bootstrap.php');
require_once('mail.php');

$get_param_user_id = $_GET['user_id'] ?? null;
$user = get_user_by_id($con, $get_param_user_id);

if ($_GET['has_subscription'] === 'true') {
    unsubscribe_to_user(
        $con,
        $_SESSION['user']['id'],
        $get_param_user_id
    );
} else {
    subscribe_to_user(
        $con,
        $_SESSION['user']['id'],
        $get_param_user_id,
    );
    $message = [
        'to' => $user['email'],
        'subject' => 'У вас новый подписчик!',
        'text' => 'Здравствуйте, ' . $user['login'] . '. На вас подписался новый пользователь ' . $_SESSION['user']['login'] . '. Вот ссылка на его профиль: ' . ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/profile.php?id=' . $_SESSION['user']['id'],
    ];
    $send_email($message);
}


header('Location:' . $referer);

