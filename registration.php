<?php

/**
 * @var $con mysqli
 * @var $current_time string
 * @var $user_name mysqli
 * @var $is_auth bool
 */
require_once('bootstrap.php');
require_once('helpers/validate-functions.php');

$title = 'readme: регистрация';

$fields_config = [
    'email' => [
        'title' => 'Электронная почта',
        'label' => 'email',
        'type' => 'email',
        'validation' => function ($value) {
            return validate_email($value);
        }
    ],
    'login' => [
        'title' => 'Логин',
        'label' => 'login',
        'type' => 'text',
        'validation' => 'validate_login'
    ],
    'password' => [
        'title' => 'Пароль',
        'label' => 'password',
        'type' => 'password',
        'validation' => 'validate_password'
    ],
    'password-repeat' => [
        'title' => 'Повтор пароля',
        'label' => 'password-repeat',
        'type' => 'password-repeat',
        'validation' => function ($value) {
            return validate_repeat_password(
                get_post_val(get_post_val('password')),
                $value
            );
        }
    ],
];


$forms_fields_keys = array_keys($fields_config);
$errors = [];
$values = [];
$fields = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($forms_fields_keys as $field_key) {
        print(get_post_val($field_key));
        $values[$field_key] = get_post_val($field_key);
        $field_error = $fields_config[$field_key]['validation'](
            $values[$field_key]
        );
        if ($field_error) {
            $errors[$field_key] = $field_error;
        }
    }
}

foreach ($fields_config as $key => $field) {
    $template = include_template('form/input-field.php', [
        'class' => 'registration',
        'title' => $field['title'],
        'label' => $field['label'],
        'type' => $field['type'],
        'value' => get_post_val($key),
        'error' => $errors[$key] ?? ''
    ]);

    $fields[] = $template;
};

$content = include_template(
    'registration.php',
    ['errors' => $errors, 'values' => $values, 'fields' => $fields]
);

$page = include_template(
    'layout.php',
    compact('content', 'title', 'is_auth', 'user_name')
);

echo($page);
