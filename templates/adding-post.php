<main class="page__main page__main--adding-post">
    <div class="page__main-section">
        <div class="container">
            <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
            <div class="adding-post__tabs-wrapper tabs">
                <div class="adding-post__tabs filters">
                <ul class="adding-post__tabs-list filters__list tabs__list">
                    <?php foreach ($post_types as $type) : ?>
                    <li class="adding-post__tabs-item filters__item">
                    <a class="adding-post__tabs-link filters__button filters__button--<?=$type['icon_class']; ?> 
                        <?php if ($type['id'] === $id) :
                            ?> filters__button--active <?php
                        endif; ?> tabs__item tabs__itm_active button" href="/add.php?<?=http_build_query([ 'id' => $type['id'] ]); ?>">
                        <svg class="filters__icon" width="22" height="18">
                        <use xlink:href="#icon-filter-<?=$type['icon_class']; ?>"></use>
                        </svg>
                        <span><?=$type['type_name']; ?></span>
                    </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                </div>
                <div class="adding-post__tab-content">
                <section class="adding-post__<?=$post_types[$id - 1]['icon_class']; ?> tabs__content tabs__content--active">
                    <h2 class="visually-hidden">Форма добавления</h2>
                    <form class="adding-post__form form" action="/add.php?<?=http_build_query([ 'id' => $id ]); ?>" method="post" enctype="multipart/form-data">
                    <div class="form__text-inputs-wrapper">
                        <div class="form__text-inputs">
                        <div class="adding-post__input-wrapper form__input-wrapper <?php if (array_key_exists("title", $errors)) :
                            ?> form__input-section--error <?php
                                                                                   endif; ?>">
                            <label class="adding-post__label form__label" for="heading">Заголовок <span class="form__input-required">*</span></label>
                            <div class="form__input-section">
                            <input class="adding-post__input form__input" id="heading" type="text" name="title" value="<?=getPostVal('title'); ?>" placeholder="Введите заголовок">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка!</h3>
                                <p class="form__error-desc"><?=$errors['title']; ?></p>
                            </div>
                            </div>
                        </div>
                        <?=$content; ?>
                        <div class="adding-post__input-wrapper form__input-wrapper <?php if (array_key_exists("tags", $errors)) :
                            ?> form__input-section--error <?php
                                                                                   endif; ?>">
                            <label class="adding-post__label form__label" for="tags">Теги</label>
                            <div class="form__input-section">
                            <input class="adding-post__input form__input" id="tags" type="text" name="tags" value="<?=getPostVal('tags'); ?>" placeholder="Введите теги">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                            <div class="form__error-text">
                                <h3 class="form__error-title">Ошибка!</h3>
                                <p class="form__error-desc"><?=$errors['tags']; ?></p>
                            </div>
                            </div>
                        </div>
                        </div>
                        <?php if (count($errors)) : ?>
                        <div class="form__invalid-block">
                            <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                            <?php foreach ($errors as $key => $value) : ?>
                            <ul class="form__invalid-list">
                                <li class="form__invalid-item"><?=$value; ?></li>
                            </ul>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php if ($post_types[$id - 1]['icon_class'] === 'photo') : ?>
                    <div class="adding-post__input-file-container form__input-container form__input-container--file">
                        <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                        <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                            <input class="adding-post__input-file form__input-file" id="photo" type="file" name="photo" title=" ">
                            <div class="form__file-zone-text">
                            <span>Перетащите фото сюда</span>
                            </div>
                        </div>
                        <button class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button" type="button">
                            <span>Выбрать фото</span>
                            <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                            <use xlink:href="#icon-attach"></use>
                            </svg>
                        </button>
                        </div>
                        <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">

                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="adding-post__buttons">
                        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                        <a class="adding-post__close" href="/index.php">Закрыть</a>
                    </div>
                    </form>
                </section>
                </div>
            </div>
        </div>
    </div>
</main>