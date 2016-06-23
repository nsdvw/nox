<!doctype html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <form action="<?= htmlspecialchars($uri) ?>" name="loginForm" method="POST"
        id="loginForm">
        <div>
            <label><?= $form->getLabels()[$lang]['email'] ?>
                <input type="text" name="loginForm[email]"
                       value="<?= $form->getUser()->getEmail() ?>">
            </label>
        </div>
        <div>
            <label><?= $form->getLabels()[$lang]['password'] ?>
                <input type="password" name="loginForm[password]"
                       value="">
            </label>
        </div>
        <div class="errors">
            <ul>
            <?php foreach($form->getErrors() as $errors): ?>
                <li>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach ?>
                    </ul>
                </li>
            <?php endforeach ?>
            </ul>
        </div>
        <div>
            <input type="submit" value="<?= $form->getLabels()[$lang]['submit'] ?>">
        </div>
    </form>
    <a href="<?= '/login?lang=en' ?>">English</a>
    <a href="<?= '/login?lang=ru' ?>">Русский</a>
</body>
</html>
