<?php
use Domain\Entities;
use Presentation\Models\Input;
use Domain\Commenter;
use Domain\CommentSanitizer;
use Infrastructure\Persistence\Doctrine\CommentRepository;

$commentRepo = new CommentRepository();

$app->get('/users/:id/posts', function($id) use($app, $userRepo) {
    $user = $userRepo->get($id);
    if($user) $app->render('user_posts.phtml', ['user' => $user]);
    else $app->render('notfound.phtml',['message' => 'Could not find the user you were looking for'], 404);

});

$app->get('/posts/:id', function($id) use($app, $postRepo) {
    $post = $postRepo->get($id);
    if($post) $app->render('single_post.phtml', ['post' => $post]);
    else $app->render('notfound.phtml', ['message' => 'Could not find the post you were looking for']);
});

$app->post('/posts/:id', function($pid) use($app, $postRepo, $commentRepo) {
    $post = $postRepo->get($pid);
    $input = new Input\Comment($app->request()->post('comment'));
    $comment = null;
    if($input->isValid()) {
        $comment = Entities\Comment::create($input->text, new Domain\Commenter($input->commenter_name, $input->commenter_email, $input->commenter_url), $post);
        $sanitizer = new Domain\CommentSanitizer($comment);
        $comment->setText($sanitizer->sanitize());
        $commentRepo->store($comment);
    }
    $app->render('single_post.phtml', ['post' => $post, 'comment' => $input]);
});

$app->get('/users', function() use ($app, $userRepo) {
    $users = $userRepo->getAll();
    $app->render('users.phtml', ['users' => $users]);
});