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
    <form class="form container <?=!empty($errors) ? "form--invalid" : ""?>" 
        action="sign-up.php" method="POST" autocomplete="off">
        <?php 
        $fields = ['email', 'password', 'name', 'message'];
        foreach ($fields as $field) {
            $errors[$field] = $errors[$field] ?? "";
            $classname[$field] = $errors[$field] ? "form__item--invalid" : "";
            $value[$field] = $sign_up[$field] ?? ""; 
        }
        ?>
        <h2>Регистрация нового аккаунта</h2>
        <?php $field = 'email'; ?>
        <div class="form__item <?=$classname[$field]?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" 
                placeholder="Введите e-mail" value="<?=$value[$field]?>">
            <span class="form__error"><?=$errors[$field]?></span>
        </div>
        <?php $field = 'password'; ?>
        <div class="form__item <?=$classname[$field]?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" 
                placeholder="Введите пароль" value="<?=$value[$field]?>">
            <span class="form__error"><?=$errors[$field]?></span>
        </div>
        <?php $field = 'name'; ?>
        <div class="form__item <?=$classname[$field]?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" 
                placeholder="Введите имя" value="<?=$value[$field]?>">
            <span class="form__error"><?=$errors[$field]?></span>
        </div>
        <?php $field = 'message'; ?>
        <div class="form__item <?=$classname[$field]?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" 
                placeholder="Напишите как с вами связаться"><?=$value[$field]?></textarea>
            <span class="form__error"><?=$errors[$field]?></span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>
  </main>