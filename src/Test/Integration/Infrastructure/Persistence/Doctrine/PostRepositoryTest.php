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
        $this->fixture = $this->loadFixture('Test\\Fixtures\\Post\\NewPost', 'Domain\\Entities\\Post');
        $this->post = $this->fixture->getAsPost();
    }

    public function test_should_store_new_Post()
    {
        $this->storePost();

        $q = $this->query('SELECT COUNT(p.id) FROM Domain\\Entities\\Post p');

        $this->assertEquals(1, $q->getSingleScalarResult());
    }

    public function test_should_store_title()
    {
        $this->storePost();
        
        $post = $this->getPost(['title' => $this->fixture->getTitle()]);

        $this->assertEquals($this->fixture->getTitle(), $post->getTitle());
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_null_title()
    {
        $this->post->setTitle(null);

        $this->storePost();
    }

    public function test_should_store_excerpt()
    {
        $this->storePost();

        $post = $this->getPost(['excerpt' => $this->fixture->getExcerpt()]);

        $this->assertEquals($this->fixture->getExcerpt(), $post->getExcerpt());
    }

    public function test_should_store_null_excerpt()
    {
        $this->post->setExcerpt(null);
        $this->storePost();

        $post = $this->getPost(['id' => 1]);

        $this->assertNotNull($post);
    }

    public function test_should_store_content()
    {
        $this->storePost();

        $post = $this->getPost(['content' => $this->fixture->getContent()]);

        $this->assertEquals($this->fixture->getContent(), $post->getContent());
    }

    /**
     * @expectedException \PDOException
     */
    public function test_should_not_store_null_content()
    {
        $this->post->setContent(null);

        $this->storePost();
    }

    public function test_should_store_date()
    {
        $this->storePost();

        $post = $this->getPost(['date' => $this->fixture->getDate()]);

        $this->assertEquals($this->fixture->getDate(), $post->getDate());
    }

    public function test_should_store_now_as_default_date()
    {
        $this->storePost();

        $post = $this->getPost(['id' => 1]);

        $this->assertEquals(new \DateTime('now'), $post->getDate());
    }

    /**
     * Store via repo
     */
    public function storePost()
    {
        $this->repo->store($this->post);
        $this->flush();
    }

    public function getPost($conditions)
    {
        return $this->findBy($conditions)[0];
    }
}