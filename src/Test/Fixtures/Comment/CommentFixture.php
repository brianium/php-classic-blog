<?php
namespace Test\Fixtures\Comment;
use Domain\Entities\Comment;
trait CommentFixture
{
    public function getAsComment()
    {
        $comment = new Comment();
        $comment->setText($this->getText());
        $comment->setDate($this->getDate());
        $comment->setCommenter($this->getCommenter());
        return $comment;
    }

    abstract public function getText();
    abstract public function getDate();
    abstract public function getCommenter();
}