<?php
/**
 * @var $communications array
 * @var $dialog array
 * @var $active_user array
 * @var $message_error bool
 * @var $no_dialogs bool
 */

?>

<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <?php if ($no_dialogs) : ?>
        <h2 style="text-align: center">Нет активных диалогов</h2>
    <?php else : ?>
        <section class="messages tabs">
            <h2 class="visually-hidden">Сообщения</h2>
            <div class="messages__contacts">
                <ul class="messages__contacts-list tabs__list">
                    <?php foreach ($communications as $communication): ?>
                        <li class="messages__contacts-item">
                            <a
                                    class="messages__contacts-tab  tabs__item <?= $communication['id'] == $active_user['id'] ? 'messages__contacts-tab--active tabs__item--active' : '' ?>"
                                    href="?user_id=<?= $communication['id'] ?>">
                                <div class="messages__avatar-wrapper">
                                    <img class="messages__avatar"
                                         src="<?= $communication['avatar_url'] ?? 'img/anonymous.png' ?>"
                                         alt="Аватар пользователя">
                                </div>
                                <div class="messages__info">
                  <span class="messages__contact-name">
                   <?= htmlspecialchars($communication['login']) ?>
                  </span>
                                    <?php if (isset($communication['content'])) : ?>
                                        <div class="messages__preview">
                                            <p class="messages__preview-text">
                                                <?= htmlspecialchars(
                                                    $communication['content']
                                                ) ?>
                                            </p>
                                            <time class="messages__preview-time"
                                                  datetime="<?= $communication['created_at'] ?>">
                                                <?= $communication['created_at'] ?>
                                            </time>
                                        </div>
                                    <? endif; ?>
                                </div>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="messages__chat">
                <div class="messages__chat-wrapper">
                    <ul class="messages__list tabs__content tabs__content--active">
                        <?php foreach ($dialog as $message): ?>
                            <?php $is_my_message = $message['sender_id'] === $_SESSION['user']['id']; ?>
                            <li class="messages__item <?= $is_my_message ? 'messages__item--my' : '' ?>">
                                <div class="messages__info-wrapper">
                                    <div class="messages__item-avatar">
                                        <a class="messages__author-link"
                                           href="/profile?id=<?= $message['sender_id'] ?>">
                                            <?php $avatar_url = $is_my_message
                                                ? (isset($_SESSION['user']['avatar_url']) ? $_SESSION['user']['avatar_url'] : 'img/anonymous.png')
                                                : $active_user['avatar_url'] ?? 'img/anonymous.png'; ?>
                                            <img class="messages__avatar"
                                                 src="<?= $avatar_url ?>"
                                                 alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="messages__item-info">
                                        <?php $login = $is_my_message ? $_SESSION['user']['login'] : $active_user['login']; ?>
                                        <a class="messages__author"
                                           href="/profile?id=<?= $message['sender_id'] ?>">
                                            <?= htmlspecialchars($login) ?>
                                        </a>
                                        <time class="messages__time"
                                              datetime="<?= $message['created_at'] ?>">
                                            <?= get_passed_time_title(
                                                $message['created_at']
                                            ) ?>
                                        </time>
                                    </div>
                                </div>
                                <p class="messages__text">
                                    <?= htmlspecialchars($message['content']) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>

                    </ul>
                </div>
                <div class="comments">
                    <form class="comments__form form" action="#" method="post">
                        <div class="comments__my-avatar">
                            <img class="comments__picture"
                                 src="<?= $_SESSION['user']['avatar_url'] ?? 'img/anonymous.png' ?>"
                                 alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section <?= $message_error ? 'form__input-section--error' : '' ?>">
                <textarea required
                          name="message"
                          class="comments__textarea form__textarea form__input"
                          placeholder="Ваше сообщение"></textarea>
                            <label class="visually-hidden">Ваше
                                сообщение</label>
                            <?php if (isset($message_error)): ?>
                                <button class="form__error-button button"
                                        type="button">
                                    !
                                </button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка
                                        валидации</h3>
                                    <p class="form__error-desc">Это поле
                                        обязательно
                                        к
                                        заполнению</p>
                                </div>
                            <? endif; ?>
                        </div>
                        <button class="comments__submit button button--green"
                                type="submit">
                            Отправить
                        </button>
                    </form>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
