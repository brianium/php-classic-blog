<?php
namespace Presentation\Models\Input;
use Domain\Repositories\UserRepository;
class Comment extends InputModel
{

    protected function initValidation()
    {
        $this->validator->validate('commenter_name', function($v){
            return $v->require();
        })->validate('commenter_email', function($v){
            return $v->require();
        })->validate('text', function($v){
            return $v->require();
        });
    }

    protected function setDefaultMessages()
    {
        $this->messages = [
            'commenter_name.nonEmpty' => 'Please provide your name',
            'commenter_email.nonEmpty' => 'Please provide an email',
            'text.nonEmpty' => 'Comment cannot be blank'
        ];
    }
}