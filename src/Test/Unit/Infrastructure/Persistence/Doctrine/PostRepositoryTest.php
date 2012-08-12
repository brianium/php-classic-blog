<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
class PostRepositoryTest extends RepositoryUnitTestCase
{
    public function test_repo_should_implement_PostRepository_interface()
    {
        $this->assertInstanceOf('Domain\\Repositories\\PostRepository', $this->repo);
    }
}