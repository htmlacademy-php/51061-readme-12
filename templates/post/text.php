<?php

/**
 * @var $content string
 * @var $title string
 * @var $id string
 * @var $full_mode bool
 */

?>

<?php if (isset($full_mode)): ?>
    <p>
        <?= htmlspecialchars($content); ?>
    </p>
<?php else: ?>
    <?php print(short_content(htmlspecialchars($content))) ?>
<?php endif; ?>
