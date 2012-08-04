<?php
if(!defined('DS'))
	define('DS', DIRECTORY_SEPARATOR);

$root = dirname(dirname(__FILE__));
$vendors = $root . DS . 'vendors';

//require composer autoloader
require_once $vendors . DS . 'autoload.php';

//load app namespaces
$namespaces = require_once $root . DS . 'namespaces.php';

//get composer loader that already has vendors loaded
$loader = ComposerAutoloaderInit::getLoader();

//load application namespaces
foreach($namespaces as $namespace => $path) {
	$loader->add($namespace, $path);
}

