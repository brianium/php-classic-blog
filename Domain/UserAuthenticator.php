<?php
namespace Domain;
use Domain\Entities\User;
class UserAuthenticator
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}