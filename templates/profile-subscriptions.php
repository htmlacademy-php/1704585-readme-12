<div class="profile__tab-content">
    <section class="profile__subscriptions tabs__content tabs__content--active">
    <h2 class="visually-hidden">Подписки</h2>
        <ul class="profile__subscriptions-list">
            <?php foreach ($content as $subscription) : ?>
                <li class="post-mini post-mini--photo post user">
                    <div class="post-mini__user-info user__info">
                        <div class="post-mini__avatar user__avatar">
                            <a class="user__avatar-link" href="/profile.php?id=<?=$subscription['id']; ?>">
                                <img class="post-mini__picture user__picture" src="img/<?=$subscription['avatar_img']; ?>" alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-mini__name-wrapper user__name-wrapper">
                            <a class="post-mini__name user__name" href="/profile.php?id=<?=$subscription['id']; ?>">
                                <span><?=$subscription['name']; ?></span>
                            </a>
                            <time class="post-mini__time user__additional" datetime="<?=$subscription['created_at']; ?>"><?=make_datetime_relative($subscription['created_at'], ' на сайте');?></time>
                          </div>
                    </div>
                    <div class="post-mini__rating user__rating">
                        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-mini__rating-amount user__rating-amount"><?=$subscription['posts_count']; ?></span>
                            <span class="post-mini__rating-text user__rating-text">публикаций</span>
                        </p>
                        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-mini__rating-amount user__rating-amount"><?=$subscription['subs']; ?></span>
                            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                        </p>
                    </div>
                    <?php if ($user['id'] !== $subscription['id']) : ?>
                    <div class="post-mini__user-buttons user__buttons">
                        <?php if (!in_array($subscription['id'], $user_subscriptions)) : ?>
                            <button class="post-mini__user-button user__button user__button--subscription button button--main" onClick='location.href="/unsubscribe.php?id=<?=$subscription['id']; ?>"' type="button">Подписаться</button>
                        <?php else : ?>
                            <button class="post-mini__user-button user__button user__button--subscription button button--quartz" onClick='location.href="/unsubscribe.php?id=<?=$subscription['id']; ?>"' type="button">Отписаться</button>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
                  
        </ul>
    </section>
</div>