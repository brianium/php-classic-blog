<?php
define('AUTHCOOKIE', 'superblorg');

use Infrastructure\Persistence\Doctrine\UnitOfWork;
use Presentation\Services\SlimAuthenticationService;
use Infrastructure\Persistence\Doctrine\UserRepository;
use Domain\UserAuthenticator;
use Domain\PasswordHasher;

$app = new Slim(array(
    'view' => 'TwigView',
    'templates.path' => dirname(dirname(__FILE__)) . DS . 'Views'
));

//common objects
$unitOfWork = new UnitOfWork();
$userRepo = new UserRepository();
$authService = new SlimAuthenticationService($app, $userRepo, new UserAuthenticator($userRepo, new PasswordHasher()));

$app->hook('slim.before', function() use($app, $authService, $unitOfWork){
    if(!$authService->isAuthenticated(AUTHCOOKIE))
        $app->response()->redirect('/login', 303);
    if($user = $authService->getLoggedInUser(AUTHCOOKIE))
        $authService->regenerateUserCookie(AUTHCOOKIE, $user);

    $unitOfWork->begin();
});

$app->hook('slim.after', function() use($app, $unitOfWork) {
    $unitOfWork->commit();
});