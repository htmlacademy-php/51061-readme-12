<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 * @var get_post_val function
 */
require_once('bootstrap.php');
require_once('helpers/validate-functions.php');

$errors = [];

if ($is_auth) {
    header('Location: /feed.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $error = validate_login($_POST['login']);
        if ($error) {
            $errors['login'] = $error;
        }
    }
    if (isset($_POST['password'])) {
        $error = validate_password($_POST['password']);
        if ($error) {
            $errors['password'] = $error;
        }
    }
    if (empty($errors)) {
        $user = get_user($con, $_POST['login']);
        if (!$user) {
            $errors['login'] = 'Пользователь не найден';
        } elseif (password_verify(
            $_POST['password'],
            $user['password']
        )) {
            $_SESSION['user'] = $user;
            header('Location: /feed.php');
        } else {
            $errors['password'] = 'Пароли не совпадают';
        }
    }
}

$page = include_template(
    'sign-in.php',
    ['errors' => $errors]
);

print($page);

?>


