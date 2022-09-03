<?php
/**
 * @var $posts array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string }
 * @var $post_types array{icon_class:string ,title:string}
 * @var $current_time int
 * @var $user array{id:string ,email:string,login:string,avatar_url:string,user_name:string }
 * @var $passed_time string
 * @var $get_param_user_id string
 * @var $total_subscriptions int
 * @var $total_posts int
 * @var $has_subscription bool
 */

?>
<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture"
                             src="<?= $user['avatar_url'] ?>"
                             alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= $user['login'] ?></span>
                        <time class="profile__user-time user__time"
                              datetime="2014-03-20"><?= $passed_time ?> на сайте
                        </time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p
                            class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= $total_posts ?></span>
                        <span
                                class="profile__rating-text user__rating-text">публикаций</span>
                    </p>
                    <p
                            class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= $total_subscriptions ?></span>
                        <span
                                class="profile__rating-text user__rating-text">подписчиков</span>
                    </p>
                </div>
                <?php if ($_SESSION['user']['id'] !== $user['id']) : ?>
                    <div class="profile__user-buttons user__buttons">
                        <a
                                href="/subscription.php?user_id=<?= $user['id'] ?>&has_subscription=<?= $has_subscription ? 'true' : 'false' ?>"
                                class="profile__user-button user__button user__button--subscription button button--main"
                                type="button">
                            <?php if ($has_subscription) : ?>
                                Отписаться
                            <?php else: ?>
                                Подписаться
                            <?php endif; ?>
                        </a>
                        <a
                                class="profile__user-button user__button user__button--writing button button--green"
                                href="/messages.php?user_id=<?= $user['id'] ?>">Сообщение</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <li class="profile__tabs-item filters__item">
                            <a
                                    class="profile__tabs-link filters__button filters__button--active tabs__item tabs__item--active button">Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button"
                               href="#">Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button"
                               href="#">Подписки</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">

                    <section
                            class="profile__posts tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Публикации</h2>
                        <?php foreach (
                            $posts
                            as $post
                        ) : ?>
                            <?php $is_current_user = $_SESSION['user']['id'] == $post['author_id']; ?>
                            <article
                                    class="profile__post post <?= $post['type'] ?>">
                                <header class="post__header">
                                    <h2>
                                        <a href="/post.php?id=<?= $post['id'] ?>"><?= $post['title'] ?></a>
                                    </h2>
                                </header>
                                <div class="post__main">
                                    <?php print($post['template']) ?>
                                </div>
                                <footer class="post__footer">
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
                                                        width="20" height="17">
                                                    <use xlink:href="#icon-heart-active"></use>
                                                </svg>
                                                <span><?= $post['likes_count'] ?></span>
                                                <span class="visually-hidden">количество лайков</span>
                                            </a>
                                            <a class="post__indicator post__indicator--repost button"
                                                <?php if (!$is_current_user) : ?>
                                                    href="repost.php?id=<?= $post['id'] ?>"
                                                <?php endif; ?>
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
                                    <ul class="post__tags">
                                        <?php foreach ($post['hashtags'] as $hashtag) : ?>
                                            <li>
                                                <a href="search.php?search=%23<?= $hashtag ?>"><?= $hashtag ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </footer>
                                <?php if ($post['comments_count']): ?>
                                    <div class="comments">
                                        <a class="comments__button button"
                                           href="/post.php?id=<?= $post['id'] ?>">Показать
                                            комментарии</a>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </section>

                    <section class="profile__likes tabs__content">
                        <h2 class="visually-hidden">Лайки</h2>
                        <ul class="profile__likes-list">
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional"
                                                  datetime="2014-03-20T20:20">5
                                                минут назад
                                            </time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#"
                                       title="Перейти на публикацию">
                                        <div class="post-mini__image-wrapper">
                                            <img class="post-mini__image"
                                                 src="../img/rock-small.png"
                                                 width="109" height="109"
                                                 alt="Превью публикации">
                                        </div>
                                        <span class="visually-hidden">Фото</span>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--text post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional"
                                                  datetime="2014-03-20T20:05">15
                                                минут назад
                                            </time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#"
                                       title="Перейти на публикацию">
                                        <span class="visually-hidden">Текст</span>
                                        <svg class="post-mini__preview-icon"
                                             width="20" height="21">
                                            <use xlink:href="#icon-filter-text"></use>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--video post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional"
                                                  datetime="2014-03-20T18:20">2
                                                часа назад
                                            </time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#"
                                       title="Перейти на публикацию">
                                        <div class="post-mini__image-wrapper">
                                            <img class="post-mini__image"
                                                 src="../img/coast-small.png"
                                                 width="109" height="109"
                                                 alt="Превью публикации">
                                            <span class="post-mini__play-big">
                            <svg class="post-mini__play-big-icon" width="12"
                                 height="13">
                              <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                          </span>
                                        </div>
                                        <span class="visually-hidden">Видео</span>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--quote post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional"
                                                  datetime="2014-03-15T20:05">5
                                                дней назад
                                            </time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#"
                                       title="Перейти на публикацию">
                                        <span class="visually-hidden">Цитата</span>
                                        <svg class="post-mini__preview-icon"
                                             width="21" height="20">
                                            <use xlink:href="#icon-filter-quote"></use>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                            <li class="post-mini post-mini--link post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                                            <time class="post-mini__time user__additional"
                                                  datetime="2014-03-20T20:05">в
                                                далеком 2007-ом
                                            </time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="#"
                                       title="Перейти на публикацию">
                                        <span class="visually-hidden">Ссылка</span>
                                        <svg class="post-mini__preview-icon"
                                             width="21" height="18">
                                            <use xlink:href="#icon-filter-link"></use>
                                        </svg>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </section>

                    <section class="profile__subscriptions tabs__content">
                        <h2 class="visually-hidden">Подписки</h2>
                        <ul class="profile__subscriptions-list">
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional"
                                              datetime="2014-03-20T20:20">5 лет
                                            на сайте
                                        </time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button
                                            class="post-mini__user-button user__button user__button--subscription button button--main"
                                            type="button">Подписаться
                                    </button>
                                </div>
                            </li>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional"
                                              datetime="2014-03-20T20:20">5 лет
                                            на сайте
                                        </time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button
                                            class="post-mini__user-button user__button user__button--subscription button button--quartz"
                                            type="button">Отписаться
                                    </button>
                                </div>
                            </li>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional"
                                              datetime="2014-03-20T20:20">5 лет
                                            на сайте
                                        </time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button
                                            class="post-mini__user-button user__button user__button--subscription button button--main"
                                            type="button">Подписаться
                                    </button>
                                </div>
                            </li>
                            <li class="post-mini post-mini--photo post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="#">
                                            <img class="post-mini__picture user__picture"
                                                 src="../uploads/userpic-petro.jpg"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="#">
                                            <span>Петр Демин</span>
                                        </a>
                                        <time class="post-mini__time user__additional"
                                              datetime="2014-03-20T20:20">5 лет
                                            на сайте
                                        </time>
                                    </div>
                                </div>
                                <div class="post-mini__rating user__rating">
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--publications">
                                        <span class="post-mini__rating-amount user__rating-amount">556</span>
                                        <span class="post-mini__rating-text user__rating-text">публикаций</span>
                                    </p>
                                    <p
                                            class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                                        <span class="post-mini__rating-amount user__rating-amount">1856</span>
                                        <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                                    </p>
                                </div>
                                <div class="post-mini__user-buttons user__buttons">
                                    <button
                                            class="post-mini__user-button user__button user__button--subscription button button--main"
                                            type="button">Подписаться
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
