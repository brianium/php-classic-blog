<?php
namespace Test\Unit\Entities;
use Domain\Entities\User;
use Test\Unit\UnitTestBase;
class UserTest extends UnitTestBase
{
	protected $user;

	public function setUp()
	{
		$this->user = new User();
	}

	public function test_Get_Username_should_return_username_value()
	{
		$this->setObjectValue($this->user, 'username', 'johnny.test');
		$this->assertEquals("johnny.test", $this->user->getUsername());
	}

	public function test_Set_username_should_set_username_value()
	{
		$this->user->setUsername("brian.scaturro");
		$this->assertEquals("brian.scaturro", $this->getObjectValue($this->user, "username"));
	}
}