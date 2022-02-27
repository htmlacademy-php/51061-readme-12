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
$post_types_ids = [];

foreach ($post_types as $type) {
    $post_types_ids[$type['icon_class']] = $type['id'];
}

$forms_fields_rules = [
    'photo' => [
        'heading' => 'validate_heading',
        'photo-url' => function ($value) {
            if (has_file('userpic-file-photo')) {
                return;
            }
            return validate_image_url($value);
        },
        'tags' => 'validate_hashtag',
        'userpic-file-photo' => function ($value) {
            return validate_image($value);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($errors)) {
    $new_post_id = null;

    switch ($current_post_type) {
        case 'photo':
            $post = [
                'title' => $_POST['heading'],
                'content_type_id' => $post_types_ids['post-photo'],
                'author_id' => '1',
            ];
            if (has_file('userpic-file-photo')) {
                $savedFileUrl = save_photo_to_server(
                    $_FILES['userpic-file-photo']
                );
                $post['image_url'] = $savedFileUrl;
            } else {
                $post['image_url'] = $_POST['photo-url'];
            }
            $new_post_id = save_post_photo($con, $post);
            break;
    }

    if ($new_post_id) {
        header("Location: /post.php?id=" . $new_post_id);
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

