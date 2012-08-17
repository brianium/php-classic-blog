<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Test\RepositoryTester;
use Infrastructure\Persistence\Doctrine\EntityManagerFactory;
use Test\Integration\Infrastructure\Persistence\Doctrine\DataTester;
class RepositoryTestBase extends TestBase
{
    use RepositoryTester;
    use DataTester;
    protected $manager;
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->manager = EntityManagerFactory::getNewManager();
        $this->createSchema($this->manager);
        $this->repo = $this->getRepo();
    }

    public function tearDown()
    {
        $this->dropSchema();
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
}