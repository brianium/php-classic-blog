<?php
namespace Presentation\Services;
use Domain\Repositories\UserRepository;
class SlimAuthenticationService
{
    protected $slim;
    protected $userRepo;
    protected $routes;

    public function __construct(\Slim $slim, UserRepository $userRepo, $routes = [])
    {
        $this->slim = $slim;
        $this->userRepo = $userRepo;
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
            if('/' . $route == $this->slim->request()->getPath()) {
                $cookie = $this->slim->getCookie('cookiename');
                if(is_null($cookie)) return false;
            }
        }
    }
}