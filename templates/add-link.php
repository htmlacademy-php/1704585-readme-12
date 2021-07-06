<div class="adding-post__textarea-wrapper form__input-wrapper <?php if (array_key_exists("link", $errors)) :
    ?> form__input-section--error <?php
                                                              endif; ?>">
    <label class="adding-post__label form__label" for="link">Ссылка <span class="form__input-required">*</span></label>
    <div class="form__input-section">
        <input class="adding-post__input form__input" id="link" type="text" name="link" value="<?=getPostVal('link'); ?>">
        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Ошибка!</h3>
            <p class="form__error-desc"><?=$errors['link']; ?></p>
        </div>
    </div>
</div>