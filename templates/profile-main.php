<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--<?=$tab; ?>">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="img/<?=$user_profile['avatar_img']; ?>" alt="Аватар пользователя">
                    </div>
                <div class="profile__name-wrapper user__name-wrapper">
                    <span class="profile__name user__name"><?=$user_profile['name']; ?></span>
                    <time class="profile__user-time user__time" datetime="<?=$user_profile['created_at']; ?>"><?=make_datetime_relative($user_profile['created_at'], ' на сайте');?></time>
                </div>
            </div>
            <div class="profile__rating user__rating">
                <p class="profile__rating-item user__rating-item user__rating-item--publications">
                    <span class="user__rating-amount"><?=$user_profile['posts_count']; ?></span>
                    <span class="profile__rating-text user__rating-text">публикаций</span>
                </p>
                <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                    <span class="user__rating-amount"><?=$user_profile['subs']; ?></span>
                    <span class="profile__rating-text user__rating-text">подписчиков</span>
                </p>
            </div>
            <?php if ($user_profile['id'] !== $user['id']): ?>
            <div class="profile__user-buttons user__buttons">
                <?php if (!$is_subscribe): ?>
                    <button class="profile__user-button user__button user__button--subscription button button--main" onClick='location.href="/subscribe.php?id=<?=$user_profile['id']; ?>"' type="button">Подписаться</button>
                <?php else: ?>
                    <button class="profile__user-button user__button user__button--subscription button button--quartz" onClick='location.href="/unsubscribe.php?id=<?=$user_profile['id']; ?>"' type="button">Отписаться</button>
                <?php endif; ?>
                <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <?php foreach ($tabs as $element): ?>
                            <li class="profile__tabs-item filters__item tabs__item">
                                <a class="profile__tabs-link filters__button <?php if ($tab === $element['tab']): ?>filters__button--active button"<?php else: ?> button" href="/profile.php?<?=http_build_query(array_merge($_GET, ['tab' => $element['tab']]));?>"<?php endif; ?>><?=$element['title']; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?=$content; ?>
            </div>
        </div>

    </div>
</main>