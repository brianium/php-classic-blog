<?php
namespace Presentation\Models\Input;
use Domain\Repositories\UserRepository;
class User extends InputModel
{
    protected $repository;

    public function __construct($data, $msgs = [])
    {
        parent::__construct($data, $msgs);
        $this->validator->addRuleSet($this);
    }

    protected function initValidation()
    {
        $this->validator->validate('username', function($v){
            return $v->require()
                and $v->atMostChars(50)
                and $v->uniqueUser();
        })->validate('password', function($v){
            return $v->require();
        })->validate('passwordConfirm', function($v){
            return $v->require();
        });
    }

    public function setRepository(UserRepository $repo)
    {
        $this->repository = $repo;
        return $this;
    }

    public function validateUniqueUser($v)
    {
        $v->setError('nonUnique');
        return false;
    }
}