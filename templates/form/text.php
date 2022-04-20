<?php /** @noinspection ALL */


/**
 * @var $error string
 * @var $current_post_type string
 * @var $value mixed
 */

$title = $current_post_type === 'quote' ? 'Текст цитаты' : 'Текст поста'

?>
<div
        class="adding-post__textarea-wrapper form__textarea-wrapper">
    <label
            class="adding-post__label form__label"
            for="post-text"> <?= $title ?><span
                class="form__input-required">*</span></label>
    <div
            class="form__input-section <?= $error ? 'form__input-section--error' : '' ?>">
        <textarea
                class="adding-post__textarea form__textarea form__input"
                id="post-text"
                name="text"
                placeholder="Введите текст публикации"><?= $value ?></textarea>
        <button
                class="form__error-button button"
                type="button">!<span
                    class="visually-hidden">Информация об ошибке</span>
        </button>
        <? if (!empty($error)) : ?>
            <div class="form__error-text">
                <h3 class="form__error-title">
                    <?= $title ?></h3>
                <p class="form__error-desc">
                    <?= $error ?></p>
            </div>
        <? endif; ?>
    </div>
</div>
