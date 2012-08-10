<?php
if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

$root = dirname(dirname(__FILE__));
$lib = $root . DS . 'lib';

//require composer autoloader
require_once $lib . DS . 'autoload.php';

//get composer loader that already has vendors loaded
$loader = ComposerAutoloaderInit::getLoader();

//load application namespaces
$namespaces = array_fill_keys(['Domain', 'Test'], dirname(__FILE__));
foreach($namespaces as $namespace => $path) {
    $loader->add($namespace, $path);
}

