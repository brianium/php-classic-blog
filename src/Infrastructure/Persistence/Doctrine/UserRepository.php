<?php
namespace Infrastructure\Persistence\Doctrine;
use Domain\Repositories;
use Domain\Entities\User;
use Doctrine\ORM\EntityManager;
class UserRepository implements Repositories\UserRepository
{
    protected $manager;

    public function __construct(EntityManager $em)
    {
        $this->manager = $em;
    }

    public function getByUsername($username)
    {

    }

    public function contains(User $user)
    {

    }

    public function store(User $user)
    {
        $this->manager->persist($user);
    }
}