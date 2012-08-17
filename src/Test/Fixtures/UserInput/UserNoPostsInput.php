<?php
namespace Test\Fixtures\UserInput;
use Presentation\Models\Input\User;
use Test\Fixtures\User\UserNoPosts;
class UserNoPostsInput extends User
{
    public function __construct()
    {
        $user = new UserNoPosts();
        $data = [
        'username' => $user->getUsername(),
        'password' => $user->getPassword(), 
        'passwordConfirm' => $user->getPassword()];
        parent::__construct($data);
    }
}