<!doctype html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <form enctype="multipart/form-data" action="<?= htmlspecialchars($uri) ?>"
          name="registerForm" method="POST" id="registerForm">
        <div>
            <label><?= $form->getLabels()[$lang]['name'] ?>
                <input type="text" name="registerForm[name]"
                       value="<?= $form->getUser()->getName() ?>">
            </label>
        </div>
        <div>
            <label><?= $form->getLabels()[$lang]['surname'] ?>
                <input type="text" name="registerForm[surname]"
                       value="<?= $form->getUser()->getSurname() ?>">
            </label>
        </div>
        <div>
            <label><?= $form->getLabels()[$lang]['email'] ?>
                <input type="text" name="registerForm[email]"
                       value="<?= $form->getUser()->getEmail() ?>">
            </label>
        </div>
        <div>
            <label><?= $form->getLabels()[$lang]['password'] ?>
                <input type="password" name="registerForm[password]"
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
    <a href="<?= '/register?lang=en' ?>">English</a>
    <a href="<?= '/register?lang=ru' ?>">Русский</a>
</body>
</html>
