<?php

require_once('./queries/helpers.php');

const SQL_POST_TEMPLATE = 'SELECT p.title,
       p.id,
       t.icon_class AS type,
       p.content_type_id,
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
       (SELECT COUNT(c.id) FROM comments c WHERE p.id = c.post_id) AS comments_count,
       (SELECT COUNT(p2.original_post_id) FROM posts p2 WHERE p.id = p2.original_post_id) AS repost_count
       FROM posts p
         JOIN users u on p.author_id = u.id
         JOIN types t on p.content_type_id = t.id';

/**
 * Получение списка постов
 * @param mysqli $con Ресурс соединения
 * @return array|void
 */
function get_post_types($con)
{
    $result = mysqli_query($con, 'SELECT * FROM types');
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить типы постов');
    } else {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}


/**
 * Получение поста по id
 * @param mysqli $con Ресурс соединения
 * @param int $id ID поста
 * @return array|string|void
 */
function get_post(mysqli $con, int $id)
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
    } else {
        return mysqli_fetch_assoc($result);
    }
}

/**
 * Получение кло-ва постов по автору
 * @param mysqli $con Ресурс соединения
 * @param string $author_id ID пользователя
 * @return int|void
 */
function get_post_comments(mysqli $con, int $id)
{
    $sql = 'SELECT 
        u.avatar_url as avatar_url,
        u.login as login,
        c.created_at as created_at,
        c.content as content, 
        c.author_id as author_id,
        c.id
    FROM comments c
         JOIN users u on u.id = c.author_id
         WHERE post_id=?';

    $stmt = db_get_prepare_stmt($con, $sql, [$id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить данные о посте');
    } else {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

/**
 * Получение кло-ва постов по автору
 * @param mysqli $con Ресурс соединения
 * @param string $author_id ID пользователя
 * @return int|void
 */
function get_posts_count_by_author(mysqli $con, string $author_id)
{
    $sql = 'SELECT count(id) as count
            FROM posts
            WHERE author_id = ? ';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $author_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        show_query_error($con, 'Не удалось загрузить количество подписчиков');
    } else {
        return mysqli_fetch_assoc($result)['count'];
    }
}

/**
 * Получение постов по подписке
 * @param mysqli $con Ресурс соединения
 * @param array{current_user_id:string, type:string} $params параметры запроса
 * @return array
 */
function get_posts_by_subscription(mysqli $con, array $params)
{
    $current_user_id = $params['current_user_id'];
    $post_type = $params['type'];

    $query = 'SELECT p.title,
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
       (SELECT COUNT(c.id) FROM comments c WHERE p.id = c.post_id) AS comments_count,
       (SELECT COUNT(p2.original_post_id) FROM posts p2 WHERE p.id = p2.original_post_id) AS repost_count
        FROM subscriptions s
                 JOIN posts p on p.author_id = s.subscription
                 JOIN users u on p.author_id = u.id
                 JOIN types t on p.content_type_id = t.id
        WHERE s.author_id = ' . $current_user_id;

    if ($post_type) {
        $query = $query . ' AND t.icon_class = "' . $post_type . '" ';
    }

    $query = $query . ' ORDER BY p.created_at ASC';

    $result = mysqli_query($con, $query);

    if (!$result) {
        show_query_error($con, 'Не удалось получить список постов');
    } else {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

/**
 * Получение постов (с пагинацией если будут переданы параметры)
 * @param mysqli $con Ресурс соединения
 * @param array{type:string, page:string, limit:string} $params параметры запроса
 * @return array
 */
function get_posts(mysqli $con, array $params)
{
    $query = SQL_POST_TEMPLATE;

    $post_type = $params['type'];
    $page = $params['page'];
    $limit = $params['limit'];
    $sort = $params['sort'];
    $order = $params['order'];

    if ($post_type) {
        $query = $query . ' WHERE t.icon_class = "' . $post_type . '" ';
    }

    if ($sort && $order) {
        $query = $query . ' ORDER BY ' . $sort . ' ' . $order;
    }


    if (isset($limit) and isset($page)) {
        $query = $query . ' LIMIT ' . $limit . ' OFFSET ' . (($page - 1) * $limit);
    }

    $result = mysqli_query($con, $query);

    if (!$result) {
        show_query_error($con, 'Не удалось получить список постов');
    } else {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

/**
 * Получение количества постов
 * @param mysqli $con Ресурс соединения
 * @param string|null $post_type Название типа поста (опционально)
 * @return int
 */
function get_posts_count(mysqli $con, ?string $post_type)
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
    } else {
        return mysqli_fetch_assoc($result)['count'];
    }
}

/**
 * Сохранение поста
 * @param mysqli $con Ресурс соединения
 * @param array $post_data Строка запроса
 * @return int ID поста
 */
function save_post(mysqli $con, array $post_data)
{
    $sql = 'INSERT INTO posts SET
    content_type_id= ?,
    author_id= ?,
    title=?,
    image_url=?,
    video_url=?,
    text=?,
    author_quote=?,
    url=?,
    repost=?,
    original_author_id=?,
    original_post_id=?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        $post_data['content_type_id'],
        $post_data['author_id'],
        $post_data['title'],
        $post_data['image_url'] ?? null,
        $post_data['video_url'] ?? null,
        $post_data['text'] ?? null,
        $post_data['author_quote'] ?? null,
        $post_data['url'] ?? null,
        $post_data['repost'] ?? null,
        $post_data['original_author_id'] ?? null,
        $post_data['original_post_id'] ?? null,
    ]);
    mysqli_stmt_execute($stmt);

    return mysqli_insert_id($con);
}

/**
 * Поиск постов по строке
 * @param mysqli $con Ресурс соединения
 * @param string $text Строка запроса
 * @return array
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

/**
 * Поиск постов по тегу
 * @param mysqli $con Ресурс соединения
 * @param string $text хештег
 * @return array
 */
function search_posts_by_tag(mysqli $con, string $hashtag = '')
{
    $sql = 'SELECT p.title,
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
        FROM hashtags h
                JOIN post_hashtags ph on ph.hashtag_id = h.id
                JOIN posts p on p.id = ph.post_id
                JOIN users u on p.author_id = u.id
                JOIN types t on p.content_type_id = t.id
        WHERE h.name = ?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'name' => $hashtag,
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Получение хештегов поста
 * @param mysqli $con Ресурс соединения
 * @param int $post_id ID поста
 * @return array
 */
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
    return array_column(mysqli_fetch_all($res, MYSQLI_ASSOC), 'hashtag');
}

/**
 * Проверка имеет ли пост лайк
 * @param mysqli $con Ресурс соединения
 * @param int $user_id ID автора
 * @param int $post_id ID поста
 * @return id - ID лайка
 */
function has_like(mysqli $con, int $user_id, int $post_id)
{
    $sql = 'SELECT * FROM likes
        WHERE author_id = ? AND post_id = ?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $user_id,
        'post_id' => $post_id
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($res)['id'];
}

/**
 * Добавление лайка к комментарию
 * @param mysqli $con Ресурс соединения
 * @param int $user_id ID автора
 * @param int $post_id ID поста
 * @return id - ID лайка
 */
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

/**
 * Удаление лайка к комментарию
 * @param mysqli $con Ресурс соединения
 * @param int $like_id ID лайка
 */
function unlike_post(mysqli $con, int $like_id)
{
    $sql = 'DELETE FROM likes WHERE id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [
        'id' => $like_id
    ]);
    mysqli_stmt_execute($stmt);
}

/**
 * Сохранение комментария к посту
 * @param mysqli $con Ресурс соединения
 * @param int $user_id ID пользователя
 * @param int $post_id ID поста
 * @param string $text Текст комментария
 * @return id - ID комментария
 */
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

/**
 * Получение постов пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id ID пользователя
 * @return array - список постов
 */
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

/**
 * Получение постов с лайками пользователя
 * @param mysqli $con Ресурс соединения
 * @param int $user_id ID пользователя
 * @return array - список постов
 */
function get_user_posts_with_likes(mysqli $con, int $user_id)
{
    $sql = 'SELECT p.title,
                   p.id,
                   t.icon_class                       AS type,
                   p.content_type_id,
                   u.login                            AS user_name,
                   u.avatar_url                       AS avatar,
                   p.image_url,
                   p.text,
                   p.url,
                   p.author_id,
                   p.author_quote,
                   p.video_url,
                   l.created_at
            FROM likes l
                     JOIN posts p on p.id = l.post_id
                     JOIN users u on u.id = l.author_id
                     JOIN types t on p.content_type_id = t.id
            WHERE p.author_id= ?';

    $stmt = db_get_prepare_stmt($con, $sql, [
        'author_id' => $user_id,
    ]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
        show_query_error($con, 'Не удалось получить список постов');
    }
}

/**
 * Добавление тега к посту
 * @param mysqli $con Ресурс соединения
 * @param int $tag_id ID тега
 * @param int $post_id ID поста
 * @return string - id тега
 */
function add_tag_to_post(mysqli $con, int $tag_id, int $post_id)
{
    $sql = 'INSERT INTO post_hashtags SET
        post_id=?,
        hashtag_id=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id, $tag_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_insert_id($con);
}

/**
 * Просмотр поста
 * @param mysqli $con Ресурс соединения
 * @param int $post_id ID поста
 */
function increment_post_view(mysqli $con, int $post_id)
{
    $sql = 'UPDATE posts SET views = views + 1
            WHERE id = ?';
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id]);
    mysqli_stmt_execute($stmt);
}
