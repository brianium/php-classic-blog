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

//common objects
$unitOfWork = new UnitOfWork();
$userRepo = new UserRepository();

$protected = ['dashboard'];
$app->hook('slim.before', function() use($app, $unitOfWork, $protected, $userRepo){
    foreach($protected as $route) {
        if('/' . $route == $app->request()->getPath()) {
            $cookie = $app->getCookie('superblorg');
            if(!$cookie)
                $app->response()->redirect('/login', 303);

            list($identifier, $token) = explode(':', $app->getCookie('superblorg'));
            if($identifier) {
                $users = $userRepo->getBy(['identifier' => $identifier]);
                if($users) {
                    $user = $users[0];
                    $now = time();
                    if($token != $user->getToken() || $now > $user->getTimeout()) {
                        $app->response()->redirect('/login', 303);
                    }
                }
            }
            break;
        }
    }
    $unitOfWork->begin();
});

$app->hook('slim.after', function() use($app, $unitOfWork) {
    $unitOfWork->commit();
});

$app->get('/login', function(){
    echo 'you gotta login and stuff';
});

$app->get('/register', function() use($app) {
    $app->render('register.phtml');
});

$app->post('/register', function() use($app, $userRepo) {
    $input = Input\User::create($app->request()->post('user'), $userRepo);
    if($input->isValid()) {
        $user = Entities\User::create($input->username, $input->password);
        $authenticator = new UserAuthenticator($user, $userRepo, new PasswordHasher());
        $authenticator->initNewUser();
        $userRepo->store($user);
        $app->setCookie('superblorg', $user->getIdentifier() . ':' . $user->getToken());
        $app->response()->redirect('/dashboard', 303);
    }
    $app->render('register.phtml', array('user' => $input));
});

$app->get('/dashboard', function() use ($app){
    echo $app->request()->getPath();
});

$app->run();