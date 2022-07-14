<?php

require_once('./queries/helpers.php');
require_once('./queries/posts.php');

const SQL_USER_TEMPLATE = 'SELECT 
    *,
    COUNT(s.subscriptions) as total_subscriptions,
    COUNT(p.id)  as total_posts,
    FROM users u
              JOIN subscriptions s on s.author_id = u.id
              JOIN posts p on p.author_id = u.id';

function get_author_info(mysqli $con, int $id)
{
    $sql = 'SELECT *
            FROM users u
            WHERE id = ? ';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить данные о пользователе');
        return;
    }
    return mysqli_fetch_assoc($result);
}

function get_subscribers_count(mysqli $con, int $author_id)
{
    $sql = 'SELECT COUNT(subscription) as count
            FROM subscriptions
            WHERE subscription = ? ';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $author_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить количество подписчиков');
        return;
    }
    return mysqli_fetch_assoc($result)['count'];
}

function save_tag(mysqli $con, string $tag)
{
    $sql = 'INSERT INTO hashtags SET name=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$tag]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}

function get_tag_id(mysqli $con, string $tag)
{
    $sql = 'SELECT id from hashtags WHERE name = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$tag]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    return $row['id'];
}

function is_email_or_login_available(mysqli $con, array $data)
{
    $sql = 'SELECT * from users WHERE';
    if (isset($data['email'])) {
        $sql = $sql . ' email = ?';
        $stmt = db_get_prepare_stmt($con, $sql, [$data['email']]);
    }

    if (isset($data['login'])) {
        $sql = $sql . ' login = ?';
        $stmt = db_get_prepare_stmt($con, $sql, [$data['login']]);
    }

    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);

    return empty($row);
}

function get_user(mysqli $con, string $login)
{
    $sql = 'SELECT * from users WHERE login=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$login]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($res);
}

function get_user_by_id(mysqli $con, string $id)
{
    $sql = 'SELECT * from users WHERE id=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($res);
}

function create_user(mysqli $con, array $user_data)
{
    $sql = 'INSERT INTO users SET email=?,login=?,password=?,avatar_url=?';
    $stmt = db_get_prepare_stmt($con, $sql, [
        'email' => $user_data['email'],
        'login' => $user_data['login'],
        'password' => $user_data['password'],
        'avatar_url' => $user_data['avatar_url'] ?? null,
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}

function get_user_subscriptions_count(mysqli $con, int $user_id): int
{
    $sql = 'SELECT COUNT(subscription) as total_subscriptions
                    FROM subscriptions
                    WHERE subscription = ?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $user_id,
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (!$res) {
        show_query_error($con, 'Не удалось получить счетчик подписок');
        return 0;
    }
    return mysqli_fetch_assoc($res)['total_subscriptions'];
}

function check_subscribe_to_user(
    mysqli $con,
    int $author_id,
    int $subscription_id
): bool {
    $sql = 'SELECT * from subscriptions
                    WHERE author_id = ? AND subscription  = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $author_id,
        'subscription' => $subscription_id
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (!$res) {
        show_query_error($con, 'Не удалось получить счетчик постов');
        return 0;
    }
    return !!mysqli_fetch_assoc($res);
}

function subscribe_to_user(mysqli $con, int $author_id, int $subscription_id)
{
    $sql = 'INSERT INTO subscriptions SET
        author_id = ?,
        subscription = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $author_id,
        'subscription' => $subscription_id
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}

function unsubscribe_to_user(mysqli $con, int $author_id, int $subscription_id)
{
    $sql = 'DELETE FROM subscriptions
        WHERE author_id = ? AND subscription = ?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $author_id,
        'subscription' => $subscription_id
    ]);
    mysqli_stmt_execute($stmt);
}

