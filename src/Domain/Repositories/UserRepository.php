<?php
namespace Domain\Repositories;
interface UserRepository extends Repository
{
	function getByUsername($username);
}