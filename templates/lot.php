<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $cat): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?=$cat['name']?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?=$lot['title']?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                  <img src="../<?=$lot['img_url']?>" width="730" height="548" alt="Фото лота">
                </div>
                <p class="lot-item__category">Категория: <span><?=$lot['category']?></span></p>
                <p class="lot-item__description"><?=$lot['description']?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer <?=calc_hour($lot['date_end']) < 1 ? "timer--finishing" : ""?>">
                      <?=format_timer($lot['date_end'])?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                          <span class="lot-item__amount">Текущая цена</span>
                          <span class="lot-item__cost"><?=format_cost($lot['cost'])?></span>
                        </div>
                        <div class="lot-item__min-cost">
                          Мин. ставка <span><?=format_cost($lot['bet_min']) . ' р'?></span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user'])) : 
                    $_SESSION['lot']['id'] = $lot['id'];
                    $_SESSION['lot']['bet_min'] = $lot['bet_min'];
                    ?>
                    <form class="lot-item__form" action="lot.php" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item <?=isset($bet['errors']) ? "form__item--invalid" : ""?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="0" value="<?=$bet['cost'] ?? ""?>">
                            <span class="form__error"><?=$bet['errors'] ?? ""?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <?php endif; ?>
                <div class="history">
                    <h3>История ставок (<span><?=count($bets_lot)?></span>)</h3>
                    <?php if (count($bets_lot)) : ?>
                    <table class="history__list">
                    <?php foreach ($bets_lot as $bet): ?>
                        <tr class="history__item">
                            <td class="history__name"><?=$bet['name']?></td>
                            <td class="history__price"><?=format_cost($bet['cost'])  . ' р'?></td>
                            <td class="history__time"><?=formatDateBet($bet['date_add'])?></td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                    <?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</main>



