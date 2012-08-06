<?php
namespace Test\Unit\Entities;
use Domain\Entities\Comment;
use Domain\Entities\Post;
use Test\Unit\UnitTestBase;
use Domain\Commenter;
class CommentTest extends UnitTestBase
{
    protected $comment;
    protected $commenter;

    public function setUp()
    {
        parent::setUp();
        $this->comment = new Comment();
        $this->commenter = new Commenter("Brian Scaturro", "scaturrob@gmail.com", "http://brianscaturro.com");
    }

    public function test_getId_should_return_id()
    {
        $this->setObjectValue($this->comment, 'id', 1);
        $this->assertEquals(1, $this->comment->getId());
    }

    public function test_getText_should_return_text()
    {
        $this->setObjectValue($this->comment, 'text', 'Great post!');
        $this->assertEquals('Great post!', $this->comment->getText());
    }

    public function test_setText_should_set_text()
    {
        $this->comment->setText('Edited comment!');
        $this->assertEquals('Edited comment!', $this->getObjectValue($this->comment, 'text'));
    }

    public function test_getDate_should_return_date()
    {
        $date = \DateTime::createFromFormat('m/d/Y', '06/20/1986');
        $this->setObjectValue($this->comment, 'date', $date);
        $this->assertEquals($date, $this->comment->getDate());
    }

    public function test_setDate_should_set_date()
    {
        $date = new \DateTime('now');
        $this->comment->setDate($date);
        $this->assertEquals($date, $this->getObjectValue($this->comment, 'date'));
    }

    public function test_getCommenter_should_return_commenter_value_object()
    {
        $this->setObjectValue($this->comment, 'commenter_name', $this->commenter->getName());
        $this->setObjectValue($this->comment, 'commenter_email', $this->commenter->getEmail());
        $this->setObjectValue($this->comment, 'commenter_url', $this->commenter->getUrl());

        $this->assertEquals($this->commenter, $this->comment->getCommenter());
    }

    public function test_setCommenter_should_set_commenter_name_to_Commenter_name()
    {
        $this->assertEquals($this->setCommenter()->getName(), $this->getObjectValue($this->comment, 'commenter_name'));
    }

    public function test_setCommenter_should_set_commenter_email_to_Commenter_email()
    {
        $this->assertEquals($this->setCommenter()->getEmail(), $this->getObjectValue($this->comment, 'commenter_email'));
    }

    public function test_setCommenter_should_set_commenter_url_to_Commenter_url()
    {
        $this->assertEquals($this->setCommenter()->getUrl(), $this->getObjectValue($this->comment, 'commenter_url'));
    }

    public function test_getPost_should_return_post()
    {
        $post = new Post();
        $this->setObjectValue($this->comment, 'post', $post);
        $this->assertSame($post, $this->comment->getPost());
    }

    public function test_setPost_should_set_post()
    {
        $post = new Post();
        $this->comment->setPost($post);
        $this->assertSame($post, $this->getObjectValue($this->comment, 'post'));
    }

    public function test_setPost_should_add_comment_to_post_comments()
    {
        $post = new Post();
        $this->comment->setPost($post);
        $this->assertContains($this->comment, $post->getComments());
    }

    protected function setCommenter()
    {
        $commenter = new Commenter('Johnny Test', 'jtest@email.com', 'http://jtest.com');
        $this->comment->setCommenter($commenter);
        return $commenter;
    }
}