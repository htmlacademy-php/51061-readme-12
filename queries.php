<?php

const SQL_POST_TEMPLATE = 'SELECT
                   p.title AS title,
                   p.id,
                   t.icon_class AS type,
                   u.login AS user_name,
                   u.avatar_url AS avatar,
                   p.views,
                   p.image_url AS image_url,
                   p.text AS text,
                   p.url AS url,
                   p.author_id AS author_id,
                   p.author_quote AS author_quote,
                   p.video_url AS video_url
            FROM posts p
              JOIN users u on p.author_id = u.id
              JOIN types t on p.content_type_id = t.id';

function show_query_error($con, $description)
{
    $error = mysqli_error($con);
    print($description . $error);
}

function get_post_types($con)
{
    $result = mysqli_query($con, "SELECT * FROM types");
    if (!$result) {
        show_query_error($con, "Не удалось загрузить типы постов");
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
        show_query_error($con, "Не удалось загрузить данные о посте");
        return;
    }
    return mysqli_fetch_assoc($result);
}

function get_author_info($con, $id)
{
//    Не забудьте вывести всю информацию об авторе поста: аватар, число подписчиков и публикаций.
    $sql = 'SELECT
                u.email,
                u.login,
                u.avatar_url,
                u.id
            FROM users u
            WHERE id = ? ';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) {
        show_query_error($con, "Не удалось загрузить данные о пользователе");
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
        show_query_error($con, "Не удалось загрузить количество подписчиков");
        return;
    }
    return mysqli_fetch_assoc($result)['count'];
}

function get_posts_count($con, $author_id)
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
        show_query_error($con, "Не удалось загрузить количество подписчиков");
        return;
    }
    return mysqli_fetch_assoc($result)['count'];
}


function get_posts($con, $post_type)
{
    $query = SQL_POST_TEMPLATE;

    if ($post_type) {
        $query = $query . ' WHERE t.icon_class = "' . $post_type . '" ';
    }

    $query = $query . ' ORDER BY views ASC;';
    $result = mysqli_query($con, $query);

    if (!$result) {
        show_query_error($con, "Не удалось получить список постов");
        return;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function save_post($con, $post_data)
{
    $sql = "INSERT INTO posts SET
    content_type_id= ?,
    author_id= ?,
    title=?,
    image_url=?,
    video_url=?,
    text=?,
    author_quote=?,
    url=?";

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
function saveTag(mysqli $con, string $tag)
{
    $sql = "INSERT INTO hashtags SET name=?";
    $stmt = db_get_prepare_stmt($con, $sql, [$tag]);
    mysqli_stmt_execute($stmt);
    $tag_id = mysqli_insert_id($con);
    return $tag_id;
}

/**
 * Добавление тега к посту
 * @param mysqli $mysql Ресурс соединения
 * @param int $tag_id
 * @param int $post_id
 */
function add_tag_to_post(mysqli $con, int $tag_id, int $post_id)
{
    $sql = "INSERT INTO post_hashtags SET
        post_id=?,
        hashtag_id=?";
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
    $sql = "SELECT id from hashtags WHERE name = ?";
    $stmt = db_get_prepare_stmt($con, $sql, [$tag]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt,);
    $row = mysqli_fetch_assoc($res);
    return $row['id'];
}

?>
