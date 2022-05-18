<?php /** @noinspection ALL */


/**
 * @var $posts array{title:string ,id:string,content:string,type:string,user_name:string,avatar:string }
 * @var $post_types array{icon_class:string ,title:string}
 * @var $current_time int
 * @var $current_post_type string
 */
$current_type = null;
if ($current_post_type) {
    $current_type = explode('-', $current_post_type)[1];
}

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
                        <? $all_types_active_class = !isset($current_type) ? 'filters__button--active' : null ?>
                        <a class="filters__button filters__button--ellipse filters__button--all <?= $all_types_active_class ?>"
                           href="/">
                            <span>Все</span>
                        </a>
                    </li>
                    <? foreach ($post_types as $key => $type): ?>
                        <? $type_name = explode('-', $type['icon_class'])[1] ?>
                        <? $link = '?type=' . $type['icon_class'] ?>
                        <? $active_class = $current_type == $type_name ? 'filters__button--active' : ''; ?>
                        <li class="popular__filters-item filters__item">
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
            </div>
        </div>
        <div class="popular__posts">
            <?php if (!empty($posts)) : ?>
                <?php foreach ($posts as $key => $post): ?>
                    <article class="popular__post post <?= $post['type'] ?>">
                        <header class="post__header">
                            <a href="/post.php?id=<?= htmlspecialchars(
                                $post['id']
                            ) ?>">
                                <h2><?= htmlspecialchars($post['title']) ?></h2>
                            </a>
                        </header>
                        <div class="post__main">
                            <?php $safe_title = htmlspecialchars(
                                $post['title']
                            ); ?>
                            <?php $safe_content = htmlspecialchars(
                                $post['content']
                            ); ?>
                            <?php switch ($post['type']) {
                                case "post-link":
                                    print(include_template('post/link.php', [
                                        'title' => $safe_title,
                                        'content' => $safe_content
                                    ]));
                                    break;
                                case "post-text":
                                    print(include_template('post/text.php', [
                                        'content' => $safe_content
                                    ]));
                                    break;
                                case "post-video":
                                    print(include_template('post/video.php', [
                                        'content' => $safe_content
                                    ]));
                                    break;
                                case "post-photo":
                                    print(include_template('post/photo.php', [
                                        'content' => $safe_content
                                    ]));
                                    break;
                                case "post-quote":
                                    print(include_template('post/quote.php', [
                                        'content' => $safe_content
                                    ]));
                                    break;
                            } ?>
                        </div>
                        <footer class="post__footer">
                            <div class="post__author">
                                <a class="post__author-link" href="#"
                                   title="Автор">
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
                                        <?php $post_date_str = generate_random_date(
                                            $key
                                        ); ?>
                                        <time class="post__time"
                                              title='<?= date_create(
                                                  $post_date_str
                                              )->format('d.m.Y H:i') ?>'
                                              datetime="<?= $post_date_str ?>">
                                            <?php
                                            $passed_time_title = get_passed_time_title(
                                                $post_date_str,
                                                $current_time
                                            );
                                            if ($passed_time_title) {
                                                echo $passed_time_title;
                                            }
                                            ?></time>
                                    </div>
                                </a>
                            </div>
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
            <?php else : ?>
                <h2>Публикаций не найдено</h2>
            <?php endif; ?>
        </div>
    </div>
</section>

