<?php
namespace Domain\Entities;
use Doctrine\Common\Collections\ArrayCollection;
use Domain\PasswordHasher;
class User extends Entity
{
    protected $username;
    protected $password;
    protected $identifier;
    protected $token;
    protected $timeout;
    protected $date;
    protected $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->date = new \DateTime('now');
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function setTimeout($seconds)
    {
        $this->timeout = $seconds;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function addPost(Post $post)
    {
        $this->posts[] = $post;
    }

    public function getPosts()
    {
        return $this->posts;
    }

    public function refreshTimeout(\DateInterval $interval = null)
    {
        $now = new \DateTime('now');

        $now->add($interval ?: new \DateInterval('P1W'));

        $this->setTimeout($now->getTimestamp());
    }

    public function refreshIdentifier()
    {
        $hasher = new PasswordHasher();
        $this->setIdentifier($hasher->hash($this->getUsername()));
    }

    public function refreshToken()
    {
        $token = md5(uniqid(rand(), true));
        $this->setToken($token);
    }

    public function refresh()
    {
        $this->refreshTimeout();
        $this->refreshIdentifier();
        $this->refreshToken();
    }

    public function getTokenString()
    {
        return $this->identifier . ':' . $this->token;
    }

    public static function create($username, $password)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        return $user;
    }
}