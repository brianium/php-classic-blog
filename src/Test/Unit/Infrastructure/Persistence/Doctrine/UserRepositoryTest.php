<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
class UserRepositoryTest extends RepositoryUnitTestCase
{
    public function test_repo_should_implement_UserRepository_interface()
    {
        $this->assertInstanceOf('Domain\\Repositories\\UserRepository', $this->repo);
    }
}