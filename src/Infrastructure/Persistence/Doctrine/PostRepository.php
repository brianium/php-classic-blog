<?php
namespace Infrastructure\Persistence\Doctrine;
use Domain\Repositories;
class PostRepository extends RepositoryBase implements Repositories\PostRepository
{
    protected $type = 'Domain\\Entities\\Post';

    public function getLatest($limit = 0)
    {
        $dql = "SELECT p FROM Domain\\Entities\\Post p ORDER BY p.date DESC";
        $query = $this->manager->createQuery($dql);

        if($limit > 0)
            $query->setMaxResults($limit);

        return $query->getResult();
    }
}