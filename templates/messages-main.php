<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
        <section class="messages tabs">
            <h2 class="visually-hidden">Сообщения</h2>
            <div class="messages__contacts">
                <ul class="messages__contacts-list tabs__list">
                    <?php foreach ($user_list as $user): ?>
                    <li class="messages__contacts-item<!-- messages__contacts-item--new-->">
                        <a class="messages__contacts-tab tabs__item<?php if ($id === $user['id']): ?> messages__contacts-tab--active tabs__item tabs__item--active <?php endif; ?>" href="/messages.php?id=<?=$user['id']; ?>">
                            <div class="messages__avatar-wrapper">
                                <img class="messages__avatar" src="img/<?=$user['avatar_img']; ?>" alt="Аватар пользователя">
                                <!--<i class="messages__indicator">2</i>-->
                            </div>
                            <div class="messages__info">
                                <span class="messages__contact-name">
                                    <?=$user['name'];?>
                                </span>
                                <div class="messages__preview">
                                    <p class="messages__preview-text">
                                        Ок, бро! По рукам
                                    </p>
                                    <time class="messages__preview-time" datetime="2019-05-01T00:15">
                                        00:15
                                    </time>
                                </div>
                            </div>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="messages__chat">
                <?php if ($messages): ?>
                <div class="messages__chat-wrapper">
                    <ul class="messages__list tabs__content tabs__content--active">
                        <?php foreach ($messages as $message): ?>
                        <li class="messages__item<?php if ($auth_user['id'] === $message['from_user_id']): ?> messages__item--my<?php endif; ?>">
                            <div class="messages__info-wrapper">
                                <div class="messages__item-avatar">
                                    <a class="messages__author-link" href="/profile.php?id=<?=$message['id']; ?>">
                                        <img class="messages__avatar" src="img/<?=$message['avatar_img']; ?>" alt="Аватар пользователя">
                                    </a>
                                </div>
                                <div class="messages__item-info">
                                    <a class="messages__author" href="/profile.php?id=<?=$message['id']; ?>">
                                        <?=$message['name']; ?>
                                    </a>
                                    <time class="messages__time" datetime="<?=$message['published_at']; ?>">
                                        <?=make_datetime_relative($message['published_at']); ?>
                                    </time>
                                </div>
                            </div>
                            <p class="messages__text">
                                <?=$message['content']; ?>
                            </p>
                        </li>
                        <?php endforeach; ?>
                    </ul>            
                </div>
                <?php endif; ?>
                <?php if ($user_list): ?>
                <div class="comments">
                    <form class="comments__form form" action="/messages.php?<?=http_build_query(['id' => $id]); ?>" method="post">
                        <div class="comments__my-avatar">
                            <img class="comments__picture" src="img/<?=$auth_user['avatar_img']; ?>" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section<?php if ($errors): ?> form__input-section--error<?php endif; ?>">
                            <textarea class="comments__textarea form__textarea form__input" name="text"
                                placeholder="Ваше сообщение"><?=$content; ?></textarea>
                            <label class="visually-hidden">Ваше сообщение</label>
                            <button class="form__error-button button" type="button">!</button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка валидации</h3>
                                <p class="form__error-desc"><?=$errors['text']; ?></p>
                            </div>
                        </div>
                        <button class="comments__submit button button--green" type="submit">Отправить</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </section>
</main>