<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Domain\Entities\User;
use Doctrine\ORM\Tools\Setup;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\UserRepository;
class UserRepositoryTest extends TestBase
{
    protected $manager;
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
        $this->manager = EntityManager::create($dbParams, $config);
        $this->tool = new SchemaTool($this->manager);
        $this->classes = [
            $this->manager->getClassMetadata('Domain\\Entities\\User')
        ];
        $this->tool->createSchema($this->classes);
        $this->fixture = $this->loadFixture('Test\\Fixtures\\User\\NewUser', 'Domain\\Entities\\User');
        $this->user = $this->fixture->getAsUser();
        $this->repo = new UserRepository($this->manager);
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
        $this->manager->flush();
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

    public function test_should_store_now_as_default_date()
    {
        $this->storeUser();

        $user = $this->getUser(['id' => 1]);

        $this->assertEquals(new \DateTime('now'), $user->getDate());
    }

    public function test_should_get_user_by_id()
    {
        $this->persistUser();

        $user = $this->repo->get(1);

        $this->assertEquals($this->user, $user);
    }

    public function test_get_should_return_null_if_not_found()
    {
        $user = $this->repo->get(1);

        $this->assertNull($user);
    }

    public function test_getAll_should_return_all_users()
    {
        $this->manager->persist($this->user);
        $other = $this->fixture->getAsUser();
        $other->setUsername("Jennie Test");
        $other->setIdentifier("jennie.test");
        $other->setToken("jennie.token");
        $this->manager->persist($other);
        $this->flush();

        $all = $this->repo->getAll();

        $this->assertEquals([$this->user, $other], $all);
    }

    public function test_getAll_should_return_empty_array_if_none()
    {
        $users = $this->repo->getAll();

        $this->assertEmpty($users);
    }

    public function test_getBy_should_return_array_matching_condition()
    {
        $this->persistUser();

        $users = $this->repo->getBy(['username' => $this->fixture->getUsername()]);

        $this->assertEquals($this->user, $users[0]);
    }

    public function test_getBy_should_return_empty_array_when_no_match()
    {
        $users = $this->repo->getBy(['id' => 99]);

        $this->assertEmpty($users);
    }

    public function test_getByUsername_should_return_single_user()
    {
        $this->persistUser();

        $user = $this->repo->getByUsername($this->fixture->getUsername());

        $this->assertEquals($this->user, $user);
    }

    public function test_getByUsername_should_return_null_if_user_does_not_exist()
    {
        $user = $this->repo->getByUsername('doesnotexist');

        $this->assertNull($user);
    }

    public function test_delete_should_remove_user()
    {
        $this->manager->persist($this->user);
        $this->flush();

        $this->repo->delete($this->user);
        $this->flush();

        $this->assertEmpty($this->manager->getRepository('Domain\\Entities\\User')->findAll());
    }

    public function test_flush_should_update_user()
    {
        $this->persistUser();
        $this->user->setUsername("Brian Scaturro");

        $this->flush();

        $user = $this->getUser(['username' => 'Brian Scaturro']);
        $this->assertNotNull($user);
    }

    protected function storeUser()
    {
        $this->repo->store($this->user);
        $this->flush();
    }

    protected function persistUser()
    {
        $this->doctrinePersist($this->user);
    }

    protected function getUser($conditions)
    {
        return $this->findBy('Domain\\Entities\\User', $conditions)[0];
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
}