<?php
namespace Domain\Entities;
use Domain\Commenter;
use Domain\Entities\Post;
class Comment extends Entity
{
    protected $text;
    protected $date;
    protected $post;

    //Commenter value object properties
    protected $commenter_name;
    protected $commenter_email;
    protected $commenter_url;

    public function __construct()
    {
        $this->date = new \DateTime('now');
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getCommenter()
    {
        return new Commenter($this->commenter_name, $this->commenter_email, $this->commenter_url);
    }

    public function setCommenter(Commenter $commenter)
    {
        $this->commenter_name = $commenter->getName();
        $this->commenter_email = $commenter->getEmail();
        $this->commenter_url = $commenter->getUrl();
    }

    public function getPost()
    {
        return $this->post;
    }

    public function setPost(Post $post)
    {
        $post->addComment($this);
        $this->post = $post;
    }

    public static function create($text, Commenter $c, Post $p)
    {
        $comment = new Comment();
        $comment->setText($text);
        $comment->setCommenter($c);
        $comment->setPost($p);
        return $comment;
    }
}