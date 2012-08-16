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
$namespaces = array_fill_keys(['Domain', 'Infrastructure', 'Test', 'Presentation'], APP_SRC);
foreach($namespaces as $namespace => $path) {
    $loader->add($namespace, $path);
}

$classes = [
    'TwigView' => $lib . DS . '/slim/extras/Views/TwigView.php',
    'Slim_View' => $lib . DS . '/slim/slim/Slim/View.php' 
];

$map = [];
foreach($classes as $class => $path) {
    if(file_exists($path))
        $map[$class] = $path;
}

$loader->addClassMap($map);