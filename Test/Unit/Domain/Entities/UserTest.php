<?php
use Domain\Entities\User;
class UserTest extends PHPUnit_Framework_TestCase
{
	public function testGetUserNameShouldReturnUserName()
	{
		$user = new User();
		$user->setUsername("johnny.test");
		$this->assertEquals("johnny.test",$user->getUsername());
	}
}