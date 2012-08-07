<?php
namespace Domain;
class PasswordHasher
{
	public static function getUniqueSalt()
	{
		return substr(sha1(mt_rand()), 0, 22);
	}

	protected $cost;

	public function __construct($cost = 10)
	{
		$this->cost = $cost;
	}

	public function hash($password)
	{
		return crypt($password, '$2y$' . $this->cost . '$' . self::getUniqueSalt());
	}

	public function checkPassword($hashed, $password)
	{
		$fullSalt = substr($hashed, 0, 29);
		$newHash = crypt($password, $fullSalt);
		return $hashed == $newHash;
	}
}