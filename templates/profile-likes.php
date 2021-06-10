<div class="profile__tab-content">
    <section class="profile__likes tabs__content tabs__content--active">
        <h2 class="visually-hidden">Лайки</h2>
        <ul class="profile__likes-list">
            <?php foreach ($content as $like): ?>
            <li class="post-mini post-mini--photo post user">
                <div class="post-mini__user-info user__info">
                    <div class="post-mini__avatar user__avatar">
                        <a class="user__avatar-link" href="/profile.php?id=<?=$like['user_id']; ?>">
                            <img class="post-mini__picture user__picture" src="img/<?=$like['avatar_img']; ?>" alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="post-mini__name-wrapper user__name-wrapper">
                        <a class="post-mini__name user__name" href="/profile.php?id=<?=$like['user_id']; ?>">
                            <span><?=$like['name']; ?></span>
                        </a>
                    <div class="post-mini__action">
                        <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
                            <time class="post-mini__time user__additional" datetime="<?=$like['created_at']; ?>"><?=make_datetime_relative($like['created_at']); ?></time>
                    </div>
                </div>
                </div>
                <div class="post-mini__preview">
                    <a class="post-mini__link" href="/post.php?id=<?=$like['post_id']; ?>" title="Перейти на публикацию">
                        <?php if ($like['type'] === 'photo'): ?>
                            <div class="post-mini__image-wrapper">
                            <img class="post-mini__image" src="uploads/<?=$like['img']; ?>" width="109" height="109" alt="Превью публикации">
                            </div>
                            <span class="visually-hidden">Фото</span>
                        <?php endif; ?>
                        <?php if ($like['type'] === 'text'): ?>
                            <span class="visually-hidden">Текст</span>
                            <svg class="post-mini__preview-icon" width="20" height="21">
                                <use xlink:href="#icon-filter-text"></use>
                            </svg>
                        <?php endif; ?>
                        <?php if ($like['type'] === 'video'): ?>
                            <div class="post-mini__image-wrapper">
                                <img class="post-mini__image" src="img/coast-small.png" width="109" height="109" alt="Превью публикации">
                                <span class="post-mini__play-big">
                                    <svg class="post-mini__play-big-icon" width="12" height="13">
                                        <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                </span>
                            </div>
                            <span class="visually-hidden">Видео</span>
                        <?php endif; ?>
                        <?php if ($like['type'] === 'quote'): ?>
                            <span class="visually-hidden">Цитата</span>
                            <svg class="post-mini__preview-icon" width="21" height="20">
                                <use xlink:href="#icon-filter-quote"></use>
                            </svg>
                        <?php endif; ?>
                        <?php if ($like['type'] === 'link'): ?>
                            <span class="visually-hidden">Ссылка</span>
                            <svg class="post-mini__preview-icon" width="21" height="18">
                                <use xlink:href="#icon-filter-link"></use>
                            </svg>
                        <?php endif; ?>
                    </a>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>