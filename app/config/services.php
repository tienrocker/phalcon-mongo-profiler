<?php
/**
 * Services are globally registered in this file
 *
 * @var \Phalcon\Config $config
 */

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;

/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

/**
 * Phalcon profiler
 */
$di->set('profiler', function () use ($di, $config) {
    if (isset($config->application->debug) && (bool)$config->application->debug === true) {
        return new \Fabfuel\Prophiler\Profiler();
    }
    return null;
}, true);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);

/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_'
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter($config->database->toArray());
}, true);

/**
 * Mongo Database connection
 */
$di->set('mongo', function () use ($config) {
    $conn = sprintf('mongodb://%s:%s@%s:%s', $config->mongodb->username, $config->mongodb->password, $config->mongodb->host, $config->mongodb->port);
    $mongo = new \MongoClient($conn);
    return $mongo->selectDb($config->mongodb->dbname);
}, true);

/**
 * Collection manager for Mongo Database
 */
$di->set('collectionManager', function () {
    return new Phalcon\Mvc\Collection\Manager();
}, true);

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
}, true);

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function () {
    $session = new SessionAdapter();
    $session->start();

    return $session;
}, true);

/**
 * Set profiler event handler
 */
$profiler = &$di->get('profiler');
if (!empty($profiler)) {
    $profiler->addAggregator(new \Fabfuel\Prophiler\Aggregator\Database\QueryAggregator());
    $profiler->addAggregator(new \Fabfuel\Prophiler\Aggregator\Cache\CacheAggregator());
    $pluginManager = new \Fabfuel\Prophiler\Plugin\Manager\Phalcon($profiler);
    $pluginManager->register();
}