<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?php foreach ($posts as $post): ?>
                        <article class="feed__post post post-<?=$post['class']?>">
                            <header class="post__header post__author">
                                <a class="post__author-link" href="/profile.php?id=<?=$post['user_id']; ?>" title="Автор">
                                    <div class="post__avatar-wrapper">
                                        <img class="post__author-avatar" src="img/<?=$post['avatar']?>" alt="Аватар пользователя" width="60" height="60">
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name"><?=$post['name']?></b>
                                        <span class="post__time"><?=make_datetime_relative($post['published_at']); ?></span>
                                    </div>
                                </a>
                            </header>
                            <div class="post__main">
                                <?php if ($post['type'] === 'Картинка'): ?>
                                    <h2><a href="/post.php?id=<?=$post['id']; ?>"><?=$post['title']; ?></a></h2>
                                    <div class="post-<?=$post['class']; ?>__image-wrapper">
                                        <img src="uploads/<?=$post['img']; ?>" alt="Фото от пользователя" width="760" height="396">
                                    </div>
                                <?php endif; ?>

                                <?php if ($post['type'] === 'Текст'): ?>
                                    <h2><a href="/post.php?id=<?=$post['id']; ?>"><?=$post['title']; ?></a></h2>
                                    <?=cut_string($post['content']); ?>
                                <?php endif; ?>

                                <?php if ($post['type'] === 'Видео'): ?>
                                    <div class="post-video__block">
                                        <div class="post-video__preview">
                                            <img src="img/coast.jpg" alt="Превью к видео" width="760" height="396">
                                        </div>
                                        <div class="post-video__control">
                                            <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                                            <div class="post-video__scale-wrapper">
                                                <div class="post-video__scale">
                                                    <div class="post-video__bar">
                                                        <div class="post-video__toggle"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                                        </div>
                                        <button class="post-video__play-big button" type="button">
                                            <svg class="post-video__play-big-icon" width="27" height="28">
                                                <use xlink:href="#icon-video-play-big"></use>
                                            </svg>
                                            <span class="visually-hidden">Запустить проигрыватель</span>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($post['type'] === 'Цитата'): ?>
                                    <blockquote>
                                        <p>
                                            <?=$post['content']; ?>
                                        </p>
                                        <cite><?=$post['author']; ?></cite>
                                    </blockquote>
                                <?php endif; ?>

                                <?php if ($post['type'] === 'Ссылка'): ?>
                                    <div class="post-link__wrapper">
                                        <a class="post-link__external" href="<?=$post['link']; ?>" title="Перейти по ссылке">
                                            <div class="post-link__icon-wrapper">
                                                <img src="img/logo-vita.jpg" alt="Иконка">
                                            </div>
                                            <div class="post-link__info">
                                                <h3><?=$post['title']; ?></h3>
                                                <span><?=$post['link']; ?></span>
                                            </div>
                                            <svg class="post-link__arrow" width="11" height="16">
                                                <use xlink:href="#icon-arrow-right-ad"></use>
                                            </svg>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button" href="/like.php?id=<?=$post['id']; ?>" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?=$post['likes']; ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?=$post['comments']; ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                    <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-repost"></use>
                                        </svg>
                                        <span>5</span>
                                        <span class="visually-hidden">количество репостов</span>
                                    </a>
                                </div>
                            </footer>
                        </article>

                    <?php endforeach; ?>
                </div>
            </div>
            <ul class="feed__filters filters">
                <li class="feed__filters-item filters__item">
                    <a class="filters__button <?= is_null($id) ? 'filters__button--active' : ''?>" 
                        href="/feed.php?<?=http_build_query(array_merge($_GET, ['id' => null])); ?>">
                        <span>Все</span>
                    </a>
                </li>
                <?php foreach ($post_types as $type): ?>
                <li class="feed__filters-item filters__item">
                    <a class="filters__button filters__button--<?=$type['icon_class']; ?> button 
                        <?php if ($id === $type['id']): ?> filters__button--active<?php endif; ?>" 
                        href="/feed.php?<?=http_build_query(array_merge($_GET, [ 'id' => $type['id'] ])); ?>">
                        <span class="visually-hidden"><?=$type['type_name']; ?></span>
                        <svg class="filters__icon" width="22" height="18">
                            <use xlink:href="#icon-filter-<?=$type['icon_class']; ?>"></use>
                        </svg>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
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