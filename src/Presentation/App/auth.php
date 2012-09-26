<?php
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