<?php
/**
 * @var $posts array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string }
 * @var $post_types array{icon_class:string ,title:string}
 * @var $current_time int
 * @var $current_post_type string
 * @var $errors array
 */

function getPostVal($name)
{
    return $_POST[$name] ?? "";
}

$errorFieldTitles = [
    'heading' => 'Заголовок',
    'tags' => 'Теги',
    'photo-url' => 'Ссылка из интернета',
    'userpic-file-photo' => 'Изображение',
    'video-url' => 'Ссылка Youtube',
    'post-text' => 'Текст поста',
    'quote-text' => 'Текст цитаты',
    'quote-author' => 'Автор цитаты',
    'post-link' => 'Ссылка'
];

?>
<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить
                публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                    <ul class="adding-post__tabs-list filters__list tabs__list">
                        <? foreach ($post_types as $key => $type): ?>
                            <? $type_name = explode(
                                '-',
                                $type['icon_class']
                            )[1] ?>
                            <? $type_param = '?type=' . $type_name ?>

                            <? $isActive = $current_post_type == $type_name ?>
                            <li class="adding-post__tabs-item filters__item">
                                <a class="adding-post__tabs-link filters__button filters__button--<?= $type_name ?> filters__button<?= $isActive ? '--active' : '' ?> tabs__item tabs__item<?= $isActive ? '--active' : '' ?> button"
                                   href="add.php<?= $type_param ?>">
                                    <svg class="filters__icon" width="22"
                                         height="18">
                                        <use
                                            xlink:href="#icon-filter-<?= $type_name ?>"></use>
                                    </svg>
                                    <span><?= $type['title'] ?></span>
                                </a>
                            </li>
                        <? endforeach; ?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <section
                        class="adding-post__photo tabs__content <?= $current_post_type === 'photo' ? 'tabs__content--active' : '' ?>">
                        <h2 class="visually-hidden">Форма добавления фото</h2>
                        <form class="adding-post__form form" action="/add.php"
                              method="post"
                              enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="photo-heading">Заголовок
                                            <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="photo-heading" type="text"
                                                name="heading"
                                                value="<?= getPostVal(
                                                    'heading'
                                                ); ?>"
                                                placeholder="Введите заголовок">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>

                                            <? if (!empty($errors) && isset($errors['heading'])) : ?>
                                                <div class="form__error-text">
                                                    <h3 class="form__error-title">
                                                        Заголовок сообщения</h3>
                                                    <p class="form__error-desc">
                                                        <?= $errors['heading'] ?></p>
                                                </div>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="photo-url">Ссылка из
                                            интернета</label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="photo-url" type="text"
                                                name="photo-url"
                                                value="<?= getPostVal(
                                                    'photo-url'
                                                ); ?>"
                                                placeholder="Введите ссылку">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <? if (!empty($errors) && isset($errors['photo-url'])) : ?>
                                                <div class="form__error-text">
                                                    <h3 class="form__error-title">
                                                        Ссылка из
                                                        интернета</h3>
                                                    <p class="form__error-desc">
                                                        <?= $errors['photo-url'] ?></p>
                                                </div>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="photo-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="photo-tags" type="text"
                                                name="tags"
                                                value="<?= getPostVal(
                                                    'tags'
                                                ); ?>"
                                                placeholder="Введите теги">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <? if (!empty($errors) && isset($errors['photo-url'])) : ?>
                                                <div class="form__error-text">
                                                    <h3 class="form__error-title">
                                                        Теги</h3>
                                                    <p class="form__error-desc">
                                                        <?= $errors['tags'] ?></p>
                                                </div>
                                            <? endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <? if (!empty($errors)) : ?>

                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста,
                                            исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $key => $value) : ?>
                                                <li class="form__invalid-item">
                                                    <?= $errorFieldTitles[$key] ?>
                                                    .<?= $value ?>
                                                </li>
                                            <? endforeach; ?>
                                        </ul>
                                    </div>
                                <? endif; ?>
                            </div>
                            <div
                                class="adding-post__input-file-container form__input-container form__input-container--file">
                                <div
                                    class="adding-post__input-file-wrapper form__input-file-wrapper">
                                    <div
                                        class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                        <!--                                        class="adding-post__input-file form__input-file"-->
                                        <input

                                            id="userpic-file-photo"
                                            type="file"
                                            name="userpic-file-photo" title=" ">
                                        <div class="form__file-zone-text">
                                            <span>Перетащите фото сюда</span>
                                        </div>
                                    </div>
                                    <button
                                        class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button"
                                        type="button">
                                        <span>Выбрать фото</span>
                                        <svg
                                            class="adding-post__attach-icon form__attach-icon"
                                            width="10" height="20">
                                            <use
                                                xlink:href="#icon-attach"></use>
                                        </svg>
                                    </button>
                                </div>
                                <div
                                    class="adding-post__file adding-post__file--photo form__file dropzone-previews">

                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button
                                    class="adding-post__submit button button--main"
                                    type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close"
                                   href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section
                        class="adding-post__video tabs__content <?= $current_post_type === 'video' ? 'tabs__content--active' : '' ?>">
                        <h2 class="visually-hidden">Форма добавления видео</h2>
                        <form class="adding-post__form form"
                              action="add.php" method="post"
                              enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="video-heading">Заголовок
                                            <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="video-heading" type="text"
                                                name="video-heading"
                                                placeholder="Введите заголовок">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="video-url">Ссылка youtube
                                            <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="video-url" type="text"
                                                name="video-url"
                                                placeholder="Введите ссылку">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="video-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="video-tags" type="text"
                                                name="photo-heading"
                                                placeholder="Введите ссылку">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста,
                                        исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">
                                            Заголовок. Это поле должно быть
                                            заполнено.
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="adding-post__buttons">
                                <button
                                    class="adding-post__submit button button--main"
                                    type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close"
                                   href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section
                        class="adding-post__text tabs__content  <?= $current_post_type === 'text' ? 'tabs__content--active' : '' ?>">
                        <h2 class="visually-hidden">Форма добавления текста</h2>
                        <form class="adding-post__form form"
                              action="add.php" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="text-heading">Заголовок <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="text-heading" type="text"
                                                name="text-heading"
                                                placeholder="Введите заголовок">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__textarea-wrapper form__textarea-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="post-text">Текст поста <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea
                                                class="adding-post__textarea form__textarea form__input"
                                                id="post-text"
                                                placeholder="Введите текст публикации"></textarea>
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="post-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="post-tags" type="text"
                                                name="photo-heading"
                                                placeholder="Введите теги">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста,
                                        исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">
                                            Заголовок. Это поле должно быть
                                            заполнено.
                                        </li>
                                        <li class="form__invalid-item">Цитата.
                                            Она не должна превышать 70 знаков.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button
                                    class="adding-post__submit button button--main"
                                    type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close"
                                   href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section
                        class="adding-post__quote tabs__content  <?= $current_post_type === 'quote' ? 'tabs__content--active' : '' ?>">
                        <h2 class="visually-hidden">Форма добавления цитаты</h2>
                        <form class="adding-post__form form"
                              action="add.php" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="quote-heading">Заголовок
                                            <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="quote-heading" type="text"
                                                name="quote-heading"
                                                placeholder="Введите заголовок">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__textarea-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="cite-text">Текст цитаты <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <textarea
                                                class="adding-post__textarea adding-post__textarea--quote form__textarea form__input"
                                                id="cite-text"
                                                placeholder="Текст цитаты"></textarea>
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="quote-author">Автор <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="quote-author" type="text"
                                                name="quote-author">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="cite-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="cite-tags" type="text"
                                                name="photo-heading"
                                                placeholder="Введите теги">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста,
                                        исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">
                                            Заголовок. Это поле должно быть
                                            заполнено.
                                        </li>
                                        <li class="form__invalid-item">Цитата.
                                            Она не должна превышать 70 знаков.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button
                                    class="adding-post__submit button button--main"
                                    type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close"
                                   href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>

                    <section
                        class="adding-post__link tabs__content  <?= $current_post_type === 'link' ? 'tabs__content--active' : '' ?>">
                        <h2 class="visually-hidden">Форма добавления ссылки</h2>
                        <form class="adding-post__form form"
                              action="add.php" method="post">
                            <div class="form__text-inputs-wrapper">
                                <div class="form__text-inputs">
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="link-heading">Заголовок <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="link-heading" type="text"
                                                name="link-heading"
                                                placeholder="Введите заголовок">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__textarea-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="post-link">Ссылка <span
                                                class="form__input-required">*</span></label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="post-link" type="text"
                                                name="post-link">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="adding-post__input-wrapper form__input-wrapper">
                                        <label
                                            class="adding-post__label form__label"
                                            for="link-tags">Теги</label>
                                        <div class="form__input-section">
                                            <input
                                                class="adding-post__input form__input"
                                                id="link-tags" type="text"
                                                name="photo-heading"
                                                placeholder="Введите ссылку">
                                            <button
                                                class="form__error-button button"
                                                type="button">!<span
                                                    class="visually-hidden">Информация об ошибке</span>
                                            </button>
                                            <div class="form__error-text">
                                                <h3 class="form__error-title">
                                                    Заголовок сообщения</h3>
                                                <p class="form__error-desc">
                                                    Текст сообщения об ошибке,
                                                    подробно
                                                    объясняющий, что не так.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form__invalid-block">
                                    <b class="form__invalid-slogan">Пожалуйста,
                                        исправьте следующие ошибки:</b>
                                    <ul class="form__invalid-list">
                                        <li class="form__invalid-item">
                                            Заголовок. Это поле должно быть
                                            заполнено.
                                        </li>
                                        <li class="form__invalid-item">Цитата.
                                            Она не должна превышать 70 знаков.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="adding-post__buttons">
                                <button
                                    class="adding-post__submit button button--main"
                                    type="submit">Опубликовать
                                </button>
                                <a class="adding-post__close"
                                   href="#">Закрыть</a>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
