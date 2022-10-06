<?php

require_once('helpers/helpers.php');

/**
 * @var $posts array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string }
 * @var $post_types array{icon_class:string ,title:string}
 * @var $current_time int
 * @var $current_post_type string
 * @var $referer string
 * @var $errors array
 * @var $form_fields array
 */


$error_field_titles = [
    'heading' => 'Заголовок',
    'tags' => 'Теги',
    'photo-url' => 'Ссылка из интернета',
    'userpic-file-photo' => 'Изображение',
    'video-url' => 'Ссылка Youtube',
    'text' => $current_post_type === 'text' ? 'Текст поста' : 'Текст цитаты',
    'author_quote' => 'Автор цитаты',
    'url' => 'Ссылка'
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
                        <?php foreach ($post_types as $key => $type): ?>
                            <?php $type_name = explode(
                                '-',
                                $type['icon_class']
                            )[1] ?>
                            <?php $type_param = '?type=' . $type_name ?>

                            <?php $is_active = $current_post_type == $type_name ?>
                            <li class="adding-post__tabs-item filters__item">
                                <a class="adding-post__tabs-link filters__button filters__button--<?= $type_name ?> filters__button<?= $is_active ? '--active' : '' ?> tabs__item tabs__item<?= $is_active ? '--active' : '' ?> button"
                                   href="add.php<?= $type_param ?>">
                                    <svg class="filters__icon" width="22"
                                         height="18">
                                        <use
                                                xlink:href="#icon-filter-<?= $type_name ?>"></use>
                                    </svg>
                                    <span><?= $type['title'] ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="adding-post__tab-content">
                    <section
                            class="adding-post__<?= $current_post_type ?> tabs__content tabs__content--active">
                        <h2>Форма
                            добавления</h2>
                        <form class="adding-post__form form"
                              action="/add.php?type=<?= $current_post_type ?>"
                              method="post"
                              enctype="multipart/form-data">
                            <div class="form__text-inputs-wrapper">

                                <div class="form__text-inputs">
                                    <?php foreach ($form_fields as $form_field): ?>
                                        <?php print($form_field) ?>
                                    <?php endforeach; ?>
                                    <?php if ($current_post_type === 'photo') : ?>
                                        <div
                                                class="adding-post__input-file-container form__input-container form__input-container--file">
                                            <div
                                                    class="adding-post__input-file-wrapper form__input-file-wrapper">
                                                <label
                                                        class="adding-post__label form__label"
                                                        for="video-url">Загрузите
                                                    фото</label>

                                                <input
                                                        class="adding-post__input form__input"
                                                        id="userpic-file-photo"
                                                        type="file"
                                                        name="userpic-file-photo"
                                                        title=" ">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($errors)) : ?>
                                    <div class="form__invalid-block">
                                        <b class="form__invalid-slogan">Пожалуйста,
                                            исправьте следующие ошибки:</b>
                                        <ul class="form__invalid-list">
                                            <?php foreach ($errors as $key => $value) : ?>
                                                <li class="form__invalid-item">
                                                    <?= $error_field_titles[$key] ?>
                                                    .<?= $value ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>

                </div>

                <div class="adding-post__buttons">
                    <button
                            class="adding-post__submit button button--main"
                            type="submit">Опубликовать
                    </button>
                    <a class="adding-post__close"
                       href="<?= $referer ?>">Закрыть</a>
                </div>
                </form>
                </section>

            </div>
        </div>
    </div>
    </div>
</main>

