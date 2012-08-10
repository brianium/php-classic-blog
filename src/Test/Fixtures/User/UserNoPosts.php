<?php
namespace Test\Fixtures\User;
use Domain\Entities\User;
class UserNoPosts extends User
{
    public function __construct()
    {
        parent::__construct();
        $this->id = 1;
        $this->username = 'johnny.test';
        $this->password = 'password';
        $this->identifier = 'some_id';
        $this->token = 'some_token';
        $this->timeout = 10;
        $this->date = \DateTime::createFromFormat('m/d/Y', '06/20/1986');
    }
}