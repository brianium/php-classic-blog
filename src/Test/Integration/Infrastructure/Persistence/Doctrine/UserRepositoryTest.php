<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\UserRepository;
class UserRepositoryTest extends TestBase
{
    protected $em;
    protected $classes;
    protected $tool;    

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
        $this->em = EntityManager::create($dbParams, $config);
        $this->tool = new SchemaTool($this->em);
        $this->classes = [
            $this->em->getClassMetadata('Domain\\Entities\\User')
        ];
        $this->tool->createSchema($this->classes);
    }

    public function tearDown()
    {
        $this->tool->dropSchema($this->classes);
    }

    public function test_should_store_new_User()
    {
        $user = $this->loadFixture('Test\\Fixtures\\User\\NewUser', 'Domain\\Entities\\User');
        $repo = new UserRepository($this->em);
        $repo->store($user->getAsUser());
        $this->em->flush();
        
        $query = $this->em->createQuery('SELECT COUNT(u.id) FROM Domain\\Entities\\User u');
        $num = $query->getSingleScalarResult();


        $this->assertEquals(1, $num);
    }
}