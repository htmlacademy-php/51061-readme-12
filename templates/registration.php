<?php
/**
 * @var $errors array
 * @var $values array
 * @var $fields array
 * @var $fields_config array
 */

?>
<main class="page__main page__main--registration">
    <div class="container">
        <h1 class="page__title page__title--registration">Регистрация</h1>
    </div>
    <section class="registration container">
        <h2 class="visually-hidden">Форма регистрации</h2>
        <form class="registration__form form" action="#" method="post"
              enctype="multipart/form-data">

            <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                    <?php foreach ($fields as $field) {
                        print($field);
                    }; ?>
                </div>
                <?php if (!empty($errors)) : ?>
                    <div class="form__invalid-block">
                        <b class="form__invalid-slogan">Пожалуйста, исправьте
                            следующие ошибки:</b>

                        <ul class="form__invalid-list">
                            <?php foreach ($errors as $name => $value) : ?>
                                <li class="form__invalid-item"><?= $fields_config[$name]['title'] ?>
                                    . <?= $value ?>.
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <button class="registration__submit button button--main"
                    type="submit">Отправить
            </button>
        </form>
    </section>
</main>
