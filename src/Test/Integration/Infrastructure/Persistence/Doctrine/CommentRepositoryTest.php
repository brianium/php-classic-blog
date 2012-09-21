<?php
namespace Test\Integration\Infrastructure\Persistence\Doctrine;
use Domain\Commenter;
class CommentRepositoryTest extends RepositoryTestBase
{
    protected $fixture;
    protected $comment;

    public function setUp()
    {
        parent::setUp();
        $this->fixture = $this->loadFixture('Test\\Fixtures\\Comment\\NewComment', 'Domain\\Entities\\Comment');
        $this->comment = $this->fixture->getAsComment();
    }

    public function test_should_store_new_Comment()
    {
        $this->storeComment();

        $q = $this->query('SELECT COUNT(c.id) FROM Domain\\Entities\\Comment c');

        $this->assertEquals(1, $q->getSingleScalarResult());
    }

    public function test_should_store_text()
    {
        $this->storeComment();

        $comment = $this->getComment(['text' => $this->fixture->getText()]);

        $this->assertEquals($this->fixture->getText(), $comment->getText());
    }

    /**
     * @expectedException   Doctrine\DBAL\DBALException
     */
    public function test_should_not_store_null_text()
    {
        $this->comment->setText(null);

        $this->storeComment();
    }

    public function test_should_store_date_as_now()
    {
        $this->storeComment();

        $comment = $this->getComment(['date' => $this->fixture->getDate()]);

        $this->assertEquals($this->fixture->getDate(), $comment->getDate());
    }

    public function test_should_store_commenter()
    {
        $this->storeComment();

        $comment = $this->getComment([
            'commenter_name' => $this->fixture->getCommenter()->getName(),
            'commenter_email' => $this->fixture->getCommenter()->getEmail(),
            'commenter_url' => $this->fixture->getCommenter()->getUrl()
        ]);

        $this->assertEquals($this->fixture->getCommenter(), $comment->getCommenter());
    }

    /**
     * @expectedException Doctrine\DBAL\DBALException
     */
    public function test_should_not_store_commenter_with_null_name()
    {
        $commenter = new Commenter(null, "test@email.com", "http://www.test.com");
        $this->comment->setCommenter($commenter);

        $this->storeComment();
    }

    /**
     * @expectedException Doctrine\DBAL\DBALException
     */
    public function test_should_not_store_commenter_with_null_email()
    {
        $commenter = new Commenter("Test Name", null, "http://www.test.com");
        $this->comment->setCommenter($commenter);

        $this->storeComment();
    }

    public function test_should_store_commenter_with_null_url()
    {
        $commenter = new Commenter("Test Name", "test@email.com", null);
        $this->comment->setCommenter($commenter);

        $this->storeComment();
        $comment = $this->getComment(['id' => 1]);

        $this->assertEquals($commenter, $comment->getCommenter());
    }

    /**
     * Store via repo
     */
    public function storeComment()
    {
        $this->repo->store($this->comment);
        $this->flush();
    }

    public function getComment($conditions)
    {
        return $this->findBy($conditions)[0];
    }
}