<div class="adding-post__input-wrapper form__input-wrapper <?php if (array_key_exists("url", $errors)): ?> form__input-section--error <?php endif; ?>">
    <label class="adding-post__label form__label" for="url">Ссылка из интернета</label>
        <div class="form__input-section">
            <input class="adding-post__input form__input" id="url" type="text" name="url" value="<?=getPostVal('url'); ?>" placeholder="Введите ссылку">
            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
            <div class="form__error-text">
                <h3 class="form__error-title">Ошибка!</h3>
                <p class="form__error-desc"><?=$errors['url']; ?></p>
        </div>
    </div>
</div>