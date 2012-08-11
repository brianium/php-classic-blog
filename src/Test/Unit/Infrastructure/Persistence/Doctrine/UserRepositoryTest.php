<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
use Test\TestBase;
use Infrastructure\Persistence\Doctrine\UserRepository;
use Doctrine\ORM\EntityManager;
class UserRepositoryTest extends TestBase
{
    protected $entityManager;
    protected $repo;

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder('Doctrine\\ORM\\EntityManager')
                                    ->disableOriginalConstructor()
                                    ->getMock();

        $this->repo = new UserRepository($this->entityManager);
    }

    public function test_constructor_should_set_manager_property_to_EntityManager()
    {
        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->getObjectValue($this->repo, 'manager'));
    }

    public function test_repo_should_implement_UserRepository_interface()
    {
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->repo);
    }
}