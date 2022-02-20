<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name mysqli
 * @var $is_auth boolean
 */
require_once('bootstrap.php');
require_once('helpers/validate-functions.php');

$title = 'readme: добавление публикации';

$current_post_type = 'photo';
$add_post = true;

if (isset($_GET['type'])) {
    $current_post_type = mysqli_real_escape_string($con, $_GET['type']);
    print($current_post_type);
}

//Отправьте SQL-запрос для получения типов контента
$post_types = get_post_types($con);


$forms_fields_rules = [
    'photo' => [
        'heading' => 'validate_heading',
        'photo-url' => function ($value) {
//            if (validate_attached_image())
            return validate_url($value, false);
        },
        'tags' => 'validate_hashtag',
        'userpic-file-photo' => function ($value) {
            var_dump($value);
        },
    ]
];

$errors = [];


$current_form_fields = $forms_fields_rules[$current_post_type];
foreach ($current_form_fields as $field => $validation) {
    $field_data = null;
    if (isset($_POST[$field])) {
        $field_data = $_POST[$field];
    }
    if (isset($_FILES[$field])) {
        $field_data = $_FILES[$field];
    }

    $field_error = $validation($field_data);
    if ($field_error) {
        $errors[$field] = $field_error;
    }
}

$content = include_template(
    'adding-post.php',
    compact(
        "current_time",
        "post_types",
        'current_post_type',
        'errors'
    )
);
$page = include_template(
    "layout.php",
    compact(
        "content",
        "title",
        "is_auth",
        "user_name",
        'add_post'
    )
);


print($page);
?>

