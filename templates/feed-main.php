<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">
                    <?=$content; ?>
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