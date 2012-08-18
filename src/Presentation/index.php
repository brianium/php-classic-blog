<?php
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Infrastructure\Persistence\Doctrine\UnitOfWork;
use Infrastructure\Persistence\Doctrine\UserRepository;
use Domain\Entities;
use Domain\UserAuthenticator;
use Domain\PasswordHasher;
use Presentation\Models\Input;

$app = new Slim(array(
    'view' => 'TwigView',
    'templates.path' => dirname(__FILE__) . DS . 'Views'
));


//kick off the app with a new UnitOfWork to be started and committed in slim hooks
$unitOfWork = new UnitOfWork();

$app->hook('slim.before', function() use($app, $unitOfWork){
    $unitOfWork->begin();
});

$app->hook('slim.after', function() use($app, $unitOfWork) {
    $unitOfWork->commit();
});

$app->get('/register', function() use($app) {
    $app->render('register.phtml');
});

$app->post('/register', function() use($app) {
    $repo = new UserRepository();
    $input = new Input\User($app->request()->post('user'));
    $input->setRepository($repo);
    if($input->isValid()) {
        $user = Entities\User::create($input->username, $input->password);
        $authenticator = new UserAuthenticator($user, $repo, new PasswordHasher());
        $authenticator->initNewUser();
        $repo->store($user);
    }
    $app->render('register.phtml', array('user' => $input));
});

$app->run();