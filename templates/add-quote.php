<div class="adding-post__input-wrapper form__textarea-wrapper <?php if(array_key_exists("quote", $errors)): ?> form__input-section--error <?php endif; ?>">
    <label class="adding-post__label form__label" for="text">Текст цитаты <span class="form__input-required">*</span></label>
    <div class="form__input-section">
        <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="text" name="quote" value="<?=getPostVal('quote'); ?>" placeholder="Текст цитаты"></textarea>
        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Ошибка!</h3>
            <p class="form__error-desc"><?=$errors['quote']; ?></p>
        </div>
    </div>
</div>
<div class="adding-post__textarea-wrapper form__input-wrapper <?php if(array_key_exists("author", $errors)): ?> form__input-section--error <?php endif; ?>">
    <label class="adding-post__label form__label" for="author">Автор <span class="form__input-required">*</span></label>
    <div class="form__input-section">
        <input class="adding-post__input form__input" id="author" type="text" name="author" value="<?=getPostVal('author'); ?>">
        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
        <div class="form__error-text">
            <h3 class="form__error-title">Ошибка!</h3>
            <p class="form__error-desc"><?=$errors['author']; ?></p>
        </div>
    </div>
</div>