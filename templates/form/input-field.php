<?php
/**
 * @var $title string
 * @var $class string
 * @var $label string
 * @var $type string
 * @var $value string
 * @var $error string
 * @var $placeholder string
 */

?>

<div
        class="<?= $class ?>__input-wrapper form__input-wrapper">
    <label class="<?= $class ?>__label form__label"
           for="<?= $class ?>-<?= $label ?>"><?= $title ?> <span
                class="form__input-required">*</span></label>
    <div
            class="form__input-section <?= $error ? 'form__input-section--error' : '' ?>"
    >
        <input class="<?= $class ?>__input form__input"
               id="<?= $class ?>-<?= $label ?>" type="<?= $type ?>"
               value='<?= $value ?>'
               name="<?= $label ?>" placeholder="<?= $placeholder ?>">
        <button class="form__error-button button"
                type="button">!<span
                    class="visually-hidden">Информация об ошибке</span>
        </button>
        <?php if (isset($error)) : ?>
            <div class="form__error-text">
                <h3 class="form__error-title"><?= $title ?></h3>
                <p class="form__error-desc"><?= $error ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>
