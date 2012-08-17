<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Doctrine\ORM\Tools\SchemaTool;
trait DataTester
{
    protected $classes = ['User', 'Post', 'Comment'];
    protected $tool;
    protected $manager;

    public function createSchema($manager)
    {
        if(!$this->manager)
            $this->manager = $manager;
        $this->tool = new SchemaTool($manager);
        $this->buildClassMeta();
        $this->tool->createSchema($this->classes);
    }

    public function dropSchema()
    {
        $this->tool->dropSchema($this->classes);
    }

    protected function buildClassMeta()
    {
        $this->classes = array_map(function($entity){
            return $this->manager->getClassMetadata('Domain\\Entities\\' . $entity);
        }, $this->classes);
    }
}