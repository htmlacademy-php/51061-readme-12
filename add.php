<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name mysqli
 * @var $is_auth bool
 */
require_once('bootstrap.php');
require_once('helpers/validate-functions.php');

$title = 'readme: добавление публикации';

$current_post_type = 'photo';
$add_post = true;
$post_types = get_post_types($con);
$post_types_ids = [];

function get_post_val($name)
{
    return $_POST[$name] ?? '';
}

if (isset($_GET['type'])) {
    $current_post_type = mysqli_real_escape_string($con, $_GET['type']);
}


foreach ($post_types as $type) {
    $post_types_ids[$type['icon_class']] = $type['id'];
}

$forms_fields_rules = [
    'heading' => 'validate_heading',
    'photo-url' => function ($value) {
        if (has_file('userpic-file-photo')) {
            return;
        }
        return validate_image_url($value);
    },
    'userpic-file-photo' => function ($value) {
        return validate_image($value);
    },
    'tags' => 'validate_hashtag',
    'text' => $current_post_type === 'text' ? 'validate_post_text' : 'validate_quote',
    'video-url' => 'validate_youtube_url',
    'author_quote' => 'validate_quote_author',
    'url' => 'validate_url'
];

$forms_config_by_type = [
    'photo' => ['heading', 'photo-url', 'tags'],
    'video' => ['heading', 'video-url', 'tags'],
    'text' => ['heading', 'text', 'tags'],
    'quote' => ['heading', 'text', 'author_quote', 'tags'],
    'link' => ['heading', 'url', 'tags']
];

$form_fields = [];
$errors = [];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($forms_config_by_type[$current_post_type] as $field) {
        $field_data = null;
        if (isset($_POST[$field])) {
            $field_data = $_POST[$field];
        }
        if (isset($_FILES[$field])) {
            $field_data = $_FILES[$field];
        }

        $validation = $forms_fields_rules[$field] ?? false;
        if ($validation) {
            $field_error = $forms_fields_rules[$field]($field_data);
        } else {
            $field_error = 'правило не задано';
        }
        if ($field_error) {
            $errors[$field] = $field_error;
        }
    }
    if (empty($errors)) {
        $new_post_id = null;

        $post = [
            'title' => $_POST['heading'],
            'content_type_id' => $post_types_ids['post-' . $current_post_type],
            'author_id' => '1',
        ];

        switch ($current_post_type) {
            case 'photo':
                if (has_file('userpic-file-photo')) {
                    $savedFileUrl = save_photo_to_server(
                        $_FILES['userpic-file-photo']
                    );
                    $post['image_url'] = $savedFileUrl;
                } else {
                    $post['image_url'] = $_POST['photo-url'];
                }
                break;
            case 'video':
                $post['video_url'] = $_POST['video-url'];
                break;
            case 'text':
                $post['text'] = $_POST['text'];
                break;
            case 'link':
                $post['url'] = $_POST['url'];
                break;
            case 'quote':
                $post['text'] = $_POST['text'];
                $post['author_quote'] = $_POST['author_quote'];
                break;
        }

        $new_post_id = save_post($con, $post);

        if ($new_post_id && isset($_POST['tags'])) {
            $hashtags = explode(' ', $_POST['tags']);

            foreach ($hashtags as $hashtag) {
                $tag_id = get_tag_id($con, $hashtag);
                if (!$tag_id) {
                    $tag_id = saveTag($con, $hashtag);
                }
                add_tag_to_post($con, $tag_id, $new_post_id);
            }
        }
        header('Location: /post.php?id=' . $new_post_id);
    }
}


foreach ($forms_config_by_type[$current_post_type] as $field) {
    $value = get_post_val($field);
    $error = !empty($errors) && isset($errors[$field]) ? $errors[$field] : null;

    $field_template = include_template(
        'form/' . $field . '.php',
        compact(
            'current_post_type',
            'value',
            'error'
        )
    );
    array_push($form_fields, $field_template);
}


$content = include_template(
    'adding-post.php',
    compact(
        'current_time',
        'post_types',
        'current_post_type',
        'errors',
        'form_fields'
    )
);
$page = include_template(
    'layout.php',
    compact(
        'content',
        'title',
        'is_auth',
        'user_name',
        'add_post'
    )
);

print($page);

