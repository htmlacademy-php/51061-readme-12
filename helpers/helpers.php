<?php

$current_time = date_create();
$current_timestamp = $current_time->getTimestamp();
const TIME_POINTS = [
    'minute' => 60,
    'hour' => 3600,
    'day' => 86400,
    'week' => 604800
];

const UPLOAD_ERR_NO_FILE_ID = 4;

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error(
                $link
            );
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            if (is_string($value)) {
                $type = 's';
            }
            if (is_double($value)) {
                $type = 'd';
            }

            $types .= $type;
            $stmt_data[] = $value;
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error(
                    $link
                );
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(
    int $number,
    string $one,
    string $two,
    string $many
): string {
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {
    }, E_WARNING);
    $headers = get_headers(
        'https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id
    );
    restore_error_handler();

    if (!is_array($headers)) {
        return 'Видео по такой ссылке не найдено. Проверьте ссылку на видео';
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return 'Видео по такой ссылке не найдено. Проверьте ссылку на видео';
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = '';
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = 'https://www.youtube.com/embed/' . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url = '')
{
    $res = '';
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf('https://img.youtube.com/vi/%s/mqdefault.jpg', $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } elseif ($parts['host'] == 'youtu.be') {
            $id = substr($parts['path'], 1);
        }
    }
    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [
        ['minutes' => 59],
        ['hours' => 23],
        ['days' => 6],
        ['weeks' => 4],
        ['months' => 11]
    ];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Ряд функций для соответствия интервала времени определенным промежуткам (день, неделя и т.д)
 * @param int $time
 * @return bool
 */
function shouldWeShowAsHoursAgo(int $time): bool
{
    return $time > TIME_POINTS['hour'] && $time <= TIME_POINTS['day'];
}

function shouldWeShowAsDaysAgo(int $time): bool
{
    return $time > TIME_POINTS['day'] && $time <= TIME_POINTS['week'];
}

function shouldWeShowAsWeeksAgo(int $time): bool
{
    return $time > TIME_POINTS['week'] && ($time <= (TIME_POINTS['week'] * 5));
}

function shouldWeShowMonthAgo(int $time): bool
{
    return $time > (TIME_POINTS['week'] * 5);
}

/**
 * Генерирует строку для прошедшего времени с момента(переданного параметром) до настоящего момента
 * в нужном сколнении в адекватном временном интервале (минут,часов, дней и т.д.)
 * @param string $date
 * @param int|null $current_timestamp
 * @return string|false
 */
function get_passed_time_title(string $date = '', int $current_timestamp = null)
{
    if (!$date) {
        return false;
    }
    if (!$current_timestamp) {
        $current_timestamp = date_create()->getTimestamp();
    }

    $post_date = strtotime($date);
    $diff = $current_timestamp - $post_date;

    switch ($diff) {
        case ($diff < TIME_POINTS['hour']): //если до текущего времени прошло меньше 60 минут, то формат будет вида «% минут назад»;
            $past_time = floor($diff / TIME_POINTS['minute']);
            $plural_form = get_noun_plural_form(
                $past_time,
                'минута',
                'минуты',
                'минут'
            );
            break;
        case (shouldWeShowAsHoursAgo(
            $diff
        )): //если до текущего времени прошло больше 60 минут, но меньше 24 часов, то формат будет вида «% часов назад»;
            $past_time = floor($diff / TIME_POINTS['hour']);
            $plural_form = get_noun_plural_form(
                $past_time,
                'час',
                'часа',
                'часов'
            );
            break;
        case (shouldWeShowAsDaysAgo(
            $diff
        ))://если до текущего времени прошло больше 24 часов, но меньше 7 дней, то формат будет вида «% дней назад»;
            $past_time = floor($diff / TIME_POINTS['day']);
            $plural_form = get_noun_plural_form(
                $past_time,
                'день',
                'дня',
                'дней'
            );
            break;
        case (shouldWeShowAsWeeksAgo(
            $diff
        )): //если до текущего времени прошло больше 7 дней, но меньше 5 недель, то формат будет вида «% недель назад»;
            $past_time = floor($diff / TIME_POINTS['week']);
            $plural_form = get_noun_plural_form(
                $past_time,
                'неделя',
                'недели',
                'недель'
            );
            break;
        case (shouldWeShowMonthAgo($diff)) :
            //если до текущего времени прошло больше 5 недель, то формат будет вида «% месяцев назад».
            $current_date = (new DateTime())->setTimestamp($current_timestamp);
            $past_time = date_diff($current_date, date_create($date))->format(
                '%m'
            );
            $plural_form = get_noun_plural_form(
                $past_time,
                'месяц',
                'месяца',
                'месяцев'
            );
            break;
    }

    if (isset($past_time) && isset($plural_form)) {
        return $past_time . ' ' . $plural_form . ' назад';
    }
    return false;
}

/**
 * текст, если его длина меньше заданного числа(по умолчанию 300) символов. В противном случае это должен быть урезанный текст с прибавленной к нему ссылкой.
 * @param string $text строка
 * @param int $length допустимая длинна
 * @return bool
 */
function is_need_trunc(string $text, int $length = 300): bool
{
    return mb_strlen($text) > $length;
}

/**
 * обрезает текст более заданного числа(по умолчанию 300) символов. дополняется  прибавлением к нему "...".
 * @param string $text строка
 * @param int $length допустимая длинна
 * @return string обрезанный текст с ... | исходный
 */
function trunc_text(string $text, int $length = 300): string
{
    $words_array = explode(' ', $text);

    $last_word_index = 0;
    $symbol_count = 0;

    foreach ($words_array as $i => $word) {
        if ($words_array[$i + 1]) {
            // если не последнее слово увеличиваем кол-во на 1( пробел между строками)
            $symbol_count++;
        }
        $symbol_count = $symbol_count + mb_strlen($word);
        if ($symbol_count > $length) {
            $last_word_index = $i - 1;
            break;
        }
    }

    $output_array = array_slice($words_array, 0, $last_word_index);
    $output_string = implode(' ', $output_array) . '...';
    return $output_string;
}

/**
 * если текст больше length обрезает его и прибавляет ссылку Читать далее, выводит текст в p
 * @param string $text строка
 * @param int $length допустимая длинна
 * @param string $full_content_link ссылка на полную версию поста
 * @return string html
 */
function short_content(
    string $text,
    int $length = 300,
    string $full_content_link = '#'
): string {
    $output = is_need_trunc($text, $length) ? '<p>' . trunc_text(
            $text,
            $length
        ) . '</p><a class="post-text__more-link" href=' . $full_content_link . '>Читать далее</a>' : '<p>' . $text . '</p>';

    return $output;
}

/**
 * Преобразовать данные из базы о посте для отображения
 * @param array $post Информация о посте
 * @return array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string }
 */
function format_post_data(array $post): array
{
    $content = $post['text'];

    if ($post['image_url']) {
        $content = $post['image_url'];
    }
    if ($post['video_url']) {
        $content = $post['video_url'];
    }
    if ($post['url']) {
        $content = $post['url'];
    }

    return [
        'id' => $post['id'],
        'title' => $post['title'],
        'author_quote' => $post['author_quote'],
        'type' => $post['type'],
        'content' => $content,
        'user_name' => $post['user_name'],
        'avatar' => $post['avatar']
    ];
}

function has_file($file_name)
{
    return isset($_FILES[$file_name]) && $_FILES['userpic-file-photo']['error'] != UPLOAD_ERR_NO_FILE_ID;
}

/**
 * Сохраняет файл на сервер
 * @param  $file Информация о посте
 * @return string - путь файла на сервере
 */
function save_photo_to_server($file): string
{
    $file_name = $file['name'];
    $file_path = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
    $file_url = '/uploads/' . $file_name;

    move_uploaded_file($file['tmp_name'], $file_path . $file_name);

    return $file_url;
}

