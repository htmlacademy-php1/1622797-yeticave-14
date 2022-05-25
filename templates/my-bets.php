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
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($user_active_lot as $active_lot) : ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= htmlspecialchars($active_lot['img']); ?>" width="54" height="40"
                             alt="<?= htmlspecialchars($active_lot['lot_name']); ?>">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?id=<?= htmlspecialchars($active_lot['lot_id']); ?>">
                            <?= htmlspecialchars($active_lot['lot_name']); ?></a></h3>
                </td>
                <td class="rates__category">
                    <?= htmlspecialchars($active_lot['cat_name']); ?>
                </td>
                <td class="rates__timer">
                    <?php $interval = get_dt_range(htmlspecialchars($active_lot['date_completion']), date('H:i')); ?>
                    <div class="timer <?php if ($interval['hour'] < 1) {
                        echo 'timer--finishing';
                    } ?>"><?= str_pad($interval['hour'], 2, '0', STR_PAD_LEFT) ?>:
                        <?= str_pad($interval['minute'], 2, '0', STR_PAD_LEFT) ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= lot_cost(htmlspecialchars($active_lot['price'])); ?>
                </td>
                <td class="rates__time">
                    <?= get_time_bet(htmlspecialchars($active_lot['creation_time']), 'NOW'); ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($user_win_lot as $win_lot) : ?>
            <tr class="rates__item rates__item--win">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= htmlspecialchars($win_lot['img']); ?>" width="54" height="40"
                             alt="<?= htmlspecialchars($win_lot['lot_name']); ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?id=<?= htmlspecialchars($win_lot['lot_id']); ?>">
                                <?= htmlspecialchars($win_lot['lot_name']); ?></a></h3>
                        <p><?= htmlspecialchars($win_lot['contact']); ?>></p>
                    </div>
                </td>
                <td class="rates__category">
                    <?= htmlspecialchars($win_lot['cat_name']); ?>
                </td>
                <td class="rates__timer">
                    <div class="timer timer--win">Ставка выиграла</div>
                </td>
                <td class="rates__price">
                    <?= lot_cost(htmlspecialchars($win_lot['price'])); ?>
                </td>
                <td class="rates__time">
                    <?= get_time_bet(htmlspecialchars($win_lot['creation_time']), 'NOW'); ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($user_finish_lot as $finish_lot) : ?>
            <tr class="rates__item rates__item--end">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= htmlspecialchars($finish_lot['img']); ?>" width="54" height="40"
                             alt="<?= htmlspecialchars($finish_lot['lot_name']); ?>">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?id=<?= htmlspecialchars($finish_lot['lot_id']); ?>">
                            <?= htmlspecialchars($finish_lot['lot_name']); ?></a></h3>
                </td>
                <td class="rates__category">
                    <?= htmlspecialchars($finish_lot['cat_name']); ?>
                </td>
                <td class="rates__timer">
                    <div class="timer timer--end">Торги окончены</div>
                </td>
                <td class="rates__price">
                    <?= lot_cost(htmlspecialchars($finish_lot['price'])); ?>
                </td>
                <td class="rates__time">
                    <?= get_time_bet(htmlspecialchars($finish_lot['creation_time']), 'NOW'); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>
