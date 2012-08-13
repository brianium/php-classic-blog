<?php
namespace Test\Unit\Infrastructure\Persistence\Doctrine;
class CommentRepositoryTest extends RepositoryUnitTestCase
{
    public function test_repo_should_implement_CommentRepository_interface()
    {
        $this->assertInstanceOf('Domain\\Repositories\\CommentRepository', $this->repo);
    }
}