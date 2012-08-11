<?php
if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

define('APP_SRC', dirname(__FILE__));

$root = dirname(dirname(__FILE__));
$lib = $root . DS . 'lib';

//require composer autoloader
require_once $lib . DS . 'autoload.php';

//get composer loader that already has vendors loaded
$loader = ComposerAutoloaderInit::getLoader();

//load application namespaces
$namespaces = array_fill_keys(['Domain', 'Infrastructure', 'Test'], APP_SRC);
foreach($namespaces as $namespace => $path) {
    $loader->add($namespace, $path);
}

