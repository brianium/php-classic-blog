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
        //$this->fixture = $this->loadFixture('Test\\Fixtures\\Post\\NewPost', 'Domain\\Entities\\Post');
        //$this->post = $this->fixture->getAsPost();
    }

    public function testTrue()
    {
        $this->assertTrue(true);
    }
}