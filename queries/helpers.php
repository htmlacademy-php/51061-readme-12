<?php

/**
 * Показ ошибки при запросе
 * @param mysqli $con Ресурс соединения
 * @param string $description описание
 * @return array
 */
function show_query_error($con, $description)
{
    $error = mysqli_error($con);
    print($description . $error);
}

