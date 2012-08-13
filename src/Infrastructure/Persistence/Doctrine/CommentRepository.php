<?php
namespace Infrastructure\Persistence\Doctrine;
use Domain\Repositories;
class CommentRepository extends RepositoryBase implements Repositories\CommentRepository
{
    protected $type = 'Domain\\Entities\\Comment';
}