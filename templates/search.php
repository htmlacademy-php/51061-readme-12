<?php
/**
 * @var $current_time string
 * @var $search_text string
 * @var $posts array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string, created_at:string }
 */

?>
<main class="page__main page__main--search-results">
    <h1 class="visually-hidden">Страница результатов поиска</h1>
    <section class="search">
        <h2 class="visually-hidden">Результаты поиска</h2>
        <div class="search__query-wrapper">
            <div class="search__query container">
                <span>Вы искали:</span>
                <span class="search__query-text"><?= $search_text ?></span>
            </div>
        </div>
        <div class="search__results-wrapper">
            <div class="container">
                <div class="search__content">
                    <?php foreach ($posts as $key => $post): ?>
                        <article class="search__post post <?= $post['type'] ?>">
                            <header class="post__header post__author">
                                <a title="Автор" class="post__author-link"
                                   href="/profile.php?id=<?= htmlspecialchars(
                                       $post['author_id']
                                   ) ?>">
                                    <div class="post__avatar-wrapper">
                                        <!--укажите путь к файлу аватара-->
                                        <img class="post__author-avatar"
                                             src="img/<?= htmlspecialchars(
                                                 $post['avatar']
                                             ) ?>"
                                             alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= htmlspecialchars(
                                                $post['user_name']
                                            ) ?></b>
                                        <?php if ($post['created_at']) : ?>
                                            <time class="post__time"
                                                  title='<?= date_create(
                                                      $post['created_at']
                                                  )->format('d.m.Y H:i') ?>'
                                                  datetime="<?= $post['created_at'] ?>">
                                                <?php
                                                $passed_time_title = get_passed_time_title(
                                                    $post['created_at'],
                                                    $current_time
                                                );
                                                if ($passed_time_title) {
                                                    echo $passed_time_title;
                                                }
                                                ?></time>
                                        <? endif; ?>
                                    </div>

                                </a>
                            </header>
                            <div class="post__main <?= $post['type'] ?>">
                                <h2><a href="/post.php?id=<?= htmlspecialchars(
                                        $post['id']
                                    ) ?>"><?= htmlspecialchars(
                                            $post['title']
                                        ) ?></a>
                                </h2>
                                <?php print($post['template']) ?>
                            </div>
                            <footer class="post__footer">
                                <div class="post__indicators">
                                    <div class="post__buttons">
                                        <a class="post__indicator post__indicator--likes button"
                                           href="#" title="Лайк">
                                            <svg class="post__indicator-icon"
                                                 width="20" height="17">
                                                <use xlink:href="#icon-heart"></use>
                                            </svg>
                                            <svg
                                                    class="post__indicator-icon post__indicator-icon--like-active"
                                                    width="20"
                                                    height="17">
                                                <use
                                                        xlink:href="#icon-heart-active"></use>
                                            </svg>
                                            <span>0</span>
                                            <span class="visually-hidden">количество лайков</span>
                                        </a>
                                        <a class="post__indicator post__indicator--comments button"
                                           href="#"
                                           title="Комментарии">
                                            <svg class="post__indicator-icon"
                                                 width="19" height="17">
                                                <use
                                                        xlink:href="#icon-comment"></use>
                                            </svg>
                                            <span>0</span>
                                            <span class="visually-hidden">количество комментариев</span>
                                        </a>
                                    </div>
                                </div>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
</main>

