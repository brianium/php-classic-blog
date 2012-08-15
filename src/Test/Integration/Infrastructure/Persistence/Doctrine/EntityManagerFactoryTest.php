<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\EntityManagerFactory;
class EntityManagerFactoryTest extends TestBase
{
    protected $manager;

    public function setUp()
    {
        parent::setUp();
        $this->manager = EntityManagerFactory::getManager();
    }

    public function test_static_getInstance_should_return_instance_of_EntityManager()
    {
        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->manager);
    }

    public function test_manager_uses_pdo_sqlite_when_in_dev_mode()
    {
        $params = $this->getParams();

        $this->assertEquals('pdo_sqlite', $params['driver']);
    }

    public function test_manager_uses_in_memory_when_in_dev_mode()
    {
        $this->assertTrue($this->getParams()['memory']);
    }

    public function test_manager_uses_blog_test_database_when_in_dev_mode()
    {
        $this->assertEquals('blog.test', $this->getParams()['dbname']);
    }

    protected function getParams()
    {
        $connection = $this->manager->getConnection();

        return $this->getObjectValue($connection, '_params');
    }
}