<?php
namespace Domain\Repositories;
use Domain\Entities\Entity;
interface Repository
{
    function contains(Entity $entity);
    function store(Entity $entity);
    function delete(Entity $entity);
    function get($id);
    function getAll();
    function getBy($conditions);
}