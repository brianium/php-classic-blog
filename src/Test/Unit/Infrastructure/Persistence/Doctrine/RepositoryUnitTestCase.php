<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Test\RepositoryTester;
class RepositoryUnitTestCase extends TestBase
{
    use RepositoryTester;
    protected $manager;
    protected $repo;

    public function setUp()
    {
        $this->manager = $this->getMockBuilder('Doctrine\\ORM\\EntityManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->repo = $this->getRepo();
    }

    public function test_constructor_should_set_manager_property_to_EntityManager()
    {
        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->getObjectValue($this->repo, 'manager'));
    }
}