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
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
        <?php if (count($bets)) : ?>
            <?php foreach ($bets as $bet): ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../<?=$bet['img_url']?>" width="54" height="40" alt="Фото лота">
                    </div>
                    <h3 class="rates__title">
                        <a href="lot.php?id=<?=$bet['id']?>"><?=$bet['title']?></a>
                    </h3>
                </td>
                <td class="rates__category">
                    <?=$bet['title']?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?=calc_hour($bet['date_end']) < 1 ? "timer--finishing" : ""?>">
                        <?=format_timer($bet['date_end'])?>
                    </div>
                </td>
                <td class="rates__price">
                    <?=format_cost($bet['cost'])  . ' р'?>
                </td>
                <td class="rates__time">
                    <?=formatDateBet($bet['date_add'])?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </table>
    </section>
</main>