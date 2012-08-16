<?php
namespace Presentation\Models\Input;
use Fuel\Validation\Base;
class User
{
    protected $data;
    protected $validator;

    public function __construct($data) {
        $this->data = $data;
        $this->validator = new Base();
    }

    public function isValid()
    {
        $this->validator->validate('username', function($v){
            return $v->require();
        })->validate('password', function($v){
            return $v->require();
        })->validate('passwordConfirm', function($v){
            return $v->require();
        });

        return $this->validator->execute($this->data);
    }

    public function getMessage($key)
    {
        return 'string';
    }
}