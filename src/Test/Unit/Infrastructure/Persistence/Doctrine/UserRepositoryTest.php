<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
class UserRepositoryTest extends RepositoryUnitTestCase
{
    public function test_constructor_should_set_manager_property_to_EntityManager()
    {
        $this->assertInstanceOf('Doctrine\\ORM\\EntityManager', $this->getObjectValue($this->repo, 'manager'));
    }

    public function test_repo_should_implement_UserRepository_interface()
    {
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->repo);
    }
}