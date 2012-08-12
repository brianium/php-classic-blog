<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
class RepositoryTestBase extends TestBase
{
    protected $manager;
    protected $classes = ['User', 'Post'];
    protected $tool;
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $paths = [APP_SRC . DS . 'Infrastructure' . DS . 'Persistence' . DS . 'Doctrine' . DS . 'mappings'];
        $isDevMode = true;
        $dbParams = [
            'user' => 'root',
            'driver' => 'pdo_sqlite',
            'dbname' => 'blog.test',
            'memory' => true
        ];
        $config = Setup::createXMLMetadataConfiguration($paths, $isDevMode);
        $this->manager = EntityManager::create($dbParams, $config);
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

    protected function findBy($type, $conditions)
    {
        return $this->manager->getRepository($type)
                      ->findBy($conditions);
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

    private function buildClassMeta()
    {
        $this->classes = array_map(function($entity){
            return $this->manager->getClassMetadata('Domain\\Entities\\' . $entity);
        }, $this->classes);
    }

    private function getRepo()
    {
        $reflection = new \ReflectionClass(get_class($this));
        $test = $reflection->getShortName();
        $repoClass = 'Infrastructure\\Persistence\\Doctrine\\' . str_replace('Test', '', $test);
        return new $repoClass($this->manager);
    }
}