<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Test\RepositoryTester;
use Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Doctrine\ORM\Tools\SchemaTool;
class RepositoryTestBase extends TestBase
{
    use RepositoryTester;
    protected $manager;
    protected $classes = ['User', 'Post', 'Comment'];
    protected $tool;
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->manager = EntityManagerFactory::getNewManager();
        $this->tool = new SchemaTool($this->manager);
        $this->buildClassMeta();
        $this->tool->createSchema($this->classes);
        $this->repo = $this->getRepo();
    }

    public function tearDown()
    {
        $this->tool->dropSchema($this->classes);
    }

    protected function doctrinePersist($object)
    {
        $this->manager->persist($object);
        $this->flush();
    }

    protected function findBy($conditions)
    {
        return $this->manager->getRepository($this->getEntityType())
                      ->findBy($conditions);
    }

    /**
     * Detach entities from the repository
     */
    protected function clear()
    {
        $this->manager->getRepository($this->getEntityType())->clear();
    }

    /**
     * Shortcut to call flush on EntityManager
     */
    protected function flush()
    {
        $this->manager->flush();
    }

    /**
     * Shortcut for createQuery on EntityManager
     */
    protected function query($dql)
    {
        return $this->manager->createQuery($dql);
    }

    private function getEntityType()
    {
        $reflection = new \ReflectionClass(get_class($this));
        $test = $reflection->getShortName();
        $entityType = 'Domain\\Entities\\' . str_replace('RepositoryTest', '', $test);
        return $entityType;
    }

    private function buildClassMeta()
    {
        $this->classes = array_map(function($entity){
            return $this->manager->getClassMetadata('Domain\\Entities\\' . $entity);
        }, $this->classes);
    }
}