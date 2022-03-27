<?php /** @noinspection ALL */


/**
 * @var $content string
 */

?>
<!--содержимое для поста-видео-->
<div class="post-video__block">
    <div class="post-video__preview">
        <? if ($content) : ?>
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
