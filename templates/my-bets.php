<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $cat): ?>
                <li class="nav__item">
                    <a href="lots-category.php?id=<?=$cat['id']?>"><?=$cat['name']?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
        <?php if (count($bets)) :
            foreach ($bets as $bet):
            $isWinBet = $bet['winner_user_id'] == $_SESSION['user']['id'] && $bet['cost'] == getMaxCostBets($link, $bet['id']);
            $isOver = date_create('now') > date_create($bet['date_end']);
            $classTr = $isOver ? "rates__item--end" : "";
            $classTr = $isWinBet ? "rates__item--win" : $classTr;
        ?>
            <tr class="rates__item <?=$classTr?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="../<?=$bet['img_url']?>" width="54" height="40" alt="Фото лота">
                    </div>
                    <div>
                        <h3 class="rates__title">
                            <a href="lot.php?id=<?=$bet['id']?>"><?=$bet['title']?></a>
                        </h3>
                        <?php if ($isWinBet) : ?>
                        <p><?=$bet['contacts']?></p>
                        <?php endif; ?>
                    </div>
                </td>

                <td class="rates__category">
                    <?=$bet['name']?>
                </td>

                <td class="rates__timer">
                    <?php if ($isWinBet) : ?>
                        <div class="timer timer--win">Ставка выиграла</div>
                    <?php elseif ($isOver) : ?>
                        <div class="timer timer--end">Торги окончены</div>
                    <?php else : ?>
                        <div class="timer <?=calc_hour($bet['date_end']) < 1 ? "timer--finishing" : ""?>">
                            <?=format_timer($bet['date_end'])?>
                        </div>
                    <?php endif; ?>
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