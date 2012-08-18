<?php
namespace Domain\Entities;
class Entity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function isNew()
    {
        return is_null($this->id);
    }
}