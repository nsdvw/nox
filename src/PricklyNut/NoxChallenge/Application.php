<?php
namespace PricklyNut\NoxChallenge;

use PricklyNut\NoxChallenge\DI\Container;

class Application
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Routes map
     * @var array
     */
    protected $map = array();

    /**
     * @var array
     */
    protected $config = array();

    public function __construct($config = array())
    {
        $this->container = new Container();
        $this->config = $config;

        $this->resolveLanguage();
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->config['lang'];
    }

    /**
     * @param $template
     * @param array $args
     */
    public function render($template, array $args)
    {
        extract($args);
        ob_start();
        require $this->config['templateDir'] . DIRECTORY_SEPARATOR . $template;
        $html = ob_get_clean();
        echo $html;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function addRoute($uri, $handler)
    {
        $this->map[$uri] = $handler;
    }

    public function getMap()
    {
        return $this->map;
    }

    public function getNotFoundHandler()
    {
        return $this->map['notFoundHandler'];
    }

    public function setNotFoundHandler($handler)
    {
        return $this->map['notFoundHandler'] = $handler;
    }

    protected function resolveLanguage()
    {
        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];
            if (in_array($lang, $this->getSupportedLanguages())) {
                setcookie('lang', $lang, time() + 3600*24*90, '/');
            } else {
                $lang = 'ru';
            }
        } elseif (isset($_COOKIE['lang'])) {
            $lang = $_COOKIE['lang'];
        } else {
            $lang = 'ru';
        }
        $this->config['lang'] = $lang;
    }

    protected function getSupportedLanguages()
    {
        return array('ru', 'en');
    }
}
