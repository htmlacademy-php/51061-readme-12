<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name string
 * @var $is_auth bool
 */
require_once('bootstrap.php');
require_once('helpers/validate-functions.php');
require_once('helpers/helpers.php');

$title = 'readme: регистрация';
$is_auth = false;

$fields_config = [
    'email' => [
        'title' => 'Электронная почта',
        'placeholder' => 'Укажите эл.почту',
        'label' => 'email',
        'type' => 'email',
        'validation' => 'validate_email'
    ],
    'login' => [
        'title' => 'Логин',
        'placeholder' => 'Укажите логин',
        'label' => 'login',
        'type' => 'text',
        'validation' => 'validate_login'
    ],
    'password' => [
        'title' => 'Пароль',
        'placeholder' => 'Придумайте пароль',
        'label' => 'password',
        'type' => 'password',
        'validation' => 'validate_password'
    ],
    'password-repeat' => [
        'title' => 'Повтор пароля',
        'placeholder' => 'Повторите пароль',
        'label' => 'password-repeat',
        'type' => 'password',
        'validation' => function ($value) {
            return validate_repeat_password(
                get_post_val('password'),
                $value
            );
        }
    ],
    'userpic-file' => [
        'title' => 'Выберите фото',
        'placeholder' => 'Загрузите фото',
        'label' => 'userpic-file',
        'type' => 'file',
        'validation' => 'validate_image'
    ],
];


$forms_fields_keys = array_keys($fields_config);
$errors = [];
$values = [];
$fields = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($forms_fields_keys as $field_key) {
        $values[$field_key] = $field_key == 'userpic-file' ? ($_FILES[$field_key] ?? '') : get_post_val(
            $field_key
        );
        $field_error = $fields_config[$field_key]['validation'](
            $values[$field_key]
        );
        if ($field_error) {
            $errors[$field_key] = $field_error;
        }
    }

    if ($values['email'] && !(isset($errors['email']))) {
        $is_available = is_email_or_login_available(
            $con,
            ['email' => $values['email']]
        );
        if (!$is_available) {
            $errors['email'] = 'Данный имейл зарегестрирован';
        }
    }
    if ($values['login'] && !(isset($errors['login']))) {
        $is_available = is_email_or_login_available(
            $con,
            ['login' => $values['login']]
        );
        if (!$is_available) {
            $errors['login'] = 'Данный логин зарегистрирован';
        }
    }
    if (empty($errors)) {
        $user_data = [
            'email' => $values['email'],
            'login' => $values['login'],
            'password' => password_hash($values['password'], PASSWORD_DEFAULT)
        ];

        if (has_file('userpic-file')) {
            var_dump($values['userpic-file']);
            $user_data['avatar_url'] = save_photo_to_server(
                $_FILES['userpic-file']
            );
        };

        $user_id = create_user($con, $user_data);
        if ($user_id) {
            $user_data['id'] = $user_id;
            $_SESSION['user'] = $user_data;
            header('Location: /feed.php');
        }
    }
}

foreach ($fields_config as $key => $field) {
    $template = include_template('form/input-field.php', [
        'class' => 'registration',
        'title' => $field['title'],
        'placeholder' => $field['placeholder'],
        'label' => $field['label'],
        'type' => $field['type'],
        'value' => get_post_val($key),
        'error' => $errors[$key] ?? ''
    ]);

    $fields[] = $template;
};

$content = include_template(
    'registration.php',
    [
        'errors' => $errors,
        'values' => $values,
        'fields' => $fields,
        'fields_config' => $fields_config
    ]
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name')
);

echo($page);
