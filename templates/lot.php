<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $category): ?>
                <li class="nav__item">
                    <a href="pages/all-lots.html"><?=$category['name']?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?=$lot['title']?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                  <img src="../<?=$lot['img_url']?>" width="730" height="548" alt="Сноуборд">
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
                          <span class="lot-item__cost"><?=format_cost($lot['start_cost'])?></span>
                        </div>
                        <div class="lot-item__min-cost">
                          Мин. ставка <span><?=format_cost($lot['start_cost'] + $lot['step_bet'])?></span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user'])) : ?>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item form__item--invalid">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="0">
                            <span class="form__error">Введите наименование лота</span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>



