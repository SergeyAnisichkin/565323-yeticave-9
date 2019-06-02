<main>
    <nav class="nav">
        <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item <?=$cat['name'] == $category_name ? "nav__item--current" : ""?>">
                <a href="lots-category.php?id=<?=$cat['id']?>"><?=$cat['name']?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«<?=$category_name ?? ""?>»</span></h2>
            <?php if (count($lots_category)) : ?>
            <ul class="lots__list">
            <?php foreach ($lots_category as $lot): ?>
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
        <?php if ($count_pages > 1) : ?>
        <ul class="pagination-list">
            <?php if ($page_number !== 1) : ?>
            <li class="pagination-item pagination-item-prev">
                <a href="lots-category.php?id=<?=$cat_id?>&page=<?=$page_number - 1?>">Назад</a>
            </li>
            <?php endif; ?>
            <?php for($i = 1; $i <= $count_pages; $i++):?>
            <li class="pagination-item <?=$page_number == $i ? "pagination-item-active" : ""?>">
                <a href="lots-category.php?id=<?=$cat_id?>&page=<?=$i?>"><?=$i?></a>
            </li>
            <?php endfor; ?>
            <?php if ($count_pages > $page_number) : ?>
            <li class="pagination-item pagination-item-next">
                <a href="lots-category.php?id=<?=$cat_id?>&page=<?=$page_number + 1?>">Вперед</a>
            </li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>
    </div>
</main>

</div>