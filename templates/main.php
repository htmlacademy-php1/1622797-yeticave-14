<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
            горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $category) : ?>
                <li class="promo__item promo__item--<?= $category['code']; ?>">
                    <a class="promo__link" href="all-lots.php?category=<?= $category['id']; ?>">
                        <?= htmlspecialchars($category['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($lot['img']); ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot['cat_name']); ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="/lot.php?id=<?= htmlspecialchars($lot['id']); ?>">
                                <?= htmlspecialchars($lot['lot_name']); ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= lot_cost(htmlspecialchars($lot['begin_price'])) ?></span>
                            </div>
                            <?php $interval = get_dt_range(htmlspecialchars($lot['date_completion']), date('H:i')); ?>
                            <div class="lot__timer timer <?php if ($interval['hour'] < 1) {
                                                                echo 'timer--finishing';
                                                            } ?>">
                                <?= str_pad($interval['hour'], 2, '0', STR_PAD_LEFT) ?>:
                                <?= str_pad($interval['minute'], 2, '0', STR_PAD_LEFT) ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>
