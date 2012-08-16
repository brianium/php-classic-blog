<?php
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = new Slim(array(
    'view' => 'TwigView'
));

$app->get('/register', function() use($app) {
    $app->render('register.php');
});