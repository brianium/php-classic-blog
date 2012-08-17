<?php
namespace Infrastructure\Persistence\Doctrine;
use Domain\Entities\Entity;
use Infrastructure\Persistence\Doctrine\EntityManagerFactory;
class RepositoryBase
{
    protected $manager;
    protected $type;

    public function __construct($em = null)
    {
        if(!class_exists($this->type))
            throw new \DomainException('protected property $type must specify fully qualified Entity class name');

        if(is_null($em))
            $em = EntityManagerFactory::getSingleton();

        if(!is_a($em, 'Doctrine\\ORM\\EntityManager'))
            throw new \InvalidArgumentException('Repository must be constructed with an instance of Doctrine\\ORM\\EntityManager');

        $this->manager = $em;
    }

    public function contains(Entity $entity)
    {
        $this->verifyType($entity);

        if(is_null($entity->getId())) return false;
        
        $q = $this->manager->createQuery("SELECT COUNT(e.id) FROM {$this->type} e WHERE e.id = ?1");

        $q->setParameter(1, $entity->getId());

        return $q->getSingleScalarResult() > 0;
    }

    public function store(Entity $entity)
    {
        $this->verifyType($entity);
        $this->manager->persist($entity);
    }

    public function get($id)
    {
        return $this->manager->find($this->type, $id);
    }

    public function getAll()
    {
        return $this->manager->getRepository($this->type)
                    ->findAll();
    }

    public function getBy($conditions)
    {
        return $this->manager->getRepository($this->type)
                             ->findBy($conditions);
    }

    public function delete(Entity $entity)
    {
        $this->verifyType($entity);
        $this->manager->remove($entity);
    }

    private function verifyType(Entity $entity)
    {
        if(!is_a($entity, $this->type))
            throw new \DomainException("$entity is not an instance of {$this->type}");
    }
}