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
<!--содержимое для поста-фото-->
<div class="post-photo__image-wrapper">
    <img src="<?= $safe_content ?>" alt="Фото от пользователя" width="360"
         height="240">
</div>
