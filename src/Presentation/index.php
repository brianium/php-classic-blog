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
$passwordHasher = new PasswordHasher();
$authenticator = new UserAuthenticator($userRepo, $passwordHasher);

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
            //make user object for logged in user?
            break;
        }
    }
    $unitOfWork->begin();
});

$app->hook('slim.after', function() use($app, $unitOfWork) {
    $unitOfWork->commit();
});

$app->get('/login', function() use($app) {
    
});

$app->post('/login', function() use($app){

});

$app->get('/register', function() use($app) {
    $app->render('register.phtml');
});

$app->post('/register', function() use($app, $userRepo, $authenticator) {
    $input = Input\User::create($app->request()->post('user'), $userRepo);
    if($input->isValid()) {
        $user = Entities\User::create($input->username, $input->password);
        $authenticator->initNewUser($user);
        $userRepo->store($user);
        $app->setCookie('superblorg', $user->getIdentifier() . ':' . $user->getToken());
        $app->response()->redirect('/dashboard', 303);
    }
    $app->render('register.phtml', array('user' => $input));
});

#admin routes
$app->get('/admin', function() use($app) {
    //list recent posts with comment count? links to add/delete posts
});

$app->get('/admin/post', function() use($app) {
    //form to add new post
});

$app->post('/admin/post', function() use($app) {
    //create new post and redirect back to /admin
});

#public routes
$app->get('/user/:id/posts', function($id) use($app) {
    //view posts for a given user
});

$app->get('/posts/:id', function($id) use($app) {
    //display single post and comment form
});

$app->post('/post/:pid/comments', function() use($app) {
    //add comment to post - probably view form for individual post
});

$app->run();