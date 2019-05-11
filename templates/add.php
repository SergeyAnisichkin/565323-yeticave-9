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
    <form class="form form--add-lot container <?=!empty($errors) ? "form--invalid" : ""?>" 
        method="POST" action="add.php" enctype="multipart/form-data">
        <?php 
        $fields = ['lot-name', 'category', 'message', 'file', 'lot-rate', 'lot-step', 'lot-date'];
        foreach ($fields as $field) {
            $errors[$field] = $errors[$field] ?? "";
            $classname[$field] = $errors[$field] ? "form__item--invalid" : "";
            $value[$field] = $sign_up[$field] ?? ""; 
        }
        ?>
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $field = 'lot-name'; ?>
            <div class="form__item <?=$classname[$field]?>">
                <label for="lot-name">Наименование <sup>*</sup></label>
                <input id="lot-name" type="text" name="lot-name" 
                    placeholder="Введите наименование лота" value="<?=$value[$field]?>">
                <span class="form__error"><?=$errors[$field]?></span>
            </div>
            <?php $field = 'category'; ?>
            <div class="form__item <?=$classname[$field]?>">
                <label for="category">Категория <sup>*</sup></label>
                <select id="category" name="category" value="<?=$value[$field]?>">
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $cat): ?>
                    <option <?=$lot[$field] == $cat['name'] ? "selected" : ""?>>
                        <?=$cat['name']?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?=$errors[$field]?></span>
            </div>
        </div>
        <?php $field = 'message'; ?>
        <div class="form__item form__item--wide <?=$classname[$field]?>">
            <label for="message">Описание <sup>*</sup></label>
            <textarea id="message" name="message" 
                placeholder="Напишите описание лота"><?=$value[$field]?></textarea>
            <span class="form__error"><?=$errors[$field]?></span>
        </div>
        <?php $field = 'file'; ?>
        <div class="form__item form__item--file <?=$classname[$field]?>">
            <label>Изображение <sup>*</sup></label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="lot-img" id="lot-img" value="">
                <label for="lot-img">
                    Добавить
                </label>
                <span class="form__error"><?=$errors[$field]?></span>
            </div>
        </div>
        <div class="form__container-three">
            <?php $field = 'lot-rate'; ?>
            <div class="form__item form__item--small <?=$classname[$field]?>">
                <label for="lot-rate">Начальная цена <sup>*</sup></label>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=$value[$field]?>">
                <span class="form__error"><?=$errors[$field]?></span>
            </div>
            <?php $field = 'lot-step'; ?>
            <div class="form__item form__item--small <?=$classname[$field]?>">
                <label for="lot-step">Шаг ставки <sup>*</sup></label>
                <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=$value[$field]?>">
                <span class="form__error"><?=$errors[$field]?></span>
            </div>
            <?php $field = 'lot-date'; ?>
            <div class="form__item <?=$classname[$field]?>">
                <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
                <input class="form__input-date" id="lot-date" value="<?=$value[$field]?>"
                    type="text" name="lot-date" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
                <span class="form__error"><?=$errors[$field]?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>