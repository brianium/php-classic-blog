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
            return $v->require()
                and $v->matchesInput('password');
        });
    }

    public function setRepository(UserRepository $repo)
    {
        $this->repository = $repo;
        return $this;
    }

    public function validateUniqueUser($v)
    {
        if(is_null($this->repository))
            throw new \RuntimeException('UserRepository must be set for unique validation');

        $var = $v->get();
        if($this->repository->getBy(['username' => $var])) {
            $v->setError('nonUnique');
            return false;
        }

        return true;
    }

    protected function setValidationClass($success)
    {
        $class = ($success) ? 'success' : 'error';
        $this->data['valid'] = $class;
    }

    protected function setDefaultMessages()
    {
        $this->messages = [
            'username.nonEmpty' => 'Username is required',
            'username.nonUnique' => 'Username is already in use',
            'username.atMostChars' => "Username can't exceed 50 chars",
            'password.nonEmpty' => 'Password is required',
            'passwordConfirm.matchesInput' => "Passwords don't match"
        ];
    }
}