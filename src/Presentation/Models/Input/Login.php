<?php
namespace Presentation\Models\Input;
use Domain\Repositories\UserRepository;
class Login extends InputModel
{

    protected function initValidation()
    {
        $this->validator->validate('username', function($v){
            return $v->require();
        })->validate('password', function($v){
            return $v->require();
        });
    }

    protected function setDefaultMessages()
    {
        $this->messages = [
            'username.nonEmpty' => 'Username is required',
            'password.nonEmpty' => 'Password is required'
        ];
    }
}