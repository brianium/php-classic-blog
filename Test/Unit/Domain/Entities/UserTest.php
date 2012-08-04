<?php
namespace Test\Unit\Entities;
use Domain\Entities\User;
use Test\Unit\UnitTestBase;
class UserTest extends UnitTestBase
{
	public function testGetUserNameShouldReturnUserName()
	{
		$user = new User();
		$this->setObjectValue($user, 'username', 'johnny.test');
		$this->assertEquals("johnny.test",$user->getUsername());
	}
}