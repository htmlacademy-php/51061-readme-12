<?php

const ERROR_TEMPLATES = [
    'empty' => 'Это поле должно быть заполнено',
    'is_short_text' => 'Значение должно быть от 3 до 50 символов',
    'is_medium_text' => 'Значение должно быть от 3 до 150 символов',
    'is_big_text' => 'Значение должно быть от 3 до 250 символов',
];

function is_short_text(string $len): bool
{
    return $len < 3 or $len > 50;
}

function is_medium_text(string $len): bool
{
    return $len < 3 or $len > 150;
}

function is_big_text(string $len): bool
{
    return $len < 3 or $len > 250;
}


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
        return ERROR_TEMPLATES['empty'];
    }

    if (is_short_text($len)) {
        return ERROR_TEMPLATES['is_short_text'];
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
        return ERROR_TEMPLATES['empty'];
    }

    if ($len < 5 or $len > 70) {
        return 'Значение должно быть от 5 до 70 символов';
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
        return ERROR_TEMPLATES['empty'];
    }

    if (is_short_text($len)) {
        return ERROR_TEMPLATES['is_short_text'];
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
        return ERROR_TEMPLATES['empty'];
    }

    if (is_big_text($len)) {
        return ERROR_TEMPLATES['is_big_text'];
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
        return ERROR_TEMPLATES['empty'];
    }

    if (is_medium_text($len)) {
        return ERROR_TEMPLATES['is_medium_text'];
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
        return ERROR_TEMPLATES['empty'];
    }

    if ($value && filter_var($value, FILTER_VALIDATE_URL) === false) {
        return 'Была введена неправильная ссылка';
    }
}

/**
 * Функция для валидации ссылки на youtube
 * @param $value
 * @return string|void
 */
function validate_youtube_url($value)
{
    if (empty($value)) {
        return ERROR_TEMPLATES['empty'];
    }

    $res = check_youtube_url($value);
    if ($res !== true) {
        return $res;
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
        return ERROR_TEMPLATES['empty'];
    }

    if (filter_var($value, FILTER_VALIDATE_URL) === false) {
        return 'Была введена неправильная ссылка';
    }

    $url = 'https://www.youtube.com/oembed?url=' . $value;
    $fop = fopen($url, 'rb');
    if (!$fop && $fop == false) {
        return 'Данное видео не найдено';
    }
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

        if (is_big_text($len)) {
            return ERROR_TEMPLATES['is_big_text'];
        }

        $hashtags = explode(' ', $value);

        if (count(array_unique($hashtags)) < count($hashtags)) {
            return 'Указаны одинаковые хештеги';
        }

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

/**
 * Функция для валидации поля почта при регистрации пользователя
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @param $connectDb
 * @return string|void
 */
function validate_email($value)
{
    if (empty($value)) {
        return ERROR_TEMPLATES['empty'];
    }

    $len = strlen($value);

    if (is_medium_text($len)) {
        return ERROR_TEMPLATES['is_medium_text'];
    }

    if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
        return 'Был введен неправильный Email';
    }
}

/**
 * Функция для валидации поля логин при регистрации пользователя
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_login($value)
{
    if (empty($value)) {
        return ERROR_TEMPLATES['empty'];
    }

    $len = strlen($value);

    if (is_short_text($len)) {
        return ERROR_TEMPLATES['is_short_text'];
    }
}

/**
 * Функция для валидации поля пароль при регистрации пользователя
 * и вывода ошибок, если валидация не прошла
 * @param $value
 * @return string|void
 */
function validate_password($value)
{
    if (empty($value)) {
        return ERROR_TEMPLATES['empty'];
    }

    $len = strlen($value);

    if ($len < 8 or $len > 150) {
        return 'Значение должно быть от 8 до 150 символов';
    }

    if (!preg_match('/^\S*$/', $value)) {
        return 'Пароль не должен содержать пробелы';
    }
}

/**
 * Функция для валидации поля повторите пароль при регистрации пользователя
 * и вывода ошибок, если валидация не прошла
 * @param $pass
 * @param $repeatPass
 * @return string|void
 */
function validate_repeat_password($pass, $repeatPass)
{
    if (empty($repeatPass)) {
        return ERROR_TEMPLATES['empty'];
    }

    if ($pass !== $repeatPass) {
        return 'Пароли не совпадают';
    }
}
