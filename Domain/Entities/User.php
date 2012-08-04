<?php
namespace Domain\Entities;
class User
{
	protected $username;

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getUsername()
	{
		return $this->username;
	}
}