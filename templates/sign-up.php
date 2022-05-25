<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="all-lots.php?category=<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php $classname = !empty($errors) ? "form--invalid" : "" ?>
    <form class="form container <?= $classname; ?>" action="sign-up.php" method="post" autocomplete="off">
        <h2>Регистрация нового аккаунта</h2>
        <?php $classname = isset($errors['email']) ? "form__item--invalid" : "" ?>
        <div class="form__item <?= $classname; ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail"
                   value="<?= htmlspecialchars(get_post_val('email')); ?>">
            <span class=" form__error"><?= $errors['email'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['password']) ? "form__item--invalid" : "" ?>
        <div class="form__item <?= $classname; ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль"
                   value="<?= htmlspecialchars(get_post_val('password')); ?>">
            <span class=" form__error"><?= $errors['password'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['first_name']) ? "form__item--invalid" : "" ?>
        <div class="form__item <?= $classname; ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="first_name" placeholder="Введите имя"
                   value="<?= htmlspecialchars(get_post_val('first_name')); ?>">
            <span class=" form__error"><?= $errors['first_name'] ?? ""; ?></span>
        </div>
        <?php $classname = isset($errors['contact']) ? "form__item--invalid" : "" ?>
        <div class="form__item <?= $classname; ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="contact" placeholder="Напишите как с вами связаться"
                      value="<?= htmlspecialchars(get_post_val('contact')); ?>"></textarea>
            <span class=" form__error"><?= $errors['contact'] ?? ""; ?></span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>
