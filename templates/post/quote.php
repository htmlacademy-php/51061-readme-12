<?php

/**
 * @var $content string
 * @var $author string
 */

?>

<div class="post-quote__wrapper">
    <blockquote>
        <p>
            <?= htmlspecialchars($content) ?>
        </p>
        <cite><?= htmlspecialchars($author) ?></cite>
    </blockquote>
</div>
