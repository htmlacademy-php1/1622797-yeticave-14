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
  <section class="lot-item container">
    <h2><?= htmlspecialchars($lot['name']) ?></h2>
    <div class="lot-item__content">
      <div class="lot-item__left">
        <div class="lot-item__image">
          <img src="../<?= htmlspecialchars($lot['img']) ?>" width="730" height="548" alt="">
        </div>
        <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category']) ?></span></p>
        <p class="lot-item__description"><?= htmlspecialchars($lot['description']) ?></p>
      </div>
        <?php if (!empty($user_id)) : ?>
      <div class="lot-item__right">
          <div class="lot-item__state">
            <?php $interval = get_dt_range(htmlspecialchars($lot['date_completion']), date('H:i')); ?>
            <div class="lot__timer timer <?php if ($interval['hour'] < 1) {echo 'timer--finishing';} ?>">
              <?= str_pad($interval['hour'], 2, '0', STR_PAD_LEFT) ?>:
                <?= str_pad($interval['minute'], 2, '0', STR_PAD_LEFT) ?>
            </div>
            <div class="lot-item__cost-state">
                <?php $cur_price = $lot['max_price'] ?? $lot['begin_price']; ?>
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?= lot_cost(htmlspecialchars($cur_price)); ?></span>
              </div>
              <div class="lot-item__min-cost">
                  <?php $min_bet = $cur_price + $lot['bid_step']; ?>
                Мин. ставка <span><?= lot_cost(htmlspecialchars($min_bet)); ?></span>
              </div>
            </div>
              <?php $last_bets_user = $lot_bets[0]['user_id'] ?? ""; ?>
              <?php if (!hidden_bets_form($date_completion, 'NOW', $user_id, $lot_creator, $last_bets_user)) : ?>
            <form class="lot-item__form" action="lot.php?id=<?= $lot_id; ?>" method="post" autocomplete="off">
                <?php $classname = !empty($errors['price']) ? "form__item--invalid" : "" ?>
                <p class="lot-item__form-item form__item <?= $classname; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="price" placeholder="<?= lot_cost(htmlspecialchars($min_bet)); ?>">
                <span class="form__error"><?= $errors['price'] ?? ""; ?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
              <?php endif; ?>
          </div>
        <?php endif; ?>
        <div class="history">
          <h3>История ставок (<span><?= count($lot_bets); ?></span>)</h3>
            <?php foreach ($lot_bets as $bets) : ?>
          <table class="history__list">
            <tr class="history__item">
              <td class="history__name"><?= htmlspecialchars($bets['name']); ?></td>
              <td class="history__price"><?= htmlspecialchars($bets['price']); ?></td>
              <td class="history__time"><?= get_time_bet(htmlspecialchars($bets['creation_time']), 'NOW'); ?></td>
            </tr>
          </table>
            <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
</main>
