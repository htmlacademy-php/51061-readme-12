<?php

/**
 * @var $content string
 * @var $title string
 * @var $id string
 */

?>
<!--содержимое для поста-ссылки-->
<div class="post-link__wrapper">
    <a class="post-link__external" href="<?= htmlspecialchars($content) ?>"
       title="Перейти по ссылке">
        <div class="post-link__info-wrapper">
            <div class="post-link__icon-wrapper">
                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                     alt="Иконка">
            </div>
            <div class="post-link__info">
                <h3><?= htmlspecialchars($title) ?></h3>
                <span><?= htmlspecialchars($content) ?></span>
            </div>
        </div>
    </a>
</div>
