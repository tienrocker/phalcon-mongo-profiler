<?php

error_reporting(E_ALL & ~E_STRICT);

define('APP_PATH', realpath('..'));
define('ENV', 'development');

try {

    /*
     * Composer autoloader
     */
    require APP_PATH . "/vendor/autoload.php";

    /**
     * Read the configuration
     */
    $config = include APP_PATH . "/app/config/config.php";

    /**
     * Read auto-loader
     */
    include APP_PATH . "/app/config/loader.php";

    /**
     * Read services
     */
    include APP_PATH . "/app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    $content = $application->handle()->getContent();

    /**
     * Add Phalcon profiler toolbar
     */
    if ($profiler !== null) {
        session_commit();
        $toolbar = new \Fabfuel\Prophiler\Toolbar($di->get('profiler'));
        $toolbar->addDataCollector(new \Fabfuel\Prophiler\DataCollector\Request());
        $content = str_replace('</body>', $toolbar->render() . '</body>', $content);
    }

    /**
     * Write html content
     */
    echo $content;

} catch (\Exception $e) {
    throw $e;
    echo $e->getMessage();
}
