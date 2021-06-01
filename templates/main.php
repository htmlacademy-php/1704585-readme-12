<section class="page__main page__main--popular">
    <div class="container">
    <h1 class="page__title page__title--popular">Популярное</h1>
        </div>
        <div class="popular container">
            <div class="popular__filters-wrapper">
                <div class="popular__sorting sorting">
                    <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                    <ul class="popular__sorting-list sorting__list">
                        <?php foreach ($sort_types as $sort_type): ?>
                            <li class="sorting__item">
                                <a class="sorting__link<?php if($sort === $sort_type['id']): ?> sorting__link--active <?php endif; ?>" 
                                href="/?<?=http_build_query(array_merge($_GET, [ 'order_by' => $sort_type['id'] ])); ?>">
                                    <span><?=$sort_type['title']; ?></span>
                                    <svg class="sorting__icon" width="10" height="12">
                                        <use xlink:href="#icon-sort"></use>
                                    </svg>
                                </a>
                            </li>
                        <?php endforeach; ?>                        
                    </ul>
                </div>
                <div class="popular__filters filters">
                    <b class="popular__filters-caption filters__caption">Тип контента:</b>
                    <ul class="popular__filters-list filters__list">
                        <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                            <a class="filters__button filters__button--ellipse filters__button--all
                            <?= is_null($id)? 'filters__button--active' : '' ?>" 
                            href="/?<?=http_build_query(array_merge($_GET, ['id' => null])); ?>">
                                <span>Все</span>
                            </a>
                        </li>
                        <?php foreach ($post_types as $types): ?>
                            <li class="popular__filters-item filters__item">
                                <a class="filters__button filters__button--<?=$types['icon_class']; ?> button
                                <?php if($id === $types['id']): ?> filters__button--active <?php endif; ?>" 
                                href="/?<?=http_build_query(array_merge($_GET, [ 'id' => $types['id'] ])); ?>">
                                    <span class="visually-hidden"><?=$types['type_name']; ?></span>
                                    <svg class="filters__icon" width="22" height="18">
                                        <use xlink:href="#icon-filter-<?=$types['icon_class']; ?>"></use>
                                    </svg>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="popular__posts">
            <?php foreach ($posts as $post): ?>
                <article class="popular__post post <?='post-' . $post['class']; ?>">
                    <header class="post__header">
                        <h2><a href="/post.php?id=<?=$post['id']; ?>"><?=$post['title']; ?></a></h2>
                    </header>
                    <div class="post__main">
                        <!--здесь содержимое карточки-->
                        <!--содержимое для поста-цитаты-->
                        <?php if ($post['type'] === "Цитата"): ?>
                        <blockquote>
                            <p>
                                <!--здесь текст-->
                                <?=$post['content']; ?>
                            </p>
                            <cite><?=$post['author']; ?></cite>
                        </blockquote>
                        <?php endif; ?>

                        <!--содержимое для поста-ссылки-->
                        <?php if ($post['type'] === "Ссылка"): ?>
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="http://<?=$post['link']; ?>" title="Перейти по ссылке">
                                <div class="post-link__info-wrapper">
                                    <div class="post-link__icon-wrapper">
                                        <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                    </div>
                                    <div class="post-link__info">
                                        <h3><?=$post['title']; ?><!--здесь заголовок--></h3>
                                    </div>
                                </div>
                                <span><?=$post['link']; ?><!--здесь ссылка--></span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!--содержимое для поста-фото-->
                        <?php if ($post['type'] === "Картинка"): ?>
                        <div class="post-photo__image-wrapper">
                            <img src="uploads/<?=$post['img']; ?>" alt="Фото от пользователя" width="360" height="240">
                        </div>
                        <?php endif; ?>

                        <!--содержимое для поста-видео-->
                        <?php if ($post['type'] === "Видео"): ?>
                        <div class="post-video__block">
                            <div class="post-video__preview">
                                <?=embed_youtube_cover($post['video']); ?>
                                <img src="" alt="Превью к видео" width="360" height="188">
                            </div>
                            <a href="post-details.html" class="post-video__play-big button">
                                <svg class="post-video__play-big-icon" width="14" height="14">
                                <use xlink:href="#icon-video-play-big"></use>
                                </svg>
                                <span class="visually-hidden">Запустить проигрыватель</span>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!--содержимое для поста-текста-->
                        <?php if ($post['type'] === "Текст"): ?>
                        <?=cut_string($post['content']); ?><!--здесь текст-->
                        <?php endif; ?>
                    </div>
                    <footer class="post__footer">
                        <div class="post__author">
                            <a class="post__author-link" href="#" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <!--укажите путь к файлу аватара-->
                                    <img class="post__author-avatar" src="img/<?=$post['avatar']; ?>" alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?=$post['name']; ?><!--здесь имя пользоателя--></b>
                                    <time class="post__time" datetime="<?=$post['published_at']; ?>" title="<?=date("d.m.Y H:i", strtotime($post['published_at'])); ?>">
                                        <?=make_datetime_relative($post['published_at']); ?>
                                    </time>
                                </div>
                            </a>
                        </div>
                        <div class="post__indicators">
                            <div class="post__buttons">
                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
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
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>