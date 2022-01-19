<?php

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
    return mysqli_fetch_all($result, MYSQLI_ASSOC);//$result->fetch_all(MYSQLI_ASSOC) ?
}

function get_posts($con)
{
    $query = 'SELECT
                   p.title AS title,
                   t.icon_class AS type,
                   u.login AS user_name,
                   u.avatar_url AS avatar,
                   p.views,
                   p.image_url AS image_url,
                   p.text AS text,
       p.url AS url,
       p.author_quote AS author_quote,
                   p.video_url AS video_url
            FROM posts p
              JOIN users u on p.author_id = u.id
              JOIN types t on p.content_type_id = t.id
            ORDER BY views ASC;';

    $result = mysqli_query($con, $query);
    if (!$result) {
        show_query_error($con, "Не удалось получить список постов");
        return;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function get_queries()
{
    return [
        'postTypes' => "get_post_types",
        'posts' => "get_posts"
    ];
}

?>