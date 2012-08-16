<?php
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';

//TwigView::$twigDirectory = 

$app = new Slim(array(
    'view' => 'TwigView',
    'templates.path' => dirname(__FILE__) . DS . 'Views'
));

$app->get('/register', function() use($app) {
    $app->render('register.phtml');
});

$app->run();