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
        action="login.php" method="post">
        <h2>Вход</h2>
        <?php
        $classname = isset($errors['email']) ? "form__item--invalid" : "";
        $err_message = $errors['email'] ?? "";
        $value = $login['email'] ?? ""; 
        ?>
        <div class="form__item <?=$classname?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" 
                placeholder="Введите e-mail" value="<?=$value?>">
            <span class="form__error"><?=$err_message?></span>
        </div>
        <?php
        $classname = isset($errors['password']) ? "form__item--invalid" : "";
        $err_message = $errors['password'] ?? "";
        $value = $login['password'] ?? ""; 
        ?>
        <div class="form__item form__item--last <?=$classname?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" 
                placeholder="Введите пароль" value="<?=$value?>">
            <span class="form__error"><?=$err_message?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
  </main>
