
<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php foreach ($categories as $cat): ?>
                <li class="promo__item promo__item--<?=$cat['code']?>">
                    <a class="promo__link" href="lots-category.php?id=<?=$cat['id']?>"><?=$cat['name']?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$lot['img_url']?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$lot['category']?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="lot.php?id=<?=$lot['id']?>"><?=htmlspecialchars($lot['title'])?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount"><?=$lot['bets']?></span>
                                <span class="lot__cost"><?=format_cost($lot['cost'])?><b class="rub"> р</b></span>
                            </div>
                            <div class="lot-item__timer timer <?=calc_hour($lot['date_end']) < 1 ? "timer--finishing" : ""?>">
                                <?=format_timer($lot['date_end'])?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</main>