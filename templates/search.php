<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
                <li class="nav__item">
                    <a href="all-lots.php?category=<?= $category['id']; ?>"><?= $category['name']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($search_result as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= htmlspecialchars($lot['img']); ?>" width="350" height="260" alt="">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($lot['cat_name']); ?></span>
                            <h3 class="lot__title"><a class="text-link" href="/lot.php?id=<?= htmlspecialchars($lot['id']); ?>"><?= htmlspecialchars($lot['lot_name']); ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= lot_cost($lot['begin_price']) ?></span>
                                </div>
                                <?php $interval = get_dt_range($lot['date_completion'], date('H:i')); ?>
                                <div class="lot__timer timer <?php if ($interval['hour'] < 1) { echo 'timer--finishing'; } ?>">
                                    <?= str_pad($interval['hour'], 2, '0', STR_PAD_LEFT) ?>:<?= str_pad($interval['minute'], 2, '0', STR_PAD_LEFT) ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <?php if (isset($pagination_limit['page_count']) && $pagination_limit['page_count'] > 1) : ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a href="<?= 'search.php?search=' . htmlspecialchars($search) . '&page=' . $pagination['prev'] ?>">Назад</a></li>
            <?php foreach ($pagination['pages'] as $pages) : ?>
                <?php if ($pages === $pagination['cur_page']) : ?>
                    <li class="pagination-item pagination-item-active"><a><?= $pages; ?></a></li>
                <?php else : ?>
                    <li class="pagination-item"><a href="<?= 'search.php?search=' . htmlspecialchars($search) . '&page=' . $pages ?>"><?= $pages; ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next"><a href="<?= 'search.php?search=' . htmlspecialchars($search) . '&page=' . $pages ?>">Вперед</a></li>
        </ul>
        <?php endif; ?>
    </div>
</main>
