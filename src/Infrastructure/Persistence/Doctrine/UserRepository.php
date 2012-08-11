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
        if(is_null($user->getId())) return false;
        
        $q = $this->manager->createQuery('SELECT COUNT(u.id) FROM Domain\\Entities\\User u WHERE u.id = ?1');

        $q->setParameter(1, $user->getId());

        return $q->getSingleScalarResult() > 0;
    }

    public function store(User $user)
    {
        $this->manager->persist($user);
    }

    public function get($id)
    {
        return $this->manager->find('Domain\\Entities\\User', $id);
    }

    public function getAll()
    {
        return $this->manager->getRepository('Domain\\Entities\\User')
                    ->findAll();
    }

    public function getBy($conditions)
    {
        return $this->manager->getRepository('Domain\\Entities\\User')
                             ->findBy($conditions);
    }

    public function delete(User $user)
    {
        $this->manager->remove($user);
    }
}