<?php
namespace Domain;
use Domain\Entities\User;
use Domain\Repositories\UserRepository;
use Domain\PasswordHasher;
class UserAuthenticator
{
    protected $user;
    protected $repo;
    protected $hasher;

    public function __construct(
        User $user, 
        UserRepository $repo,
        PasswordHasher $hasher)
    {
        $this->user = $user;
        $this->repo = $repo;
        $this->hasher = $hasher;
    }

    public function isAuthenticated($password)
    {
        $exists = $this->repo->contains($this->user);

        if(!$exists) return false;

        return $this->hasher->checkPassword($this->user->getPassword(), $password);
    }

    public function hashPassword()
    {
        $hashed = $this->hasher->hash($this->user->getPassword());
        $this->user->setPassword($hashed);
    }

    public function initNewUser()
    {
        if(!$this->user->isNew())
            throw new \RuntimeException('Cannot initialize existing user');
        
        $this->hashPassword();
        $this->user->refresh();
    }
}