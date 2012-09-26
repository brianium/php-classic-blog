<?php
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';
define('APP', dirname(__FILE__) . DS . 'App' . DS);

//include app routes and hooks
require APP . DS . 'init.php';
require APP . DS . 'admin.php';
require APP . DS . 'auth.php';
require APP . DS . 'posts.php';

$app->get('/', function() use ($app, $postRepo){
    $latest = $postRepo->getLatest(10);
    $app->render('index.phtml', ['posts' => $latest]);
});

$app->run();