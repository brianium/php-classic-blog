<?php
namespace Presentation\Models\Input;
use Domain\Repositories\UserRepository;
class Post extends InputModel
{

    protected function initValidation()
    {
        $this->validator->validate('title', function($v){
            return $v->require();
        })->validate('content', function($v){
            return $v->require();
        });
    }

    protected function setDefaultMessages()
    {
        $this->messages = [
            'title.nonEmpty' => 'Title is required',
            'content.nonEmpty' => 'Content is required'
        ];
    }
}