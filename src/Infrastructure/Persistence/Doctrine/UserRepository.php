<?php
namespace Infrastructure\Persistence\Doctrine;
use Domain\Repositories;
use Domain\Entities\User;
use Doctrine\ORM\EntityManager;
class UserRepository extends RepositoryBase implements Repositories\UserRepository
{
    protected $type = 'Domain\\Entities\\User';

    public function getByUsername($username)
    {
        return $this->manager->getRepository($this->type)
                             ->findOneBy(['username' => $username]);
    }
}