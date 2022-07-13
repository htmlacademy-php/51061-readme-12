<?php

const SQL_POST = 'SELECT p.title,
       p.id,
       t.icon_class AS type,
       u.login AS user_name,
       u.avatar_url AS avatar,
       p.views,
       p.image_url,
       p.text,
       p.url,
       p.author_id,
       p.author_quote,
       u.login,
       p.video_url,
       p.created_at,
       (SELECT COUNT(l.id) FROM likes l WHERE p.id = l.post_id) AS likes_count,
       (SELECT COUNT(c.id) FROM comments c WHERE p.id = c.post_id) AS comments_count';

const SQL_POST_TEMPLATE = 'SELECT p.title,
       p.id,
       t.icon_class AS type,
       u.login AS user_name,
       u.avatar_url AS avatar,
       p.views,
       p.image_url,
       p.text,
       p.url,
       p.author_id,
       p.author_quote,
       p.video_url,
       p.created_at,
       (SELECT COUNT(l.id) FROM likes l WHERE p.id = l.post_id) AS likes_count,
       (SELECT COUNT(c.id) FROM comments c WHERE p.id = c.post_id) AS comments_count
       FROM posts p
         JOIN users u on p.author_id = u.id
         JOIN types t on p.content_type_id = t.id';

const SQL_USER_TEMPLATE = 'SELECT 
    *,
    COUNT(s.subscriptions) as total_subscriptions,
    COUNT(p.id)  as total_posts,
    FROM users u
              JOIN subscriptions s on s.author_id = u.id
              JOIN posts p on p.author_id = u.id';

function show_query_error($con, $description)
{
    $error = mysqli_error($con);
    print($description . $error);
}

function get_post_types($con)
{
    $result = mysqli_query($con, 'SELECT * FROM types');
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить типы постов');
        return;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_post($con, $id)
{
    if (!$id) {
        return 'Нет Id запроса';
    }
    $sql = SQL_POST_TEMPLATE . ' WHERE p.id = ? ';

    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить данные о посте');
        return;
    }
    return mysqli_fetch_assoc($result);
}

function get_post_comments($con, $id)
{
    $sql = 'SELECT 
        u.avatar_url as avatar_url,
        u.login as login,
        c.created_at as created_at,
        c.content as content 
    FROM comments c
         JOIN users u on u.id = c.author_id
         WHERE post_id=?';

    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить данные о посте');
        return;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_author_info($con, $id)
{
//    Не забудьте вывести всю информацию об авторе поста: аватар, число подписчиков и публикаций.
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

function get_subscribers_count($con, $author_id)
{
//    Не забудьте вывести всю информацию об авторе поста: аватар, число подписчиков и публикаций.
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

function get_posts_count_by_author($con, $author_id)
{
//    Не забудьте вывести всю информацию об авторе поста: аватар, число подписчиков и публикаций.
    $sql = 'SELECT count(id) as count
            FROM posts
            WHERE author_id = ? ';
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


function get_posts_by_subscription($con, $params)
{
    $current_user_id = $params['current_user_id'];
    $post_type = $params['type'];

    $query = SQL_POST . '
        FROM subscriptions s
                 JOIN posts p on p.author_id = s.author_id
                 JOIN users u on p.author_id = u.id
                 JOIN types t on p.content_type_id = t.id
        WHERE s.subscription = ' . $current_user_id;

    if ($post_type) {
        $query = $query . ' AND t.icon_class = "' . $post_type . '" ';
    }

    $query = $query . ' ORDER BY p.created_at ASC';

    $result = mysqli_query($con, $query);

    if (!$result) {
        show_query_error($con, 'Не удалось получить список постов');
        return;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function get_posts($con, $params)
{
    $query = SQL_POST_TEMPLATE;

    $post_type = $params['type'];
    $page = $params['page'];
    $limit = $params['limit'];

    if ($post_type) {
        $query = $query . ' WHERE t.icon_class = "' . $post_type . '" ';
    }

    $query = $query . ' ORDER BY views ASC ';

    if (isset($limit) and isset($page)) {
        $query = $query . ' LIMIT ' . $limit . ' OFFSET ' . (($page - 1) * $limit);
    }

    $result = mysqli_query($con, $query);

    if (!$result) {
        show_query_error($con, 'Не удалось получить список постов');
        return;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function get_posts_count($con, $post_type)
{
    $query = 'SELECT count(p.id) as count
        FROM posts p
        JOIN types t on p.content_type_id = t.id';

    if ($post_type) {
        $query = $query . ' WHERE t.icon_class = "' . $post_type . '" ';
    }

    $result = mysqli_query($con, $query);

    if (!$result) {
        show_query_error($con, 'Не удалось получить список постов');
        return;
    }
    return mysqli_fetch_assoc($result)['count'];
}

function save_post($con, $post_data)
{
    $sql = 'INSERT INTO posts SET
    content_type_id= ?,
    author_id= ?,
    title=?,
    image_url=?,
    video_url=?,
    text=?,
    author_quote=?,
    url=?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        $post_data['content_type_id'],
        $post_data['author_id'],
        $post_data['title'],
        $post_data['image_url'] ?? null,
        $post_data['video_url'] ?? null,
        $post_data['text'] ?? null,
        $post_data['author_quote'] ?? null,
        $post_data['url'] ?? null,
    ]);
    mysqli_stmt_execute($stmt);

    return mysqli_insert_id($con);
}


/**
 * Добавление тега к посту
 * @param mysqli $con Ресурс соединения
 * @param string $tag
 * @return string|false - id тега
 */
function save_tag(mysqli $con, string $tag)
{
    $sql = 'INSERT INTO hashtags SET name=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$tag]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}

/**
 * Добавление тега к посту
 * @param mysqli $mysql Ресурс соединения
 * @param int $tag_id
 * @param int $post_id
 */
function add_tag_to_post(mysqli $con, int $tag_id, int $post_id)
{
    $sql = 'INSERT INTO post_hashtags SET
        post_id=?,
        hashtag_id=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id, $tag_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

/**
 * Добавление тега к посту
 * @param mysqli $mysql Ресурс соединения
 * @param string $tag
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
 * Получени пользователя по логину
 * @param mysqli $mysql Ресурс соединения
 * @param string $login логин
 * @return mixed
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
 * Создание пользователя
 * @param mysqli $con Ресурс соединения
 * @param array{email:string,login:string,password:string,avatar_url:string} $user_data - данные пользователя
 * @return string|false - id юзера
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
 * Создание пользователя
 * @param mysqli $con Ресурс соединения
 * @param array{email:string,login:string,password:string,avatar_url:string} $user_data - данные пользователя
 * @return array - id юзера
 */
function search_posts(mysqli $con, string $text = '')
{
    $trim_text = trim($text);
    $res = [];
    $is_one_word = count(explode(' ', $trim_text)) == 1;
    if ($is_one_word) {
        $sql = SQL_POST_TEMPLATE . " WHERE p.title LIKE '%" . $trim_text . "%' or  p.text LIKE '%" . $trim_text . "%'";
        $res = mysqli_query($con, $sql);
        if (!$res) {
            show_query_error($con, 'Не удалось получить список постов');
            return;
        }
    } else {
        $sql = SQL_POST_TEMPLATE . ' WHERE MATCH(p.title, p.text) AGAINST(?)';
        $stmt = db_get_prepare_stmt($con, $sql, [
            'text' => $text,
        ]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if (!$res) {
            show_query_error($con, 'Не удалось получить список постов');
            return;
        }
    }
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
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


function get_user_posts(mysqli $con, int $user_id)
{
    $sql = SQL_POST_TEMPLATE . ' WHERE p.author_id = ? ';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $user_id,
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if (!$res) {
        show_query_error($con, 'Не удалось получить счетчик постов');
        return 0;
    }
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
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

function get_post_hashtags(mysqli $con, int $post_id)
{
    $sql = 'SELECT h.name AS hashtag 
            FROM post_hashtags ph
            JOIN hashtags h on ph.hashtag_id = h.id
            WHERE ph.post_id = ?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'post_id' => $post_id,
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    //    TODO как представить массив данных в виде одномерного массива
    return array_column(mysqli_fetch_all($res, MYSQLI_ASSOC), 'hashtag');
}

function like_post(mysqli $con, int $user_id, int $post_id)
{
    $sql = 'INSERT INTO likes SET
        author_id = ?,
        post_id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $user_id,
        'post_id' => $post_id
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}


function save_post_comment(
    mysqli $con,
    int $user_id,
    int $post_id,
    string $text
) {
    $sql = 'INSERT INTO comments SET
             author_id = ?,
             post_id = ?,
             content = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $user_id,
        'post_id' => $post_id,
        'content' => $text
    ]);
    mysqli_stmt_execute($stmt);
    return mysqli_insert_id($con);
}

