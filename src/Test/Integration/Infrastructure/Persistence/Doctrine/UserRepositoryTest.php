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
    protected $fixture;
    protected $user;
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
        $this->em = EntityManager::create($dbParams, $config);
        $this->tool = new SchemaTool($this->em);
        $this->classes = [
            $this->em->getClassMetadata('Domain\\Entities\\User')
        ];
        $this->tool->createSchema($this->classes);
        $this->fixture = $this->loadFixture('Test\\Fixtures\\User\\NewUser', 'Domain\\Entities\\User');
        $this->user = $this->fixture->getAsUser();
        $this->repo = new UserRepository($this->em);
    }

    public function tearDown()
    {
        $this->tool->dropSchema($this->classes);
    }

    public function test_should_store_new_User()
    {
        $this->storeUser();
        
        $q = $this->query('SELECT COUNT(u.id) FROM Domain\\Entities\\User u');

        $this->assertEquals(1, $q->getSingleScalarResult());
    }

    public function test_contains_should_return_true_when_user_saved()
    {
        $this->storeUser();
        $this->assertTrue($this->repo->contains($this->user));
    }

    public function test_contains_should_return_false_when_user_not_saved()
    {
        $this->assertFalse($this->repo->contains($this->user));
    }

    public function test_should_store_username()
    {
        $this->storeUser();

        $user = $this->getUser(['username' => $this->fixture->getUsername()]);

        $this->assertEquals($this->fixture->getUsername(), $user->getUsername());
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_with_null_username()
    {
        $this->user->setUsername(null);
        $this->storeUser();
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_with_non_unique_username()
    {
        $this->storeUser();
        $other = $this->fixture->getAsUser();
        $this->repo->store($other);
        $this->flush();
    }

    public function test_should_store_password()
    {
        $this->storeUser();

        $user = $this->getUser(['password' => $this->fixture->getPassword()]);

        $this->assertEquals($this->fixture->getPassword(), $user->getPassword());
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_null_password()
    {
        $this->user->setPassword(null);
        $this->storeUser();
    }

    public function test_should_store_identifier()
    {
        $this->storeUser();

        $user = $this->getUser(['identifier' => $this->fixture->getIdentifier()]);

        $this->assertEquals($this->fixture->getIdentifier(), $user->getIdentifier());
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_null_identifier()
    {
        $this->user->setIdentifier(null);
        $this->storeUser();
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_non_unique_identifier()
    {
        $this->storeUser();
        $other = $this->fixture->getAsUser();
        $this->repo->store($other);
        $this->flush();
    }

    public function test_should_store_token()
    {
        $this->storeUser();

        $user = $this->getUser(['token' => $this->fixture->getToken()]);

        $this->assertEquals($this->fixture->getToken(), $user->getToken());
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_null_token()
    {
        $this->user->setToken(null);
        $this->storeUser();
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_non_unique_token()
    {
        $this->storeUser();
        $other = $this->fixture->getAsUser();
        $this->repo->store($other);
        $this->em->flush();
    }

    public function test_should_store_timeout()
    {
        $this->storeUser();

        $user = $this->getUser(['timeout' => $this->fixture->getTimeout()]);

        $this->assertEquals($this->fixture->getTimeout(), $user->getTimeout());
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_null_timeout()
    {
        $this->user->setTimeout(null);
        $this->storeUser();
    }

    public function test_should_store_date()
    {
        $this->storeUser();

        $user = $this->getUser(['date' => $this->fixture->getDate()]);

        $this->assertEquals($this->fixture->getDate(), $user->getDate());
    }

    protected function storeUser()
    {
        $this->repo->store($this->user);
        $this->flush();
    }

    protected function getUser($conditions)
    {
        return $this->getQueryResult('Domain\\Entities\\User', $conditions)[0];
    }

    protected function getQueryResult($type, $conditions)
    {
        $reflectionClass = new \ReflectionClass($type);
        $q = $this->em->createQuery();
        $alias = strtolower($reflectionClass->getShortName());
        $dql = "SELECT $alias FROM $type $alias WHERE";
        $i = 1;
        foreach($conditions as $key => $value) {
            $dql .= " $alias.$key = ?$i";
            $q->setParameter($i, $value);
            if($i < sizeof($conditions))
                $dql .= ' AND';
            $i++;
        }
        $q->setDql($dql);
        return $q->getResult();
    }

    /**
     * Shortcut to call flush on EntityManager
     */
    protected function flush()
    {
        $this->em->flush();
    }

    /**
     * Shortcut for createQuery on EntityManager
     */
    protected function query($dql)
    {
        return $this->em->createQuery($dql);
    }
}