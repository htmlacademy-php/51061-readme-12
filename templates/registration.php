<?php
/**
 * @var $errors array
 * @var $values array
 * @var $fields array
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
                            <?php foreach ($errors as $title => $value) : ?>
                                <li class="form__invalid-item"><?= $title ?>
                                    . <?= $value ?>.
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div
                    class="registration__input-file-container form__input-container form__input-container--file">
                <div
                        class="registration__input-file-wrapper form__input-file-wrapper">
                    <div
                            class="registration__file-zone form__file-zone dropzone">
                        <input class="registration__input-file form__input-file"
                               id="userpic-file" type="file" name="userpic-file"
                               title=" ">
                        <div class="form__file-zone-text">
                            <span>Перетащите фото сюда</span>
                        </div>
                    </div>
                    <button
                            class="registration__input-file-button form__input-file-button button"
                            type="button">
                        <span>Выбрать фото</span>
                        <svg class="registration__attach-icon form__attach-icon"
                             width="10" height="20">
                            <use xlink:href="#icon-attach"></use>
                        </svg>
                    </button>
                </div>
                <div class="registration__file form__file dropzone-previews">

                </div>
            </div>
            <button class="registration__submit button button--main"
                    type="submit">Отправить
            </button>
        </form>
    </section>
</main>
