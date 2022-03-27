<?php /** @noinspection ALL */


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
            for="photo-url">Ссылка из
        интернета</label>
    <div
            class="form__input-section <?= $error ? 'form__input-section--error' : '' ?>"
    ">
    <input
            class="adding-post__input form__input"
            id="photo-url" type="text"
            name="photo-url"
            value="<?= $value ?>"
            placeholder="Введите ссылку">
    <button
            class="form__error-button button"
            type="button">!<span
                class="visually-hidden">Информация об ошибке</span>
    </button>
    <? if (!empty($error)) : ?>
        <div class="form__error-text">
            <h3 class="form__error-title">
                Ссылка из
                интернета</h3>
            <p class="form__error-desc">
                <?= $error ?></p>
        </div>
    <? endif; ?>
</div>
</div>
