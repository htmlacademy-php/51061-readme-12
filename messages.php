<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $current_url string
 * @var $user_name string
 * @var $is_auth bool
 */
require_once('bootstrap.php');
$title = 'readme: сообщения';

if (!$is_auth) {
    header('Location: /index.php');
}


$active_user = null;
$no_dialogs = null;
$message_error = null;
$dialog = [];
$communications = get_user_communications(
        $con,
        $_SESSION['user']['id']
    ) ?? [];

if (isset($_GET['user_id'])) {
    $has_communication_with_user = array_search(
        (int)$_GET['user_id'],
        array_column($communications, 'id')
    );
    if (!is_numeric($has_communication_with_user)) {
        $active_user = get_user_by_id($con, $_GET['user_id']);
        $communications[] = $active_user;
    } else {
        $active_user = $communications[$has_communication_with_user];
    }
} elseif (!empty($communications)) {
    $active_user = $communications[0];
} else {
    $no_dialogs = true;
}

if (isset($active_user)) {
    $dialog = get_user_dialog(
        $con,
        $_SESSION['user']['id'],
        $active_user['id']
    );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message) {
        $message_id = sent_message(
            $con,
            $_SESSION['user']['id'],
            $active_user['id'],
            $_POST['message']
        );
        if ($message_id) {
            header('Location: /messages.php?user_id=' . $active_user['id']);
        }
    } else {
        $message_error = true;
    }
}


$content = include_template(
    'messages.php',
    compact(
        'communications',
        'active_user',
        'dialog',
        'message_error',
        'no_dialogs'
    )
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name', 'current_url')
);

print($page);

