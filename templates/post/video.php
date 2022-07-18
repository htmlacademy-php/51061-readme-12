<?php
/**
 * @var $content stringz
 * @var $title string
 * @var $id string
 * @var $full_mode bool
 */

?>

<?php if (isset($full_mode)): ?>
    <div class="post-details__image-wrapper post-photo__image-wrapper">
        <?= embed_youtube_video($content); ?>
    </div>

<?php else: ?>
    <div class="post-video__block">
        <div class="post-video__preview">
            <? if (htmlspecialchars($content)) : ?>
                <?= embed_youtube_cover(htmlspecialchars($content)); ?>
            <? else : ?>
                <img src="img/coast-medium.jpg" alt="Превью к видео" width="360"
                     height="188">
            <? endif; ?>
        </div>
        <a href="./post.php?id=<?= $id ?>" class="post-video__play-big button">
            <svg class="post-video__play-big-icon" width="14" height="14">
                <use xlink:href="#icon-video-play-big"></use>
            </svg>
            <span class="visually-hidden">Запустить проигрыватель</span>
        </a>
    </div>
<?php endif; ?>
