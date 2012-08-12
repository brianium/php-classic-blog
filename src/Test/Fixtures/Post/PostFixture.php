<?php
namespace Test\Fixtures\Post;
use Domain\Entities\Post;
trait PostFixture
{
    public function getAsPost()
    {
        $post = new Post();
        $post->setTitle($this->getTitle());
        $post->setExcerpt($this->getExcerpt());
        $post->setContent($this->getContent());
        $post->setDate($this->getDate());
        return $post;
    }

    abstract public function getTitle();
    abstract public function getExcerpt();
    abstract public function getContent();
    abstract public function getDate();
}