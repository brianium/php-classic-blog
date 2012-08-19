<?php
namespace Domain;
use Domain\Entities\User;
use Domain\Repositories\UserRepository;
use Domain\PasswordHasher;
class UserAuthenticator
{
    protected $repo;
    protected $hasher;

    public function __construct(
        UserRepository $repo,
        PasswordHasher $hasher)
    {
        $this->repo = $repo;
        $this->hasher = $hasher;
    }

    public function isAuthenticated($username, $password)
    {
        $users = $this->repo->getBy(['username' => $username]);

        if(!$users) return false;

        return $this->hasher->checkPassword($users[0]->getPassword(), $password);
    }

    public function hashPassword(User $user)
    {
        $hashed = $this->hasher->hash($user->getPassword());
        $user->setPassword($hashed);
    }

    public function initNewUser(User $user)
    {
        if(!$user->isNew())
            throw new \RuntimeException('Cannot initialize existing user');
        
        $this->hashPassword($user);
        $user->refresh();
    }
}