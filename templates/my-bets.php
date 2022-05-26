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
            <?php foreach ($user_bets as $bet) : ?>
            <tr class="rates__item
                <?php if ($bet['winner_id'] === $bet['user_id']) :
                    ?>rates__item--win<?php
                endif;?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= htmlspecialchars($bet['img']); ?>"
                             width="54" height="40" alt="<?= htmlspecialchars($bet['lot_name']); ?>">
                    </div>
                    <?php if ($bet['winner_id'] === $bet['user_id']) : ?>
                        <div>
                            <h3 class="rates__title">
                                <a href="lot.php?id=<?= htmlspecialchars($bet['lot_id']); ?>">
                                    <?= htmlspecialchars($bet['lot_name']); ?>
                                </a>
                            </h3>
                            <p><?= htmlspecialchars($bet['contact']); ?></p>
                        </div>
                    <?php else : ?>
                        <h3 class="rates__title">
                            <a href="lot.php?id=<?= htmlspecialchars($bet['lot_id']); ?>">
                                <?= htmlspecialchars($bet['lot_name']); ?>
                            </a>
                        </h3>
                    <?php endif;?>
                </td>
                <td class="rates__category">
                    <?= htmlspecialchars($bet['cat_name']); ?>
                </td>

                <?php $interval = get_dt_range(htmlspecialchars($bet['date_completion']), 'NOW'); ?>
                <td class="rates__timer ">
                    <?php if ($bet['winner_id'] === $bet['user_id']) : ?>
                        <div class="timer timer--win">
                            Ставка выиграла
                        </div>

                    <?php elseif ($bet['winner_id'] === null && $interval > 1) : ?>
                        <div class="timer">
                            <?= str_pad($interval['hour'], 2, '0', STR_PAD_LEFT) ?>:
                            <?= str_pad($interval['minute'], 2, '0', STR_PAD_LEFT) ?>
                        </div>

                    <?php elseif ($bet['winner_id'] === null && $interval < 1) : ?>
                        <div class="timer timer--finishing">
                            <?= str_pad($interval['hour'], 2, '0', STR_PAD_LEFT) ?>:
                            <?= str_pad($interval['minute'], 2, '0', STR_PAD_LEFT) ?>
                        </div>

                    <?php else : ?>
                        <div class="timer timer--end">
                            Торги окончены
                        </div>
                    <?php endif;?>
                </td>
                <td class="rates__price">
                    <?= lot_cost(htmlspecialchars($bet['price'])); ?>
                </td>
                <td class="rates__time">
                    <?= get_time_bet(htmlspecialchars($bet['creation_time']), 'NOW'); ?>
                </td>
            <?php endforeach; ?>
        </table>
    </section>
</main>
