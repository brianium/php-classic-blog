<?php
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';
define('APP', dirname(__FILE__) . DS . 'App' . DS);

use Domain\Entities;
use Presentation\Models\Input;

//include app routes and hooks
require APP . DS . 'init.php';
require APP . DS . 'admin.php';
require APP . DS . 'auth.php';
require APP . DS . 'posts.php';

$app->run();