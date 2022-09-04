<?php
/**
 * @var $posts array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string }
 * @var $post_types array{icon_class:string ,title:string}
 * @var $current_time int
 * @var $current_post_type string
 * @var $prev_page_url string
 * @var $next_page_url string
 * @var $is_last_page bool
 * @var $is_first_page bool
 */

?>

<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link sorting__link--active" href="#">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип
                    контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <? $all_types_active_class = !isset($current_post_type) ? 'filters__button--active' : null ?>
                        <a class="filters__button filters__button--ellipse filters__button--all <?= $all_types_active_class ?>"
                           href="/popular.php">
                            <span>Все</span>
                        </a>
                    </li>
                    <? foreach ($post_types as $key => $type): ?>
                        <? $type_name = explode('-', $type['icon_class'])[1] ?>
                        <? $link = '?page=1&type=' . $type['icon_class'] ?>
                        <? $active_class = $current_post_type == $type_name ? 'filters__button--active' : ''; ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--photo button <?= $active_class ?>"
                               href="<?= $link ?>">
                                <span class="visually-hidden">Фото</span>
                                <svg class="filters__icon" width="22"
                                     height="18">
                                    <use xlink:href="#icon-filter-<?= $type_name ?>"></use>
                                </svg>
                            </a>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php if (!empty($posts)) : ?>
                <?php foreach ($posts as $key => $post): ?>
                    <article class="popular__post post <?= $post['type'] ?>">
                        <header class="post__header">
                            <h2><a href="/post.php?id=<?= htmlspecialchars(
                                    $post['id']
                                ) ?>"><?= htmlspecialchars(
                                        $post['title']
                                    ) ?></a>
                            </h2>
                        </header>
                        <div class="post__main">
                            <?php print($post['template']) ?>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link"
                                   href="./profile.php?id=<?= $post['author_id'] ?>"
                                   title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <!--укажите путь к файлу аватара-->
                                        <img class="post__author-avatar"
                                             src="<?= $post['avatar'] ?? 'img/anonymous.png' ?>"
                                             alt="Аватар пользователя">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?= htmlspecialchars(
                                                $post['user_name']
                                            ) ?></b>

                                        <time class="post__time"
                                              title='<?= date_create(
                                                  $post['created_at']
                                              )->format('d.m.Y H:i') ?>'
                                              datetime="<?= $post['created_at'] ?>">
                                            <?= get_passed_time_title(
                                                $post['created_at']
                                            ) ?>
                                        </time>
                                    </div>
                                </a>
                            </div>
                            <div class="post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button"
                                       href="/like.php?post_id=<?= $post['id'] ?>"
                                       title="Лайк">
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
                                        <span><?= $post['likes_count'] ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button"
                                       href="/post.php?id=<?= $post['id'] ?>"
                                       title="Комментарии">
                                        <svg class="post__indicator-icon"
                                             width="19" height="17">
                                            <use
                                                    xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= $post['comments_count'] ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                </div>
                            </div>
                        </footer>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <h2>Публикаций не найдено</h2>
            <?php endif; ?>
        </div>
        <div class="popular__page-links">
            <?php if (!$is_first_page) : ?>
                <a class="popular__page-link popular__page-link--prev button button--gray"
                   href="<?= $prev_page_url ?>">Предыдущая страница</a>
            <? endif; ?>
            <?php if (!$is_last_page) : ?>
                <a class="popular__page-link popular__page-link--next button button--gray"
                   href="<?= $next_page_url ?>">Следующая страница</a>
            <? endif; ?>
        </div>
    </div>
</section>

