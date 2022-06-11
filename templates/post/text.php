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
<?php print(short_content($safe_content)) ?>
