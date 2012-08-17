<?php
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Infrastructure\Persistence\Doctrine\UnitOfWork;
use Infrastructure\Persistence\Doctrine\UserRepository;
use Presentation\Models\Input;

$app = new Slim(array(
    'view' => 'TwigView',
    'templates.path' => dirname(__FILE__) . DS . 'Views'
));


//kick off the app with a new UnitOfWork to be started and committed in slim hooks
$unitOfWork = new UnitOfWork();

$app->hook('slim.before', function() use($app, $unitOfWork){
    putenv('APPLICATION_ENV=development');
    $unitOfWork->begin();
});

$app->hook('slim.after', function() use($app, $unitOfWork) {
    $unitOfWork->commit();
});

$app->get('/register', function() use($app) {
    $app->render('register.phtml');
});

$app->post('/register', function() use($app) {
    $input = new Input\User($app->request()->post('user'));
    if($input->isValid()) {
        
    }
    $app->render('register.phtml', array('user' => $input));
});

$app->run();