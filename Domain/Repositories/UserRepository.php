<?php
namespace Domain\Repositories;
use Domain\Entities\User;
interface UserRepository
{
	function getByUsername($username);
	function contains(User $user);
}