<?php

require_once('./queries/helpers.php');

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
        return;
    }
    return mysqli_fetch_assoc($result);
}

function get_post_comments(mysqli $con, int $id)
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
        return;
    }
    return mysqli_fetch_assoc($result)['count'];
}


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
       (SELECT COUNT(c.id) FROM comments c WHERE p.id = c.post_id) AS comments_count
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


function get_posts(mysqli $con, array $params)
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
        return;
    }
    return mysqli_fetch_assoc($result)['count'];
}

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

function add_tag_to_post(mysqli $con, int $tag_id, int $post_id)
{
    $sql = 'INSERT INTO post_hashtags SET
        post_id=?,
        hashtag_id=?';
    $stmt = db_get_prepare_stmt($con, $sql, [$post_id, $tag_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
