<?php

/**
 * @var $post array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string }
 * @var $author_info array{email:string ,login:string,avatar_url:string,id:string,subscribers_count:string,posts_count:string }
 * @var $comment_error string
 * @var $has_subscription bool
 */
$title = htmlspecialchars($post['title']);
$type = $post['type'];

$login = htmlspecialchars($author_info['login']);
$posts_count = $author_info['posts_count'];
$subscribers_count = $author_info['subscribers_count'];
$is_current_user = $_SESSION['user']['id'] == $post['author_id'];
$last_comment_id = end($post['comments'])['id'] ?? null;

?>
<main class="page__main page__main--publication">
    <div class="container">
        <h1 class="page__title page__title--publication"><?= $title ?></h1>
        <section class="post-details">
            <h2 class="visually-hidden">Публикация</h2>
            <div class="post-details__wrapper <?= $type ?>">
                <div class="post-details__main-block post post--details">
                    <div class="post-details__image-wrapper <?= $type ?>">
                        <div class="post__main">
                            <?= $post['template'] ?>
                        </div>
                    </div>

                    <div class="post__indicators">
                        <div class="post__buttons">

                            <a class="post__indicator post__indicator--likes button"
                                <?php if (!$is_current_user) : ?>
                                    href="/like.php?post_id=<?= $post['id'] ?>"
                                <?php endif; ?>
                               title="Лайк">

                                <svg class="post__indicator-icon" width="20"
                                     height="17">
                                    <use xlink:href="#icon-heart"></use>
                                </svg>
                                <svg
                                        class="post__indicator-icon post__indicator-icon--like-active"
                                        width="20"
                                        height="17">
                                    <use xlink:href="#icon-heart-active"></use>
                                </svg>
                                <span><?= $post['likes_count'] ?></span>
                                <span class="visually-hidden">количество лайков</span>
                            </a>
                            <a class="post__indicator post__indicator--comments button"
                               href="#<?= $last_comment_id ?>"
                               title="Комментарии">
                                <svg class="post__indicator-icon" width="19"
                                     height="17">
                                    <use xlink:href="#icon-comment"></use>
                                </svg>
                                <span><?= count($post['comments']) ?></span>
                                <span class="visually-hidden">количество комментариев</span>
                            </a>

                            <a class="post__indicator post__indicator--repost button"
                                <?php if (!$is_current_user) : ?>
                                    href="repost.php?id=<?= $post['id'] ?>"
                                <?php endif; ?>
                               title="Репост">

                                <svg class="post__indicator-icon" width="19"
                                     height="17">
                                    <use xlink:href="#icon-repost"></use>
                                </svg>
                                <span><?= $post['repost_count'] ?></span>
                                <span class="visually-hidden">количество репостов</span>
                            </a>

                        </div>
                        <span class="post__view"><?= $post['views'] . ' ' ?><?php
                            print(get_noun_plural_form(
                                $post['views'],
                                'просмотр',
                                'просмотрa',
                                'просмотров'
                            )) ?></span>
                    </div>
                    <ul class="post__tags">
                        <?php foreach ($post['hashtags'] as $hashtag) : ?>
                            <li>
                                <a href="search.php?search=%23<?= htmlspecialchars(
                                    $hashtag
                                ) ?>"><?= htmlspecialchars($hashtag) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="comments">
                        <form class="comments__form form" action="#"
                              method="post">
                            <div class="comments__my-avatar">
                                <img class="comments__picture"
                                     src=" <?= $_SESSION['user']['avatar_url'] ?? 'img/anonymous.png' ?> "
                                     alt=" Аватар пользователя">
                            </div>
                            <div
                                    class="form__input-section <?= $comment_error ? 'form__input-section--error' : '' ?>">
                                <textarea
                                        class="comments__textarea form__textarea form__input"
                                        name="comment"
                                        placeholder="Ваш комментарий"><?= htmlspecialchars(
                                        get_post_val(
                                            'comment'
                                        )
                                    ) ?></textarea>
                                <label class="visually-hidden">Ваш
                                    комментарий</label>
                                <button class="form__error-button button"
                                        type="button">!
                                </button>
                                <?php if ($comment_error): ?>
                                    <div class="form__error-text">
                                        <h3 class="form__error-title">Ошибка
                                            валидации</h3>
                                        <p class="form__error-desc"><?= $comment_error ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button
                                    class="comments__submit button button--green"
                                    type="submit">Отправить
                            </button>
                        </form>
                        <div class="comments__list-wrapper">
                            <ul class="comments__list">
                                <?php foreach ($post['comments'] as $comment) : ?>
                                    <li class="comments__item user"
                                        <?php if ($comment['id'] === $last_comment_id): ?>
                                            id="<?= $last_comment_id ?>"
                                        <? endif; ?>
                                    >
                                        <div class="comments__avatar">
                                            <a class="user__avatar-link"
                                               href="/profile.php?id=<?= $comment['author_id'] ?>">
                                                <img class="comments__picture"
                                                     src="<?= $comment['avatar_url'] ?? 'img/anonymous.png' ?>"
                                                     alt="Аватар пользователя">
                                            </a>
                                        </div>

                                        <div class="comments__info">
                                            <div class="comments__name-wrapper">
                                                <a class="comments__user-name"
                                                   href="/profile.php?id=<?= $comment['author_id'] ?>">
                                                    <span><?= htmlspecialchars(
                                                            $comment['login']
                                                        ) ?></span>
                                                </a>
                                                <time class="comments__time"
                                                      datetime="<?= $comment['created_at'] ?>"><?= get_passed_time_title(
                                                        $comment['created_at']
                                                    ) ?>
                                                </time>
                                            </div>
                                            <p class="comments__text">
                                                <?= htmlspecialchars(
                                                    $comment['content']
                                                ) ?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="post-details__user user">
                    <div class="post-details__user-info user__info">
                        <div class="post-details__avatar user__avatar">
                            <a class="post-details__avatar-link user__avatar-link"
                               href="/profile.php?id=<?= $author_info['id'] ?>">
                                <img class="post-details__picture user__picture"
                                     src="<?= $author_info['avatar_url'] ?? 'img/anonymous.png' ?>"
                                     alt="Аватар пользователя">
                            </a>
                        </div>
                        <div
                                class="post-details__name-wrapper user__name-wrapper">
                            <a class="post-details__name user__name"
                               href="/profile.php?id=<?= $author_info['id'] ?>">
                                <span>"<?= $login ?>"</span>
                            </a>
                            <time class="post-details__time user__time"
                                  datetime="<?= $author_info['created_at'] ?>">
                                <?= get_passed_time_title(
                                    $author_info['created_at']
                                ) ?>
                            </time>
                        </div>


                    </div>
                    <div class="post-details__rating user__rating">
                        <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
                            <span
                                    class="post-details__rating-amount user__rating-amount"><?= $subscribers_count ?></span>
                            <span
                                    class="post-details__rating-text user__rating-text">подписчиков</span>
                        </p>
                        <p class="post-details__rating-item user__rating-item user__rating-item--publications">
                            <span
                                    class="post-details__rating-amount user__rating-amount"><?= $posts_count ?></span>
                            <span
                                    class="post-details__rating-text user__rating-text">публикаций</span>
                        </p>
                    </div>
                    <?php if ($post['author_id'] !== $_SESSION['user']['id']): ?>
                        <div class="post-details__user-buttons user__buttons">

                            <a
                                    href="/subscription.php?user_id=<?= $post['author_id'] ?>&has_subscription=<?= $has_subscription ? 'true' : 'false' ?>"
                                    class="user__button user__button--subscription button button--main"
                                    type="button">
                                <?= $has_subscription ? 'Отписаться' : 'Подписаться' ?>
                            </a>

                            <a class="user__button user__button--writing button button--green"
                               href="messages.php?user_id=<?= $post['author_id'] ?>">Сообщение</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </section>
    </div>
</main>
