<?php
namespace Domain\Repositories;
use Domain\Entities\User;
interface UserRepository extends Repository
{
	function getByUsername($username);
}