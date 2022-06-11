<?php

/**
 * @var $content string
 * @var $title string
 * @var $id string
 */
$safe_content = htmlspecialchars($content);
$safe_title = htmlspecialchars($title);
?>

<h2><a href="/post.php?id=<?= htmlspecialchars($id) ?>"><?= $safe_title ?></a>
</h2>
<!--содержимое для поста-ссылки-->
<div class="post-link__wrapper">
    <a class="post-link__external" href="<?= $safe_content ?>"
       title="Перейти по ссылке">
        <div class="post-link__info-wrapper">
            <div class="post-link__icon-wrapper">
                <img src="https://www.google.com/s2/favicons?domain=vitadental.ru"
                     alt="Иконка">
            </div>
            <div class="post-link__info">
                <h3><?= $safe_title ?></h3>
                <span><?= $safe_content ?></span>
            </div>
        </div>
    </a>
</div>
