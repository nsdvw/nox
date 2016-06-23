<?php

use PricklyNut\NoxChallenge\Application;
use PricklyNut\NoxChallenge\Entity\User;
use PricklyNut\NoxChallenge\Form\LoginForm;
use PricklyNut\NoxChallenge\Form\RegisterForm;
use PricklyNut\NoxChallenge\Helper\Generator;
use PricklyNut\NoxChallenge\Mapper\UserMapper;
use PricklyNut\NoxChallenge\Router\Router;
use PricklyNut\NoxChallenge\Service\LoginManager;
use PricklyNut\NoxChallenge\Validator\Validator;

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    require dirname(__DIR__) . DIRECTORY_SEPARATOR
        . 'src' . DIRECTORY_SEPARATOR . $class . '.php';
});

mb_internal_encoding('utf8');

$app = new Application(
    array('templateDir' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'views')
);

$config = parse_ini_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . "config.ini");

$dic = $app->getContainer();
$dic->lang = $app->getLang();

$dic->connection = $dic->asShared(function ($c) use ($config) {
    $dsn = $config['dsn'];
    $user = $config['user'];
    $password = $config['password'];
    $connection = new \PDO($dsn, $user, $password);
    $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $connection;
});

$dic->userMapper = $dic->asShared(function ($c) {
    return new UserMapper($c->connection);
});

$dic->loginManager = $dic->asShared(function ($c) {
    return new LoginManager($c->userMapper, $c->lang);
});

$dic->validator = $dic->asShared(function ($c) {
    return new Validator($c->lang);
});

$app->addRoute('/login', function () use ($app) {
    $loginManager = $app->getContainer()->loginManager;
    $validator = $app->getContainer()->validator;

    $user = new User();
    $user->disableFieldsValidation(array('name', 'surname'));
    $form = new LoginForm($loginManager, $user);

    $form->handleRequest();

    if ($form->isSubmitted() and $form->isValid($validator)) {
        $loginManager->login($user, $form->getRememberMe());
        header('Location: /profile');
    }

    $app->render(
        'login.tpl.php',
        array(
            'lang' => $app->getContainer()->lang,
            'form' => $form,
            'uri' => $_SERVER['REQUEST_URI'],
        )
    );
});

$app->addRoute('/register', function () use ($app) {
    $loginManager = $app->getContainer()->loginManager;
    $validator = $app->getContainer()->validator;
    $mapper = $app->getContainer()->userMapper;

    $user = new User();
    $form = new RegisterForm($loginManager, $user);

    $form->handleRequest();

    if ($form->isSubmitted() and $form->isValid($validator)) {
        $user->setSalt(Generator::generateString());
        $hash = Generator::generateSaltedHash(
            $user->getSalt(),
            $form->getPassword()
        );
        $user->setHash($hash);

        $mapper->insert($user);
        $loginManager->login($user, $form->getRememberMe());
        header('Location: /profile');
    }

    $app->render(
        'register.tpl.php',
        array(
            'lang' => $app->getContainer()->lang,
            'form' => $form,
            'uri' => $_SERVER['REQUEST_URI'],
        )
    );
});

$app->addRoute('/profile', function () use ($app) {
    $loginManager = $app->getContainer()->loginManager;
    $validator = $app->getContainer()->validator;
    $mapper = $app->getContainer()->userMapper;

    if (!$user = $loginManager->getLoggedUser()) {
        header('HTTP/1.0 403 Forbidden');
        echo "<h1>You have no permissions</h1>";
        exit;
    }

    $form = new RegisterForm($loginManager, $user);
    $form->handleRequest();

    if ($form->isSubmitted() and $form->isValid($validator)) {
        $mapper->update($user);
        header('Location: /profile');
    }

    $app->render('register.tpl.php',
        array(
            'lang' => $app->getContainer()->lang,
            'form' => $form,
            'uri' => $_SERVER['REQUEST_URI'],
        )
    );
});

$app->setNotFoundHandler(function () use ($app) {
    header('HTTP/1.0 404 Not Found');
    echo "<h1>Page not found</h1>";
});

$controller = Router::resolveController($app);
$controller();
