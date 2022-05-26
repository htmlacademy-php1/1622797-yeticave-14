<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category) : ?>
            <li class="nav__item <?php if ($category_id === $category['id']) :
                ?>nav__item--current<?php
                                 endif;?>">
                <a href="all-lots.php?category=<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <?php foreach ($categories as $category) : ?>
                <?php if ($category_id === $category['id']) : ?>
            <h2>Все лоты в категории <span><?= '«' . htmlspecialchars($category['name']) . '»'; ?></span></h2>
                <?php endif; ?>
            <?php endforeach; ?>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($lot['img']); ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot['cat_name']); ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id']; ?>">
                                <?= htmlspecialchars($lot['name']) ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= lot_cost(htmlspecialchars($lot['begin_price'])) ?></span>
                            </div>
                                <?php $interval = get_dt_range($lot['date_completion'], date('H:i')); ?>
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
        <?php if ($page_count > 1) : ?>
            <ul class="pagination-list">
                <?php foreach ($categories as $category) : ?>
                    <?php if ($category_id === $category['id']) : ?>
                <li class="pagination-item pagination-item-prev">
                    <a <?php if ($cur_page != 1) : ?>href="<?= 'all-lots.php?category='
                    . htmlspecialchars($category['id']) . '&page=' . $cur_page - 1; ?><?php endif; ?>">Назад</a></li>

                    <?php foreach ($pages as $page) : ?>
                        <li class="pagination-item <?php if ($page == $cur_page) : ?>pagination-item-active
                        <?php endif; ?>"><a href="<?= 'all-lots.php?category=' . htmlspecialchars($category['id']) .
                            '&page=' . $page; ?>"><?= $page; ?></a></li>
                    <?php endforeach; ?>

                <li class="pagination-item pagination-item-next"><a <?php if ($cur_page < $page_count) : ?>
                        href="<?= 'all-lots.php?category=' . htmlspecialchars($category['id']) . '&page=' .
                        $cur_page + 1; ?>"<?php endif; ?>>Вперед</a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</main>
