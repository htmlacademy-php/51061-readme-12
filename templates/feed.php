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

<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $key => $post): ?>
                            <article
                                    class="feed__post post <?= $post['type'] ?>">
                                <header class="post__header post__author">
                                    <a class="post__author-link"
                                       href="/profile.php?id=<?= $post['author_id'] ?>"
                                       title="Автор">
                                        <div class="post__avatar-wrapper">
                                            <img class="post__author-avatar"
                                                 src="<?= $post['avatar'] ?? 'img/anonymous.png' ?>"
                                                 alt="Аватар пользователя"
                                                 width="60"
                                                 height="60">
                                        </div>
                                        <div class="post__info">
                                            <b class="post__author-name"><?= htmlspecialchars(
                                                    $post['user_name']
                                                ) ?></b>
                                            <span class="post__time"><?= get_passed_time_title(
                                                    $post['created_at']
                                                ) ?></span>
                                        </div>
                                    </a>
                                </header>
                                <div class="post__main">
                                    <h2>
                                        <a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars(
                                                $post['title']
                                            ) ?></a>
                                    </h2>
                                    <?php print($post['template']) ?>
                                </div>
                                <footer class="post__footer post__indicators">
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
                                        <a class="post__indicator post__indicator--repost button"
                                           href="repost.php?id=<?= $post['id'] ?>"
                                           title="Репост">
                                            <svg class="post__indicator-icon"
                                                 width="19" height="17">
                                                <use xlink:href="#icon-repost"></use>
                                            </svg>
                                            <span><?= $post['repost_count'] ?></span>
                                            <span class="visually-hidden">количество репостов</span>
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
                                </footer>
                            </article>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <h2>Публикаций не найдено</h2>
                    <?php endif; ?>
                </div>
            </div>
            <ul class="feed__filters filters">
                <? $all_types_active_class = !isset($current_post_type) ? 'filters__button--active' : null ?>
                <li class="feed__filters-item filters__item ">
                    <a class="filters__button <?= $all_types_active_class ?>" "
                    href="./">
                    <span>Все</span>
                    </a>
                </li>
                <? foreach ($post_types as $key => $type): ?>
                    <? $type_name = explode('-', $type['icon_class'])[1] ?>
                    <? $link = '?type=' . $type['icon_class'] ?>
                    <? $active_class = $current_post_type == $type_name ? 'filters__button--active' : ''; ?>
                    <li class="feed__filters-item filters__item">
                        <a class="filters__button filters__button--photo button <?= $active_class ?>"
                           href="<?= $link ?>">
                            <span class="visually-hidden">Фото</span>
                            <svg class="filters__icon" width="22"
                                 height="18">
                                <use
                                        xlink:href="#icon-filter-<?= $type_name ?>"></use>
                            </svg>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по
                    нашей
                    франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
