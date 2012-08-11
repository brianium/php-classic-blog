<?php
namespace Test\Fixtures\User;
use Domain\Entities\User;
class NewUser extends User
{
    use UserFixture;
    public function __construct()
    {
        parent::__construct();
        $this->username = 'Brian Test';
        $this->password = 'password';
        $this->identifier = 'id.test';
        $this->token = 'token.test';
        $this->timeout = 10;
        $this->date = \DateTime::createFromFormat('m/d/Y', '06/20/1986');
    }
}