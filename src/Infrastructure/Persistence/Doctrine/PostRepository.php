<?php
namespace Infrastructure\Persistence\Doctrine;
use Domain\Repositories;
class PostRepository extends RepositoryBase implements Repositories\PostRepository
{
    protected $type = 'Domain\\Entities\\Post';
}