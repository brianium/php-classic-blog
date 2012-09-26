<?php
require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Infrastructure\Persistence\Doctrine\UnitOfWork;
use Infrastructure\Persistence\Doctrine;
use Domain\Entities;
use Domain\UserAuthenticator;
use Domain\PasswordHasher;
use Domain\CommentSanitizer;
use Presentation\Models\Input;
use Presentation\Services\SlimAuthenticationService;

define('AUTHCOOKIE', 'superblorg');

$app = new Slim(array(
    'view' => 'TwigView',
    'templates.path' => dirname(__FILE__) . DS . 'Views'
));

//common objects
$unitOfWork = new UnitOfWork();
$userRepo = new Doctrine\UserRepository();
$authService = new SlimAuthenticationService($app, $userRepo, new UserAuthenticator($userRepo, new PasswordHasher()));

$app->hook('slim.before', function() use($app, $authService, $unitOfWork){
    if(!$authService->isAuthenticated(AUTHCOOKIE))
        $app->response()->redirect('/login', 303);

    $unitOfWork->begin();
});

$app->hook('slim.after', function() use($app, $unitOfWork) {
    $unitOfWork->commit();
});

$app->get('/login', function() use($app) {
    $app->render('login.phtml');
});

$app->post('/login', function() use($app, $authService, $userRepo){
    $input = new Input\Login($app->request()->post('login'));
    if($input->isValid()) {
        if($authService->canLogin($input->username, $input->password))
            $authService->login($userRepo->getByUsername($input->username), AUTHCOOKIE, function() use ($app){
                $app->response()->redirect('/admin', 303);
            });
        else $input->setMessageFor("username", "Invalid username or password");
    }
        
    $app->render('login.phtml', ['login' => $input]);
});

$app->get('/register', function() use($app) {
    $app->render('register.phtml');
});

$app->post('/register', function() use($app, $authService) {
    $input = Input\User::create($app->request()->post('user'), $userRepo);
    if($input->isValid())
        $authService->register(Entities\User::create($input->username, $input->password), AUTHCOOKIE, function() use($app){
            $app->response()->redirect('/admin', 303);
        });

    $app->render('register.phtml', ['user' => $input]);
});

#admin routes
$authService->addRoute('/^\/admin.*/');
$app->get('/admin', function() use($app) {
    //list recent posts with comment count? links to add/delete posts
});

$postRepo = new Doctrine\PostRepository();
$app->get('/admin/post', function() use($app, $postRepo, $authService) {
    $app->render('add_post.phtml', ['user_posts' => $postRepo->getBy(['user' => $authService->getLoggedInUser('superblorg')])]);
});

$app->post('/admin/post', function() use($app, $authService, $postRepo) {
    $input = new Input\Post($app->request()->post('post'));
    $user = $authService->getLoggedInUser('superblorg');
    $post = Entities\Post::create($input->title, $input->content, $input->excerpt, $user);
    if($input->isValid())
        $postRepo->store($post);

    $app->render('add_post.phtml', ['post' => $input, 'user_posts' => $postRepo->getBy(['user' => $user]), 'saved' => $post]);
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