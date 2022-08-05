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

/**
 * Получение пользователя по id
 * @param mysqli $con Ресурс соединения
 * @param string $id Id пользователя
 * @return array
 */
function get_user_by_id(mysqli $con, string $id)
{
    $sql = 'SELECT * from users WHERE id=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($res);
}

/**
 * Сохранение тега
 * @param mysqli $con Ресурс соединения
 * @param string $tag название тега
 * @return string - id тега
 */
function save_tag(mysqli $con, string $tag)
{
    $sql = 'INSERT INTO hashtags SET name=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$tag]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}

/**
 * Получение id тега по имени
 * @param mysqli $con Ресурс соединения
 * @param string $tag Название тега
 * @return string - id тега
 */
function get_tag_id(mysqli $con, string $tag)
{
    $sql = 'SELECT id from hashtags WHERE name = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$tag]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    return $row['id'];
}

/**
 * Проверка логина или пароля
 * @param mysqli $mysql Ресурс соединения
 * @param array{login:string, email:string} $data
 * @return boolean
 */
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

/**
 * Получени пользователя по логину
 * @param mysqli $mysql Ресурс соединения
 * @param string $login логин
 * @return mixed
 */
function get_user(mysqli $con, string $login)
{
    $sql = 'SELECT * from users WHERE login=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$login]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($res);
}


/**
 * Создание пользователя
 * @param mysqli $con Ресурс соединения
 * @param array{email:string,login:string,password:string,avatar_url:string} $user_data - данные пользователя
 * @return string - id юзера
 */
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

/**
 * Количество подписчиков пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id - id пользователя
 * @return int
 */
function get_user_subscriptions_count(mysqli $con, int $user_id): int
{
    $sql = 'SELECT COUNT(subscription) as total_subscriptions
                    FROM subscriptions
                    WHERE author_id = ?';

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

/**
 * Получение подписчиков пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id - id пользователя
 * @return array
 */
function get_user_subscribers(mysqli $con, int $user_id): array
{
    $sql = 'SELECT u.* from subscriptions s
                 join users u on u.id = s.subscription
            WHERE s.author_id = ?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $user_id,
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (!$res) {
        show_query_error($con, 'Не удалось получить счетчик подписок');
        return 0;
    }
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Проверить есть ли подписка на пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $author_id - id пользователя
 * @param int $subscription_id - id пользователя
 * @return bool
 */
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
    return !!mysqli_fetch_assoc($res);
}

/**
 * Подписка на пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $author_id - id пользователя
 * @param int $subscription_id - id пользователя
 * @return string - id подписки
 */
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

/**
 * Отписка на пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $author_id - id пользователя
 * @param int $subscription_id - id пользователя
 */
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

