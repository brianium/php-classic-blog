<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Domain\Entities\Post;
class PostRepositoryTest extends RepositoryTestBase
{
    protected $fixture;
    protected $post;

    public function setUp()
    {
        parent::setUp();
    }
}