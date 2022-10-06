<?php /** @noinspection ALL */


/** @noinspection PhpShortOpenTagInspection */

/** @noinspection PhpShortOpenTagInspection */

/**
 * @var $error string
 * @var $current_post_type string
 * @var $value mixed
 */

?>
<div
        class="adding-post__textarea-wrapper form__input-wrapper">
    <label
            class="adding-post__label form__label"
            for="author_quote">Автор <span
                class="form__input-required">*</span></label>
    <div
            class="form__input-section  <?= $error ? 'form__input-section--error' : '' ?>"
    ">
    <input
            class="adding-post__input form__input"
            id="author_quote" type="text"
            value="<?= htmlspecialchars($value) ?>"
            name="author_quote">
    <button
            class="form__error-button button"
            type="button">!<span
                class="visually-hidden">Информация об ошибке</span>
    </button>
    <? if (!empty($error)) : ?>
        <div class="form__error-text">
            <h3 class="form__error-title">
                Автор</h3>
            <p class="form__error-desc">
                <?= $error ?></p>
        </div>
    <? endif; ?>
</div>
</div>
