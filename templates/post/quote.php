<?php

/**
 * @var $content string
 * @var $title string
 * @var $id string
 */
$safe_content = htmlspecialchars($content);
?>
<blockquote>
    <p>
        <?= $safe_content ?>
    </p>
    <cite>Неизвестный Автор</cite>
</blockquote>
