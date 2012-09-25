<?php
namespace Presentation\Services;
use Domain\Repositories\UserRepository;
use Domain\Entities\User;
use Domain\UserAuthenticator;
class SlimAuthenticationService
{
    protected $slim;
    protected $userRepo;
    protected $userAuth;
    protected $routes;

    public function __construct(\Slim $slim, UserRepository $userRepo, UserAuthenticator $auth, $routes = [])
    {
        $this->slim = $slim;
        $this->userRepo = $userRepo;
        $this->userAuth = $auth;
        $this->routes = $routes;
    }

    public function addRoute($route)
    {
        $this->routes[] = $route;
        return $this;
    }

    public function isAuthenticated($cookiename)
    {
        if(empty($this->routes)) return true;

        foreach($this->routes as $route) {
            if(!$this->matchesCurrentRoute($route)) continue;
            return $this->authCookieIsValid($cookiename);
        }
        return true;
    }

    public function canLogin($username, $password)
    {
        return $this->userAuth->isAuthenticated($username, $password);
    }

    public function getLoggedInUser($cookiename)
    {
        list($identifier, $token) = $this->splitAuthCookie($cookiename);
        if($identifier) return $this->userRepo->getBy(['identifier' => $identifier])[0];
    }

    public function login(User $user, $cookiename, $callback = null)
    {
        $user->refresh();
        $this->setAuthCookie($cookiename, $user);
        $this->userRepo->store($user);
        if(is_callable($callback)) $callback();
    }

    public function register(User $user, $cookiename, $callback = null)
    {
        $this->userAuth->hashPassword($user);
        $this->login($user, $cookiename, $callback);
    }

    public function setAuthCookie($cookiename, User $user)
    {
        $this->slim->setcookie($cookiename, $user->getTokenString());
    }

    public function matchesCurrentRoute($route)
    {
        $pattern = $route[0] == '/';
        if(!$pattern) $route = '/^\/' . $route . '$/';
        return (bool)preg_match($route, $this->slim->request()->getPath());
    }

    public function authCookieIsValid($cookiename)
    {
        list($identifier, $token) = $this->splitAuthCookie($cookiename);

        if(!$identifier) return false;

        $users = $this->userRepo->getBy(['identifier' => $identifier]);
        if(!$users) return false;

        return !$this->hasExpired($users[0], $token);
    }

    protected function hasExpired($user, $token)
    {
        return $token != $user->getToken() || time() > $user->getTimeout();
    }

    protected function splitAuthCookie($cookiename) {
        $cookie = $this->slim->getCookie($cookiename);
        if(is_null($cookie)) return [false, false];
        return explode(':', $cookie);
    }
}