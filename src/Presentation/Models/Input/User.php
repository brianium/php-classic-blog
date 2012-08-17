<?php
namespace Presentation\Models\Input;
use Domain\Repositories\IUserRepository;
class User extends InputModel
{
    protected $repository;

    protected function initValidation()
    {
        $this->validator->validate('username', function($v){
            return $v->require()
                and $v->atMostChars(50);
        })->validate('password', function($v){
            return $v->require();
        })->validate('passwordConfirm', function($v){
            return $v->require();
        });
    }

    public function setRepository(IUserRepository $repo)
    {
        $this->repository = $repo;
        return $this;
    }
}