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
<!--содержимое для поста-видео-->
<div class="post-video__block">
    <div class="post-video__preview">
        <? if ($safe_content) : ?>
            <?= embed_youtube_cover($content); ?>
        <? else : ?>
            <img src="img/coast-medium.jpg" alt="Превью к видео" width="360"
                 height="188">
        <? endif; ?>
    </div>
    <a href="post-details.html" class="post-video__play-big button">
        <svg class="post-video__play-big-icon" width="14" height="14">
            <use xlink:href="#icon-video-play-big"></use>
        </svg>
        <span class="visually-hidden">Запустить проигрыватель</span>
    </a>
</div>
