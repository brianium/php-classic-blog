<?php
use Infrastructure\Persistence\Doctrine\PostRepository;

$authService->addRoute('/^\/admin.*/');
$app->get('/admin', function() use($app) {
    //list recent posts with comment count? links to add/delete posts
});

$postRepo = new PostRepository();
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