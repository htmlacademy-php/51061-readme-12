<?php /** @noinspection ALL */

/** @noinspection ALL */

/**
 * @var $error string
 * @var $current_post_type string
 * @var $value mixed
 */

?>

<div
        class="adding-post__input-wrapper form__input-wrapper">
    <label
            class="adding-post__label form__label"
            for="heading">Заголовок
        <span
                class="form__input-required">*</span></label>
    <div
            class="form__input-section <?= $error ? 'form__input-section--error' : '' ?>">
        <input
                class="adding-post__input form__input"
                id="heading" type="text"
                name="heading"
                value="<?= $value ?>"
                placeholder="Введите заголовок">
        <button
                class="form__error-button button"
                type="button">!<span
                    class="visually-hidden">Информация об ошибке</span>
        </button>

        <? if (!empty($error)) : ?>
            <div class="form__error-text">
                <h3 class="form__error-title">
                    Заголовок сообщения</h3>
                <p class="form__error-desc">
                    <?= $error ?></p>
            </div>
        <? endif; ?>
    </div>
</div>
