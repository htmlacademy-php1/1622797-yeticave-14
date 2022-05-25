<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="all-lots.php?category=<?= $category['id']; ?>">
                        <?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php $classname = !empty($errors) ? "form--invalid" : "" ?>
    <form class="form form--add-lot container <?= $classname; ?>" action="add.php" method="post"
          enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $classname = isset($errors['name']) ? "form__item--invalid" : "" ?>
            <div class="form__item <?= $classname; ?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота"
                       value="<?= htmlspecialchars(get_post_val('name')); ?>">
                <span class="form__error"><?= $errors['name'] ?? ""; ?></span>
            </div>
            <?php $classname = isset($errors['category_id']) ? "form__item--invalid" : "" ?>
            <div class="form__item <?= $classname; ?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category_id">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= htmlspecialchars($category['id']); ?>"
                            <?= ($category['id'] === get_post_val('category_id')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?= $errors['category_id'] ?? ""; ?></span>
            </div>
        </div>
        <?php $classname = isset($errors['description']) ? "form__item--invalid" : "" ?>
        <div class="form__item form__item--wide <?= $classname; ?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="description" placeholder="Напишите описание лота"><?= htmlspecialchars(get_post_val('description')); ?></textarea>
            <span class="form__error"><?= $errors['description'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['img']) ? "form__item--invalid" : "" ?>
        <div class="form__item form__item--file <?= $classname; ?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="lot-img" name="img" value="">
                <label for="lot-img">
                    Добавить
                </label>
            </div>
            <span class="form__error"><?= $errors['img'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['begin_price']) ? "form__item--invalid" : "" ?>
        <div class="form__container-three">
            <div class="form__item form__item--small <?= $classname; ?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="begin_price" placeholder="0"
                       value="<?= htmlspecialchars(get_post_val('begin_price')); ?>">
                <span class="form__error"><?= $errors['begin_price'] ?? ""; ?></span>
            </div>
            <?php $classname = isset($errors['bid_step']) ? "form__item--invalid" : "" ?>
            <div class="form__item form__item--small <?= $classname; ?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="bid_step" placeholder="0"
                       value="<?= htmlspecialchars(get_post_val('bid_step')); ?>">
                <span class="form__error"><?= $errors['bid_step'] ?? ""; ?></span>
            </div>
            <?php $classname = isset($errors['date_completion']) ? "form__item--invalid" : "" ?>
            <div class="form__item <?= $classname; ?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" type="text" name="date_completion"
                       placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                       value="<?= htmlspecialchars(get_post_val('date_completion')); ?>">
                <span class="form__error"><?= $errors['date_completion'] ?? ""; ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>
