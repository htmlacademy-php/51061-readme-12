<?php


/**
 * Функция для валидации поля заголовка при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_heading($value)
{
    $len = strlen(trim($value));

    if (empty(trim($value))) {
        return "Это поле должно быть заполнено";
    }

    if ($len < 5 or $len > 50) {
        return "Значение должно быть от 5 до 50 символов";
    }
}

/**
 * Функция для валидации поля цитаты при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_quote($value)
{
    $len = strlen(trim($value));

    if (empty(trim($value))) {
        return "Это поле должно быть заполнено";
    }

    if ($len < 5 or $len > 70) {
        return "Значение должно быть от 5 до 70 символов";
    }
}

/**
 * Функция для валидации поля автора цитаты при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_quote_author($value)
{
    $len = strlen($value);

    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }

    if ($len < 2 or $len > 50) {
        return "Значение должно быть от 2 до 50 символов";
    }
}

/**
 * Функция для валидации поля текста поста при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_post_text($value)
{
    $len = strlen($value);

    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }

    if ($len < 2 or $len > 250) {
        return "Значение должно быть от 2 до 250 символов";
    }
}

/**
 * Функция для валидации поля комментария
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_message($value)
{
    $len = strlen($value);

    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }

    if ($len < 2 or $len > 150) {
        return "Значение должно быть от 2 до 150 символов";
    }
}

/**
 * Функция для валидации поля ссылки при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_url($value, $required = true)
{
    if ($required && empty($value)) {
        return "Это поле должно быть заполнено";
    }

    if ($value && filter_var($value, FILTER_VALIDATE_URL) === false) {
        return "Была введена неправильная ссылка";
    }
}

/**
 * Функция для валидации поля ссылки видео при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_video($value)
{
    if (empty($value)) {
        return "Это поле должно быть заполнено";
    }

    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
        return "Была введена неправильная ссылка";
    }


    set_error_handler(function () {
    });
    $url = "https://www.youtube.com/oembed?url=" . $value;
    $fop = fopen($url, "rb");
    if (!$fop && $fop == false) {
        return "Данное видео не найдено";
    };
    restore_error_handler();
}

/**
 * Функция для валидации поля ссылки на изображение при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_image_url($value)
{
    $error = validate_url($value);

    if ($error) {
        return $error;
    }

    if (file_get_contents($value) === false) {
        return 'Не удалось найти изображение. Проверьте ссылку.';
    }
}

/**
 * Функция для валидации поля загружаемого изображения при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_image($image)
{
    if ($image && $image['error'] !== 4) {
        $fileType = $image['type'];

        $validImageTypes = [
            'image/png',
            'image/jpeg',
            'image/jpg',
            'image/gif'
        ];

        if (!in_array($fileType, $validImageTypes)) {
            return $fileType . 'Неверный формат загружаемого файла. Допустимый формат: ' . implode(
                    ' , ',
                    $validImageTypes
                );
        }
    }
}

/**
 * Функция для валидации поля хэштегов при добавлении поста
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_hashtag($value)
{
    if (!empty($value)) {
        $len = strlen($value);

        if ($len < 2 or $len > 250) {
            return "Значение должно быть от 2 до 250 символов";
        }

        $hashtags = explode(' ', $value);

        foreach ($hashtags as $hashtag) {
            if (substr($hashtag, 0, 1) !== '#') {
                return 'Хэштег должен начинаться со знака решетки';
            }
            if (strrpos($hashtag, '#') > 0) {
                return 'Хэш-теги разделяются пробелами';
            }
            if (strlen($hashtag) < 2) {
                return 'Хэш-тег не может состоять только из знака решетки';
            }
        }
    }
}

