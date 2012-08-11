<?php
namespace Test\Fixtures\User;
use Domain\Entities\User;
trait UserFixture
{
    public function getAsUser()
    {
        $user = new User();
        $user->setUsername($this->getUsername());
        $user->setPassword($this->getPassword());
        $user->setIdentifier($this->getIdentifier());
        $user->setToken($this->getToken());
        $user->setTimeout($this->getTimeout());
        $user->setDate($this->getDate());
        return $user;
    }

    abstract public function getUsername();
    abstract public function getPassword();
    abstract public function getIdentifier();
    abstract public function getToken();
    abstract public function getTimeout();
    abstract public function getDate();
}