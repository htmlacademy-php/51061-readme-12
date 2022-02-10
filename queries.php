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

?>
