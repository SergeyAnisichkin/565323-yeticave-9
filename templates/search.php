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
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?=$find ?? ""?></span>»</h2>
            <?php if (count($lots)) : ?>
            <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?=$lot['img_url']?>" width="350" height="260" alt="Фото лота">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?=$lot['category']?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="lot.php?id=<?=$lot['id']?>">
                                <?=$lot['title']?>
                            </a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?=format_cost($lot['start_cost'])?><b class="rub">р</b></span>
                            </div>
                            <div class="lot__timer timer<?=calc_hour($lot['date_end']) < 1 ? "timer--finishing" : ""?>">
                                <?=format_timer($lot['date_end'])?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </section>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item"><a href="#">3</a></li>
            <li class="pagination-item"><a href="#">4</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
    </div>
</main>