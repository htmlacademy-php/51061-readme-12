<?php

function show_query_error($con,$description) {
        $error = mysqli_error($con);
        print($description . $error);
}

function get_post_types($con){
    $result = mysqli_query($con,"SELECT * FROM types");
    if (!$result) {
        show_query_error($con,"Не удалось загрузить типы постов");
        return;
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);//$result->fetch_all(MYSQLI_ASSOC) ?
};

function get_queries(){

    return [
        "postTypes"=>'get_post_types'
    ];
}
?>
