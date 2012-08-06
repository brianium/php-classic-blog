<?php
namespace Domain;
class Commenter
{
    private $name;
    private $email;
    private $url;

    public function __construct($name, $email, $url) 
    {
        $this->name = $name;
        $this->email = $email;
        $this->url = $url;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUrl()
    {
        return $this->url;
    }
}