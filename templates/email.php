<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= htmlspecialchars($winner['user_name'])?></p>
<p>
    Ваша ставка для лота <a href="<?= $base_url; ?>/lot.php?id=<?= htmlspecialchars($winner['lot_id']) ;?>">
        <?= htmlspecialchars($winner['lot_name']) ;?></a> победила.
</p>
<p>Перейдите по ссылке <a href="<?= $base_url; ?>/my-bets.php">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет-Аукцион "YetiCave"</small>
